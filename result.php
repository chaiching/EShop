<?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username, password=:password, confirm_password=:confirm_password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $password = md5($_POST['password']);
                $confirm_password = md5($_POST['confirm_password']);
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));

                /*echo strlen($password);*/
                if (empty($password || $confirm_password)) {
                    echo "Please enter password";
                } elseif ($password != $confirm_password) {
                    echo "Password does not match";
                    } elseif (strlen ($password) <6 ) {
                    echo "Password minimum 6 word";
                } else {

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':confirm_password', $confirm_password);
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
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            /*if (strlen($password) < 6) {
                echo "Password minimum 6 word";
            } else {
            }*/
            /*if($password==$confirm_password) {

            }else {
                echo "Your password does not match";
            }*/
        }
        ?>