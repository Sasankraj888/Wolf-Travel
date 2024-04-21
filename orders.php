<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>payment</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="./css/payment.css" />
  <link rel="stylesheet" href="./css/navbar.css" />
</head>

<body>
  <header>
    <?php
    if (!empty($_SESSION['membership'])) {
      $checkmem = $_SESSION['membership'];
    } else {
      $checkmem = "";
    }
    if ($checkmem == 'Yes') {
    ?>
      <a href="./index.php" class="logo" style="color: gold;">
        <span>W</span>olf - <span>T</span>ravel
      </a>
    <?php
    } else {
    ?>
      <a href="./index.php" class="logo">
        <span>W</span>olf - <span>T</span>ravel
      </a>
    <?php
    }
    ?>
    <nav class="navbar">
      <a href="./index.php">Home</a>
      <a href="./gallery.php">Gallery</a>
      <a href="./packages.php">Packages</a>
      <a href="./booking.php">Hotels</a>
      <a href="">Orders</a>
      <a href="./payment.php">Cart</a>
      <a href="./about.php">About</a>
      <?php if (empty($_SESSION['username'])) :
        echo "<script>alert('Please Login first!')</script>";
        echo "<script>window.location.href='./index.php'</script>"; ?>
      <?php else : ?>
        <a href="./profile.php">Profile</a>
        <a id="logoutanchor" href="logout.php">Logout</a>
        <script>
          var anchor = document.getElementById('logoutanchor');
          anchor.addEventListener('click', function(event) {
            if (confirm("Are you sure, Logout?")) {
              window.location.href = "logout.php";
            } else {
              event.preventDefault();
            }
          });
        </script>
      <?php endif; ?>
    </nav>
  </header>
  <section>
    <h1 class="heading">
      <span>O</span>
      <span>R</span>
      <span>D</span>
      <span>E</span>
      <span>R</span>
      <span>S</span>
    </h1>
  </section>
  <br>
  <br>
  <?php
  function returndate()
  {
    $cur = "";
    // $api_url = "https://www.timeapi.io/api/Time/current/zone?timezone=UTC";
    $api_url = "https://worldtimeapi.org/api/ip";
    $data = @file_get_contents($api_url);
    if ($data !== false) {
      $result = json_decode($data, true);
      $current_date = $result['datetime'];
      $cur = date("Y-m-d", strtotime($current_date));
    }
    $current_datetime = new DateTime($cur);
    return $current_datetime;
  }
  $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $tempId = $_SESSION['userid'];
  $transactionid = "Purchased";
  $sql = "SELECT * FROM cart WHERE UserId='$tempId' AND TransactionId='$transactionid' ORDER BY CartId DESC";
  $resultt = $conn->query($sql);
  if ($resultt->num_rows > 0) {
    $curdate = returndate();
    echo '<div class="main">';
    echo '<div class="mainin">';
    while ($row = $resultt->fetch_assoc()) {
      if ($row['Activity'] && strlen($row["ProductId"]) == 5) {
        $startdate = $row['StartDate'];
        $cartid = $row['CartId'];
        $selected_datetime = new DateTime($startdate);
        $interval = $selected_datetime->diff($curdate);
        $daysdifference = $interval->format('%a');
        if (($selected_datetime <= $curdate)) {
          $tempstatus = 'Inactive';
          $sql2 = "UPDATE cart SET Activity='$tempstatus' WHERE CartId='$cartid'";
          $conn->query($sql2);
        }
        // if (($daysdifference > 0) && ($selected_datetime > $curdate)) {
        echo '<div id="myForm" class="formcontainer">';
        echo '<div class="card1_0"></div>';
        // Card 1_1
        echo '<div class="card1_1">';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Booking Id:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['CartId'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Name:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $_SESSION['username'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Destination:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['PlaceName'] . '</b></div>';
        echo '</div>';
        echo '</div>';
        // Card 1_2
        echo '<div class="card1_2">';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Starting Date:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['StartDate'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">No of Days:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Days'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">No of People:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Persons'] . '</b></div>';
        echo '</div>';
        echo '</div>';
        // Card 1_3
        echo '<div class="card1_3">';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Price:</div>';
        echo '<span class="tag1" id="pricetaglabel">&#8377;&nbsp;</span>';
        echo '<span class="tag2"><b class="formlabels" id="pricetag">' . $row['TotalPrice'] . "/-" . '</b></span>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Order Status:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Activity'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Transaction Status:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['TransactionId'] . '</b></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        // }
      }
      if ($row['Activity'] && strlen($row["ProductId"]) == 9) {
        $startdate = $row['StartDate'];
        $cartid = $row['CartId'];
        $selected_datetime = new DateTime($startdate);
        $interval = $selected_datetime->diff($curdate);
        $daysdifference = $interval->format('%a');
        if (($selected_datetime <= $curdate)) {
          $tempstatus = 'Inactive';
          $sql2 = "UPDATE cart SET Activity='$tempstatus' WHERE CartId='$cartid'";
          $conn->query($sql2);
        }
        echo '<div id="myForm" class="formcontainer">';
        echo '<div class="card1_02"></div>';
        // Card 1_1
        echo '<div class="card1_1">';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Booking Id:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['CartId'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Name:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $_SESSION['username'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Destination:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['PlaceName'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Hotel Name:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['HotelName'] . '</b></div>';
        echo '</div>';
        echo '</div>';
        // Card 1_2
        echo '<div class="card1_2">';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Starting Date:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['StartDate'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">No of Days:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Days'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">No of People:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Persons'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Rooms:</div>';
        $tempstring = $row['RoomsBooked'] . " (" . $row['RoomType'] . ")";
        echo '<div class="tag2"><b class="formlabels">' . $tempstring . '</b></div>';
        echo '</div>';
        echo '</div>';
        // Card 1_3
        echo '<div class="card1_3">';
        echo '<div id="pricetagg">';
        echo '<div class="tag1">Price:</div>';
        echo '<span class="tag1" id="pricetaglabel">&#8377;&nbsp;</span>';
        echo '<span class="tag2"><b class="formlabels" id="pricetag">' . $row['TotalPrice'] . "/-" . '</b></span>';
        echo '<span class="tag1" id="pricetaglabel"></span>';
        echo '</div>';
        echo '<div class="detailsform" style="margin-top:2vh;">';
        echo '<div class="tag1">Order Status:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['Activity'] . '</b></div>';
        echo '</div>';
        echo '<div class="detailsform">';
        echo '<div class="tag1">Transaction Status:</div>';
        echo '<div class="tag2"><b class="formlabels">' . $row['TransactionId'] . '</b></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    }
  }
  echo '</div>';
  echo '</div>';
  $conn->close();
  ?>
  <?php
  if ($_SESSION['membership'] != 'Yes') {
  ?>
    <form method="POST" action="./goldmem.php" class="floating-ad2">
      <input type="hidden" name="goldvalue" id="goldvalue" value="200000">
      <button name="submitgold" id="submitgold" class="submitgold" title="10% Discount on all Purchases">
        <img class="submitgold2" src="./images/goldadv.png" alt="Advertisement" width="150">
      </button>
    </form>
  <?php
  }
  ?>
  <a href="./tp/index.php" target="_blank" class="floating-ad">
    <img src="./images/bankadv.png" alt="Advertisement" width="150">
  </a>
</body>

</html>