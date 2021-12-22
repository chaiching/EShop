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
            <h1>Read Products</h1>
        </div>

        <html>

        <body>
            <!-- <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label>Category:</label>
                <select class="form-select" name="category">
                    <option value="A">All</option>
                    <option value="1">Accessories</option>
                    <option value="2">Food</option>
                    <option value="3">Household</option>
                    <option value="4">Drink</option>
            </form>
            </select>
            <button class="btn btn-primary" name="filter">Filter</button> -->
        </body>

        </html>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // select all data
        $query = "SELECT products.id as proid, products.name, category.category_id, category.category_name as catname, products.category_id, description, price, promotion_price, manufacture_date, expired_date FROM products INNER JOIN category ON products.category_id = category.category_id ORDER BY products.id DESC";

        $category = "";

        // delete message prompt will be here
        if ($_POST) {
            $query = "SELECT products.id as proid, products.name, category.category_id, category.category_name as catname, products.category_id, description, price, promotion_price, manufacture_date, expired_date FROM products INNER JOIN category ON products.category_id = category.category_id WHERE products.category_id = ? ORDER BY products.id DESC";

            $category = htmlspecialchars(strip_tags($_POST['category']));

            if ($category == "A") {
                $query = "SELECT products.id as proid, products.name, category.category_id, category.category_name as catname, products.category_id, description, price, promotion_price, manufacture_date, expired_date FROM products INNER JOIN category ON products.category_id = category.category_id ORDER BY products.id DESC";
            }
        }

        $stmt = $con->prepare($query);
        if ($_POST && $category !== "A") {
            $stmt->bindParam(1, $category);
        }
        $stmt->execute();
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create Product</a>";
        ?>

        <?php
        $categoryquery = "SELECT category_id, category_name FROM category ORDER BY category_id DESC";
        $categorystmt = $con->prepare($categoryquery);
        $categorystmt->execute();

        $numcategory = $categorystmt->rowCount();

        //check if more than 0 record found
        if ($numcategory > 0) {
            echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'>";
            echo "<select class='form-select' aria-lable='Default select example' name='category'>";
            echo "<option value='A' name='A'>All</option>";

            while ($row = $categorystmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<option value='$category_id'";
                if ($category_id == $category) {
                    echo "selected";
                }
                echo ">";
                echo "{$category_name}";
                echo "</option>";
            }
            echo "</select>";

            echo "<input type='submit' value='Submit' class='btn btn-primary' />";
            echo "</form>";
        }

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        ?>

        <?php
        if ($num > 0) {
            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category</th>";
            echo "<th>Price</th>";
            echo "<th>Promotion Price</th>";
            echo "<th>Manufacture Date</th>";
            echo "<th>Expired Date</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$proid}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$catname}</td>";
                // echo "<td>" . "<div style='text-align:right'>$price</div>". "</td>";
                // echo "<td>" . "<div style='text-align:right'>$promotion_price</div>". "</td>";
                echo "<td>" . ($price = number_format($price, 2)) . "</td>";
                // echo "<td>" . "<div align='right'>" .$price. "</div>" . "</td>";
                echo "<td>" . ($promotion_price = number_format($promotion_price, 2)) . "</td>";
                echo "<td>{$manufacture_date}</td>";
                echo "<td>{$expired_date}</td>";
                echo "<td>";
                // read one record
                echo "<a href='read_product.php?id={$proid}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update_product.php?id={$proid}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a onclick='delete_user({$proid});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        }

        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }

        ?>


    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script type='text/javascript'>
        // confirm record deletion
        function delete_user(id) {

            var answer = confirm('Are you sure?');
            //if answer == 1
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'delete_product.php?id=' + id;
            }
        }
    </script>
</body>

</html>