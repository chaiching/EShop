<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
            <h1>Update Product</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, description, category_id, price, promotion_price, manufacture_date, expired_date FROM products WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $category_id = $row['category_id'];
            $price = $row['price'];
            $promotionprice = $row['promotion_price'];
            $manufacturedate = $row['manufacture_date'];
            $expireddate = $row['expired_date'];
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
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$description}</td>";
            echo "<td>{$category_id}</td>";
            echo "<td>{$price}</td>";
            echo "<td>{$promotionprice}</td>";
            echo "<td>{$manufacturedate}</td>";
            echo "<td>{$expireddate}</td>";
            echo "<td>";
            // read one record
            echo "<a href='read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
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
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $category_id = htmlspecialchars(strip_tags($_POST['category_id']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promotionprice = htmlspecialchars(strip_tags($_POST['promotion_price']));
                $manufacturedate = date(strip_tags($_POST['manufacture_date']));
                $expireddate = date(strip_tags($_POST['expired_date']));

                $flag = 1;
                $message = "";
                $nowdate = date("Y-m-d");

                if ($name == "" || $description == "" || $category_id == "" || $price == "" || $promotionprice == "" || $manufacturedate == "" || $expireddate == "") {
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

                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                if ($flag == 1) {

                    $query = "UPDATE products
                    SET name=:name, description=:description, category_id=:category_id, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE id = :id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':category_id', $category_id);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':promotion_price', $promotionprice);
                    $stmt->bindParam(':manufacture_date', $manufacturedate);
                    $stmt->bindParam(':expired_date', $expireddate);
                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>$message</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
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
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' value="<?php echo htmlspecialchars($promotionprice, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' value="<?php echo htmlspecialchars($manufacturedate, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' value="<?php echo htmlspecialchars($expireddate, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='index_product.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>