<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <?php include 'menu.php'; 
        // include database connection
        include 'config/database.php';
        ?>
        <div class="page-header">
            <h1>Create Product</h1>
        </div>
        <!-- html form to create product will be here -->

        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            try {
                // posted values
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $catname = htmlspecialchars(strip_tags($_POST['category_id']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promotionprice = htmlspecialchars(strip_tags($_POST['promotion_price']));
                $manufacturedate = date(strip_tags($_POST['manufacture_date']));
                $expireddate = date(strip_tags($_POST['expired_date']));

                echo "$catname";

                $flag = 1;
                $message = "";
                $nowdate = date("Y-m-d");

                if ($name == "" || $description == "" || $price == "" || $promotionprice == "" || $manufacturedate == "" || $expireddate == "") {
                    $flag = 0;
                    $message = "Please fill in the blank. ";
                }

                // show numeric
                if (!is_numeric($price) && !is_numeric($promotionprice)) {
                    $flag = 0;
                    $message = $message . 'Price just accept number. ';
                }

                if ($price < $promotionprice) {
                    $flag = 0;
                    $message = $message . 'Promotion price is more than the normal price. ';
                }

                if ($manufacturedate > $expireddate) {
                    $flag = 0;
                    $message = $message . 'Manufacture date is more than expiration date. ';
                }

                if ($manufacturedate > $nowdate) {
                    $flag = 0;
                    $message = $message . 'Manufacture date is error. ';
                }

                if ($flag == 1) {

                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, category_id=:category_id, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date";
                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':category_id', $catname);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotionprice);
                    // specify when this record was inserted to the database
                    $manufacturedate = date('Y-m-d', strtotime($_POST['manufacture_date']));
                    $stmt->bindParam(':manufacture_date', $manufacturedate);
                    $expireddate = date('Y-m-d', strtotime($_POST['expired_date']));
                    $stmt->bindParam(':expired_date', $expireddate);

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
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <?php
                        
                        $categoryquery = "SELECT category_id as cid, category_name as cname FROM category ORDER BY category_id DESC";
                        $categorystmt = $con->prepare($categoryquery);
                        $categorystmt->execute();

                        $numcategory = $categorystmt->rowCount();

                        //check if more than 0 record found
                        if ($numcategory > 0) {
                            echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'>";
                            echo "<select class='form-select' aria-lable='Default select example' name='category_id'>";
                            echo "<option value='A' name='A'>Select Category</option>";

                            while ($row = $categorystmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value='$cid'> $cname";
                                echo "</option>";
                            }
                            echo "</select>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='read_product.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>