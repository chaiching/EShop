<!DOCTYPE HTML>
<html>

<head>
    <title>Login Form</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">

        <!-- PHP code to read records will be here -->
        <?php
        //start the session
        session_start();

        if ($_POST) {

            //$username = "xxx";
            //$password = "987654";
            $lusername = htmlspecialchars(strip_tags($_POST['username']));
            $lpassword = htmlspecialchars(strip_tags($_POST['password']));

            $flag = 1;
            /*echo $username;*/

            /*if (isset($username)) {*/

            if ($lusername == "" || $lpassword == "") {
                $flag = 0;
                echo "<div class='alert alert-danger'>Please fill in the blank.</div>";
            }
            if (strlen($lpassword) < 6) {
                $flag = 0;
                echo "<div class='alert alert-danger'>Password minimum 6 character.</div>";
            }

            if ($flag == 1) {

                // include database connection
                include 'config/database.php';

                // insert query
                $query = "SELECT username, password, account_status FROM customers WHERE username = ?";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(1, $lusername);

                // Execute the query
                $stmt->execute();

                // table body will be here
                // retrieve our table contents
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                /*var_dump($row);*/

                // this is how to get number of rows returned
                //just for count the row 
                /*$row = $stmt->rowCount();*/

                //check if more than 0 record found
                /*if ($row > 0) {*/


                if (is_array($row)) {
                    /*$username == $row['username'];*/
                    if (md5($lpassword) == $row['password']) {
                        if ($row['account_status'] == 1) {
                            //set session variables
                            $_SESSION["username"] = $_POST["username"];
                            header("location: welcome.php");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'>Account unactive.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Password not match.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>User not found.</div>";
                }

                // Execute the query
                if ($stmt->execute()) {
                }
            }
            //}
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="login-form bg-light mt-4 p-4">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row g-3 text-center">
                            <h4>Please Sign In</h4>
                            <div class="col-12">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                            </div>
                            <div class="col-12">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                            <div class="col-12 d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </form>
                        <hr class="mt-4">
                        <div class="col-12">
                            <p class="text-center mb-0 text-muted"> @ 2002-2021 </p>
                            <!--<a href="#">Signup</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>