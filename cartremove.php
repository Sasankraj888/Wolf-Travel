<?php
session_start();
$connection = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
$ans = "Inactive";
// $cartid = $_SESSION['tempcartid'];
$cartid = $_POST['packageidh'];
$query = "UPDATE `cart` SET Activity='$ans' WHERE CartId='$cartid'";
$query_run = mysqli_query($connection, $query);
if ($query_run) {
    echo "<script> alert('Successfully removed!');</script>";
    echo "<script> window.location.href='payment.php'; </script> ";
} else {
    echo "<script> alert('Remove unsuccessful!');</script>";
    echo "<script> window.location.href='payment.php'; </script> ";
}
$connection->close();
?>
