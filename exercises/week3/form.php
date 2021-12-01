<!DOCTYPE html>
<html>

<head>
    <title>Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

<?php
if ($_GET) {
    echo $_GET["fname"];
    echo "<br>";
    echo $_GET["lname"];
    echo "<br>";
    echo $_GET["hobby"];
}
?>

    <!--<form action="action.php" method="GET">
        <label for="fname">First name:</label><br>
        <input type="text" id="fname" name="fname"><br>
        <label for="lname">Last name:</label><br>
        <input type="text" id="lname" name="lname"><br><br>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <label for="hobby">Hobby:</label>

        <select name="hobby" id="hobby">
            <option value="Reading">Reading</option>
            <option value="Gaming">Gaming</option>
            <option value="Fishing">Fishing</option>
        </select>
    <input type="submit" value="Submit">-->

    <div class="container">

        <h2>HTML Forms</h2>

        <form action="action.php">

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">First Name</span>
                <input type="text" class="form-control" name="fname" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Last Name</span>
                <input type="text" class="form-control" name="lname" aria-describedby="basic-addon1">
            </div>

            <select class="form-select" name="hobby" aria-label="Default select example">
                <option selected>Hobby</option>
                <option value="Reading">Reading</option>
                <option value="Gaming">Gaming</option>
                <option value="Fishing">Fishing</option>
            </select>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
        <p>If you click the "Submit" button, the form-data will be sent to a page called "action.php".</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>