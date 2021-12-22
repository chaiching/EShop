<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php
        include 'session.php';
        include 'menu.php';
        include 'config/database.php';
        ?>
        <div class="page-header">
            <h1>Welcome</h1>

            <?php
            echo "<div class='text-center'>";
            echo "Today Date: ";
            echo date("M j, Y");
            echo "<br>";
            echo "Welcome";

            $id = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record user not found.');

            $query = "SELECT username, last_name, gender FROM customers WHERE username=?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $last_name = $row['last_name'];
            $gender = $row['gender'];

            if ($gender = 1) {
                echo " Ms ";
            } else {
                echo " Mr ";
            };
            echo $last_name;
            echo "</div"
            ?>

            <div class="container px-4">
                <div class="row gx-1">
                    <div class="col text-center border bg-light">
                        <p class="fw-bold text-uppercase">Total Order</p>
                        <?php
                        $query = "SELECT * FROM orders ORDER BY id DESC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();

                        if ($num > 0) {
                            echo $num;
                        }
                        ?>
                    </div>
                    <div class="col text-center border bg-light">
                        <p class="fw-bold text-uppercase">Total Price</p>
                        <?php
                        $totalpricequery = "SELECT orderdetails_id, order_id, product_id, quantity, products.id ,products.price as proprice, products.name as proname FROM orderdetails INNER JOIN products ON orderdetails.product_id = products.id";
                        $totalpricestmt = $con->prepare($totalpricequery);
                        $totalpricestmt->execute();
                        $row = $totalpricestmt->fetch(PDO::FETCH_ASSOC);
                        $totalamount = 0;
                        $proprice = $row['proprice'];
                        $quantity = $row['quantity'];
                        while ($row = $totalpricestmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            $total = ($proprice * $quantity);
                            $totalamount = $totalamount + $total;
                        }
                        echo $totalamount;
                        ?>
                    </div>
                    <div class="col text-center border bg-light">
                        <p class="fw-bold text-uppercase">Total Customer</p>
                        <?php
                        // select all data
                        $query = "SELECT username, email, first_name, last_name, gender, date_of_birth, register_date_time, account_status FROM customers ORDER BY username DESC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();

                        if ($num > 0) {
                            echo $num;
                        }
                        ?>
                    </div>
                </div>

                <div class="col text-center border bg-light">
                    <p class="fw-bold text-uppercase">Top Sell Product</p>

                    <?php
                    $query = "SELECT product_id, SUM(quantity) As MostSold FROM orderdetails GROUP BY product_id ORDER BY MostSold  DESC limit 3";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $num = $stmt->rowCount();
                    echo $num;

                    ?>
                </div>
                <?php
                //$id = isset($_GET['Username']) ? $_GET['Username'] : die('ERROR: Record Username not found.');
                try {

                    if (isset($myusername)) {
                        $query = "SELECT order_id, customer_username as oname, max(order_time) as MaxDate FROM orders WHERE customer_username = ?";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(1, $myusername);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        //var_dump($row);

                        // values to fill up our form
                        $order_id = $row['order_id'];
                        $oname = $row['oname'];
                        $order_time = $row['MaxDate'];
                    }

                    if (isset($order_time)) {
                        $query = "SELECT order_id, orderdetails.order_id as oname, orderdetails_id, orders.id, product_id, quantity, max(order_time) as MaxDate, products.price as pprice, products.id as pid, products.name as pname FROM orderdetails INNER JOIN products ON orderdetails.product_id = products.id INNER JOIN orders ON orderdetails.order_id = orders.id WHERE orderdetails.order_id = ?";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(1, $order_time);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        var_dump($row);

                        // values to fill up our form
                        $order_id = $row['order_id'];
                        $oname = $row['oname'];
                        $order_time = $row['MaxDate'];
                        $pprice = $row['pprice'];
                        $quantity = $row['quantity'];
                        $totalamount = 0;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            $total = ($pprice * $quantity);
                        }

                        $totalamount = $totalamount + $total;
                    }

                ?>
                    <div class="container px-4">
                        <div class="row gx-5">
                            <div class="col border bg-light">
                                <div class="p-3">
                                    <h3>Lastest Order</h3>
                                    <div class='col-5'>Order ID : </td>
                                        <td class='col-6'><?php echo $order_id ?>
                                    </div>
                                    <div class='col-5'>Customer Name : </td>
                                        <td class='col-6'><?php echo $oname ?>
                                    </div>
                                    <div class='col-5'>Total Amount : </td>
                                        <td class='col-6'><?php echo $totalamount ?>
                                    </div>
                                    <div class='col-5'>Order Date : </td>
                                        <td class='col-6'><?php echo $order_time ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }
                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>