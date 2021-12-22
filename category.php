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
        <?php include 'menu.php' ?>
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

                    // insert query
                    $query = "INSERT INTO orders SET customer_username=:customer_username";

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
                    echo "<option value='A'>Please select customer</option>";


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

                <?php
                for ($x = 0; $x <= 4; $x++) {
                ?>
                    <tr>
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
    </div>
    </tr>
<?php } ?>
</table>
<td colspan="2"><input type='submit' value='Submit' class='btn btn-primary' /></td>
</form>
</div> <!-- end .container -->

<!-- confirm delete record will be here -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>

<?php include 'session.php'; ?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Read One</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
</head>

<body>
    <div class="menu">
        <?php include 'header.php'; ?>
    </div>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $oid = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT orderdetail_id, order_id, product_id, quantity, products.id, products.name as proname, products.price as proprice FROM orderdetails INNER JOIN products ON products.id = orderdetails.product_id WHERE order_id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $oid);

            // execute our query
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $order_id = $row['order_id'];
            ?>
            <table class=' table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Order ID</td>
                    <td colespan="5"><?php echo htmlspecialchars($order_id, ENT_QUOTES); ?></td>
                </tr>
                <?php
                $totalamount = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                ?>

                    <tr>
                        <td>Product</td>
                        <td><?php echo htmlspecialchars($proname, ENT_QUOTES); ?></td>
                        <td>Quantity</td>
                        <td><?php echo htmlspecialchars($quantity, ENT_QUOTES); ?></td>
                        <td>Price</td>
                        <td><?php echo htmlspecialchars($proprice, ENT_QUOTES); ?></td>
                        <td>Total</td>
                        <td><?php 
                        $total = ($proprice * $quantity);
                        echo $total;
                        $totalamount = $totalamount+$total;
                        ?></td>
                    </tr>

                    <tr>
                    <td>Total</td>
                    <td colspan="5"><?php
                        echo number_format($totalamount, 2);
                        ?></td>
                </tr>
            <?php
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <tr>
                <td colspan="4">
                    <a href='order_index.php' class='btn btn-danger'>Back to read orders</a>
                </td>
            </tr>
            </table>
    </div> <!-- end.container -->
    <?php include 'footer.php'; ?>
</body>

</html>