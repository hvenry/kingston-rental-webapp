<?php
// rentaldb connection
try {
    $connection = new PDO('mysql:host=localhost;dbname=rentaldb', "root", "");
} catch (PDOException $e) {
    print "Error!: ". $e->getMessage(). "<br/>";
    die();
}
?>