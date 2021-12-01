<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <!-- container -->
    <?php include 'menu.php' ?>
    <div class="container">

        <div class="page-header">
            <h1>Customer Create</h1>
        </div>
        <!-- html form to create customer will be here -->

        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));

                /*echo $password;
                echo strlen($password);*/
                $flag = 1;
                $message = "";
                $nowyear = date("Y"); //today's date's year
                $year = substr($date_of_birth, 0, 4);

                if ($username == "" || $password == "" || $email == "" || $first_name == "" || $last_name == "" || $gender == "" || $date_of_birth == "") {
                    $flag = 0;
                    $message = "Please fill in the blank. ";
                }
                if (strlen($password) < 6) {
                    $flag = 0;
                    $message = $message . "Password minimum 6 character. ";
                }

                /*if ($password != $confirm_password) {
                    $flag = 0;
                    $message = $message . "Password does not match. ";
                }*/

                /*$year = new DateTime($date_of_birth);
                $nowyear = new DateTime($nowyear);

                $interval = $year->diff($nowyear);

                $myage = $interval->y;*/

                $myage = $nowyear - $year;

                if ($myage < 18) {
                    $flag = 0;
                    $message =  $message . "Must above or 18 years old. ";
                }

                $query = "SELECT username FROM customers WHERE username =?";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $username);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (is_array($row)) {
                    $flag = 0;
                    $message =  $message . "Username already existed. Please use another username. ";
                }

                $query = "SELECT email FROM customers WHERE email =?";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (is_array($row)) {
                    $flag = 0;
                    $message =  $message . "Email already existed. Please use another email. ";
                }

                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $flag = 0;
                    $message =  $message . "Invalid email format. ";
                }

                if ($flag == 1) {

                    // insert query
                    $query = "INSERT INTO customers SET username=:username, password=:password, email=:email, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth";

                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $n_password = md5($password);
                    $stmt->bindParam(':password', $n_password);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':date_of_birth', $date_of_birth);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>$message</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' id="myInput" />
                        <!-- An element to toggle between password visibility -->
                        <input type="checkbox" onclick="myFunction()">Show Password
                    </td>
                </tr>
                <script>
                    function myFunction() {
                        var x = document.getElementById("myInput");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    }
                </script>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" id="female" value="1">
                        <label class="form-check-label" for="female">
                            Female
                        </label>
                        <input type="radio" name="gender" id="male" value="0">
                        <label class="form-check-label" for="male">
                            Male
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index_customer.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>