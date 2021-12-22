<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php include 'menu.php'; ?>
        <div class="page-header">
            <h1>Create Order</h1>
        </div>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
            <table class='table table-hover table-responsive table-bordered'>
                <?php

                // include database connection
                include 'config/database.php';

                if ($_POST) {

                    // echo "<pre>";
                    // var_dump($_POST);
                    // echo "</pre>";

                    //posted value
                    $customer_username = $_POST['customer_username'];
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];

                    $flag = 1;
                    // echo "<pre>";
                    // var_dump($product_id);
                    // echo "</pre>";

                    if ($customer_username == "") {
                        $flag = 0;
                        // echo "<div class='alert alert-danger'>Please select username.</div>";
                    }

                    //loop see which line have information
                    for ($y = 0; $y < count($product_id); $y++) {
                        if ($product_id[$y] == "" || $quantity[$y] == "") {
                            $flag = 0;
                            // echo "<div class='alert alert-danger'>Please select product and quantity.</div>";
                        }
                    }
                    if (count($product_id) != count(array_unique($product_id))) {
                        $flag = 0;
                        echo "<div class='alert alert-danger'>Duplicate.</div>";
                    }



                    if ($flag == 1) {
                        // insert query
                        $query = "INSERT INTO orders SET customer_username=:customer_username";
                        // echo $customer_username;

                        // prepare query for execution
                        $stmt = $con->prepare($query);

                        // bind the parameters
                        $stmt->bindParam(':customer_username', $customer_username);

                        // Execute the query
                        $stmt->execute();
                        //get order id
                        $id = $con->lastInsertID();
                        echo "<div class='alert alert-success'>Record was saved. The Order id is $id</div>";

                        //count = 5 so use <
                        //<=4 plus 0 = 5
                        //y<count = x<=4 it is link
                        //why for loop bec one will run one time
                        //for loop x or y is not affect
                        for ($y = 0; $y < count($product_id); $y++) {
                            //to get n know order id
                            $query = "INSERT INTO orderdetails SET order_id=:order_id, product_id=:product_id, quantity=:quantity";

                            // prepare query for execution
                            //here preppare di so down atmt execute can same name
                            $stmt = $con->prepare($query);

                            // bind the parameters
                            /*$stmt->bindParam(':quantity', $quantity);*/
                            //product id is array bec set 18... see submit how much
                            $stmt->bindParam(':product_id', $product_id[$y]);
                            $stmt->bindParam(':order_id', $id);
                            $stmt->bindParam(':quantity', $quantity[$y]);

                            // echo $product_id($x) . "<br>";
                            // echo $quantity($x) . "<br>";

                            $stmt->execute();
                        }
                    } else {
                        //if flag = 0 will echo this
                        echo "<div class='alert alert-danger'>Please fill in information.</div>";
                    }
                }

                echo "<tr>";
                echo "<td>Customer ID</td>";
                echo "<td>";
                $query = "SELECT username, first_name, last_name FROM customers ORDER BY username DESC";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $num = $stmt->rowCount();

                if ($num > 0) {

                    echo "<select class='form-select' aria-lable='Default select example' name='customer_username'>";
                    echo "<option value=''>Please select customer</option>";


                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<option value=$username>$first_name $last_name";
                        echo "</option>";
                    }
                    echo "</select>";
                }
                echo "</td>";
                echo "</tr>";

                ?>

                <tr class='productQuantity'>
                    <td>
                        <div class="row">
                            <div class="col">
                                <?php
                                $productquery = "SELECT id, name FROM products ORDER BY id DESC";
                                $productstmt = $con->prepare($productquery);
                                $productstmt->execute();
                                $productnum = $productstmt->rowCount();

                                //$stmt->bindParam(':quantity', $product_id[$orderQuantityA]);
                                if ($productnum > 0) {

                                    echo "<select class= 'form-select' aria-label='Default select example' name='product_id[]'>";
                                    echo "<option value='A'>Please select products</option>";
                                    while ($row = $productstmt->fetch(PDO::FETCH_ASSOC)) {
                                        extract($row);
                                        echo "<option value=$id>$name";
                                        echo "</option>";
                                    }
                                    echo "</select>";
                                }

                                ?>

                            </div>
                    </td>

                    <td>
                        <div class="col">
                            <select class='form-select' aria-label='Default select example' name='quantity[]'>
                                <option value='A'>Please select quantity</option>
                                <option value='1'>1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>

            <td><input type='submit' value='Submit' class='btn btn-primary' /></td>

            <div class="d-flex justify-content-center flex-column flex-lg-row">
                <div class="d-flex justify-content-center">
                    <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                    <button type="button" class="del_last btn mb-3 mx-2">Delete Last Product</button>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
            <script>
                document.addEventListener('click', function(event) {
                    if (event.target.matches('.add_one')) {
                        var element = document.querySelector('.productQuantity');
                        var clone = element.cloneNode(true);
                        element.after(clone);
                    }
                    if (event.target.matches('.del_last')) {
                        var total = document.querySelectorAll('.productQuantity').length;
                        if (total > 1) {
                            var element = document.querySelector('.productQuantity');
                            element.remove(element);
                        }
                    }
                }, false);

                function deleteMe(row) {
                    var table = document.getElementById('order_table')
                    var allrows = table.getElementsByTagName('tr');
                    if (allrows.length == 1) {
                        alert("You are not allowed to delete.");
                    } else {
                        if (confirm("Confirm to delete?")) {
                            row.parentNode.parentNode.remove();
                        }
                    }
                }
            </script>

        </form>
    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>