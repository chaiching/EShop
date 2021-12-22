
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
            <h1>Read Orders</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // select all data
        $query = "SELECT orders.id as orid, customer_username, order_time FROM orders INNER JOIN customers ON customers.username = orders.customer_username ORDER BY orders.id DESC";

        $customers = "";

        // delete message prompt will be here
        if ($_POST) {
            $query = "SELECT orders.id as orid, customer_username, order_time, customers.username, orders.customer_username FROM orders INNER JOIN customers ON customers.username = orders.customer_username WHERE orders.customer_username = ? ORDER BY orders.id DESC";

            $customers = htmlspecialchars(strip_tags($_POST['customers']));

            if ($customers == "A") {
                $query = "SELECT orders.id as orid, customer_username, order_time, customers.username, orders.customer_username FROM orders INNER JOIN customers ON customers.username = orders.customer_username ORDER BY orders.id DESC";
            }
        }

        $stmt = $con->prepare($query);
        if ($_POST && $customers !== "A") {
            $stmt->bindParam(1, $customers);
        }
        $stmt->execute();
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='create_order.php' class='btn btn-primary m-b-1em'>Create Order</a>";
        ?>

        <?php
        $customerquery = "SELECT username FROM customers ORDER BY username DESC";
        $customerstmt = $con->prepare($customerquery);
        $customerstmt->execute();

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
            echo "<th>Customer Username</th>";
            echo "<th>Order Time</th>";
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
                echo "<td>{$orid}</td>";
                echo "<td>{$customer_username}</td>";
                echo "<td>{$order_time}</td>";
                echo "<td>";
                // read one record
                echo "<a href='read_order.php?id={$orid}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update_order.php?id={$orid}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a onclick='delete_user({$orid});'  class='btn btn-danger'>Delete</a>";
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
        function delete_user(order_id) {

            var answer = confirm('Are you sure?');
            //if answer == 1
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'delete_order.php?order_id=' + order_id;
            }
        }
    </script>
</body>

</html>