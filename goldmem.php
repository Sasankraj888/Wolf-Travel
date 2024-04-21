<?php
session_start();
if(!empty($_SESSION['username'])){
    if (isset($_POST['submitgold'])) {
        $goldprice = $_POST['goldvalue'];
        $_SESSION['tempcartid'] = "goldmem";
        $_SESSION['totalcartprice'] = $goldprice;
        echo "<script>window.location.href='./payment2.php'</script>";
    }
}else{
    echo "<script>alert('Please Login first!')</script>";
    echo "<script>window.location.href='./index.php'</script>";
}
