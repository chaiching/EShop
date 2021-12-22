<?php
// include database connection
include 'config/database.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $ordid=isset($_GET['orderdetails_id']) ? $_GET['orderdetails_id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE FROM orderdetails_id WHERE orderdetails_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $ordid);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: index_orderdetails.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
