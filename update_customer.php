<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <!-- PHP read record by username will be here -->
        <?php
        // get passed parameter value, in this case, the record username
        // isset() is a PHP function used to verify if a value is there or not
        $c_username = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record username not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, password, email, first_name, last_name, gender, date_of_birth, account_status, register_date_time FROM customers WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $c_username);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $u_username = $row['username'];
            $current_password = $row['password'];
            $email = $row['email'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $account_status = $row['account_status'];
            $register_date_time = $row['register_date_time'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr>";
            echo "<td>{$u_username}</td>";
            echo "<td>{$current_password}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$first_name}</td>";
            echo "<td>{$last_name}</td>";
            echo "<td>{$gender}</td>";
            echo "<td>{$date_of_birth}</td>";
            echo "<td>{$account_status}</td>";
            echo "<td>{$register_date_time}</td>";
            echo "<td>";
            // read one record
            echo "<a href='read_customer.php?username={$c_username}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='update_customer.php?username={$c_username}' class='btn btn-primary m-r-1em'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_user({$c_username});'  class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

        ?>

        <!-- HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->
        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // posted values
                $current_password = ($_POST['current_password']);
                $new_password = ($_POST['new_password']);
                $confirm_password = ($_POST['confirm_password']);
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));

                // prepare select query
                $query = "SELECT username, password, email, first_name, last_name, gender, date_of_birth, account_status, register_date_time FROM customers WHERE username = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                $stmt->bindParam(1, $c_username);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $flag = 1;
                $message = "";
                $nowyear = date("Y"); //today's date's year
                $year = substr($date_of_birth, 0, 4);
                $myage = $nowyear - $year;

                if ($email == "" || $first_name == "" || $last_name == "" || $gender == "" || $date_of_birth == "") {
                    $flag = 0;
                    $message = "Please fill in the blank. ";
                }
                if ($myage < 18) {
                    $flag = 0;
                    $message =  $message . "Must above or 18 years old. ";
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $flag = 0;
                    $message =  $message . "Invalid email format. ";
                }

                $query = "SELECT email FROM customers WHERE email = ?";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (is_array($row)) {
                        $flag = 0;
                        $message =  $message . "Email already existed. Please use another email. ";
                }
                
                /*if ($_POST['email'] != $email_address && $row['email'] == $email_address) {
                    $flag = 0;
                    $message = $message . "Email already existed. Please use another email. ";
                }
                var_dump($email_address);
                echo $_POST['email'];
                echo "<br>";
                echo $row['email'];
                echo "<br>";
                echo $email_address;*/

                if ($current_password == "" && $new_password == "" && $confirm_password == "") {
                    if ($flag == 1) {
                        $query = "UPDATE customers
                            SET email=:email, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE username=:username";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':username', $u_username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':first_name', $first_name);
                        $stmt->bindParam(':last_name', $last_name);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);

                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was updated.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>$message</div>";
                    }
                } else {
                    if ($current_password == "" || $new_password == "" || $confirm_password == "") {
                        $flag = 0;
                        $message = "Please fill in the blank. ";
                    }

                    if (strlen($current_password) < 6 || strlen($new_password) < 6 || strlen($confirm_password) < 6) {
                        $flag = 0;
                        $message = $message . "Password minimum 6 character .";
                    }

                    if ($new_password != $confirm_password) {
                        $flag = 0;
                        $message = $message . "New password and Confirm password must be the same. ";
                    }

                    if ($new_password == $current_password) {
                        $flag = 0;
                        $message = $message . "New password can't same as Current password. ";
                    }


                    if ($flag == 1) {
                        $query = "SELECT username, password FROM customers WHERE username = ? LIMIT 0,1";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(1, $c_username);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (is_array($row)) {
                            if (md5($current_password) != $row['password']) {
                                $flag = 0;
                                $message =  $message . "Wrong password. ";
                            } else {
                                $query = "UPDATE customers
                            SET password=:new_password, email=:email, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE username=:username";
                                $stmt = $con->prepare($query);
                                $stmt->bindParam(':username', $u_username);
                                $h_password = md5($new_password);
                                $stmt->bindParam(':new_password', $h_password);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':first_name', $first_name);
                                $stmt->bindParam(':last_name', $last_name);
                                $stmt->bindParam(':gender', $gender);
                                $stmt->bindParam(':date_of_birth', $date_of_birth);

                                // Execute the query
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Record was updated.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                                }
                            }
                        }
                    } else {
                        echo "<div class='alert alert-danger'>$message</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$c_username}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($u_username, ENT_QUOTES);  ?></td>
                    </td>
                </tr>
                <tr>
                    <td>Current Password</td>
                    <td><input type='password' name='current_password' class='form-control' id="myInput" />
                    </td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='password' name='new_password' class='form-control' id="myInput" />
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control' id="myInput" />
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" id="female" value="1" <?php echo ($gender == '1') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="female">
                            Female
                        </label>
                        <input type="radio" name="gender" id="male" value="0" <?php echo ($gender == '0') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="male">
                            Male
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' value="<?php echo date($date_of_birth, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td><?php echo htmlspecialchars($account_status != '0' ? 'Unonline' : 'Online', ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Register Date & Time</td>
                    <td><?php echo htmlspecialchars($register_date_time, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='index_customer.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>