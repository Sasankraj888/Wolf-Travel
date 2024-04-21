<?php
session_start();
$_SESSION['tempcartid'] = "";
$_SESSION['totalcartprice'] = "";
if (isset($_POST['buy1'])) {
  $_SESSION['tempcartid'] = $_POST['packageidh'];
  echo "<script>window.location.href='./payment2.php'</script>";
}
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
  <script src="./s1.js"></script>
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
      <a href="./orders.php">Orders</a>
      <a href="">Cart</a>
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
      <span>C</span>
      <span>A</span>
      <span>R</span>
      <span>T</span>
    </h1>
  </section>
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
  $cartname = "Cart";
  $sql = "SELECT * FROM cart WHERE UserId='$tempId' AND TransactionId='$cartname'";
  $resultt = $conn->query($sql);
  if ($resultt->num_rows > 0) {
    $curdate = returndate();
    echo '<div class="main">';
    echo '<div class="mainin">';
    while ($row = $resultt->fetch_assoc()) {
      if ($row['Activity'] === "Active" && strlen($row["ProductId"]) == 5) {
        $startdate = $row['StartDate'];
        $cartid = $row['CartId'];
        $selected_datetime = new DateTime($startdate);
        $interval = $selected_datetime->diff($curdate);
        $daysdifference = $interval->format('%a');
        if (($daysdifference >= 0) && ($selected_datetime > $curdate)) {
          echo '<div class="formcontainer">';
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
          echo '<div id="pricetagg">';
          echo '<div class="tag1">Price:</div>';
          echo '<span class="tag1" id="pricetaglabel">&#8377;&nbsp;</span>';
          echo '<span class="tag2"><b class="formlabels" id="pricetag">' . $row['TotalPrice'] . '/-' . '</b></span>';
          echo '</div>';
          echo "<form method='post' action='cartremove.php' id='myForm' class='removeform'>";
          echo '<div style="margin-bottom:3vh;">';
          echo "<input type='hidden' value='$cartid' name='packageidh' required />";
          echo '</div>';
          echo '<div class="detailsform" style="padding:0.6vh 0vw">';
          echo '<button class="formbtn" id="fbtn2" name="rem1">REMOVE</button>';
          echo '</div>';
          echo '</form>';
          echo "<form method='post' id='myForm' class='booknowform'>";
          // echo "<div class='booknowform'>";
          echo '<div class="detailsform" style="padding:1.5vh 0vw";margin-top:10vh;>';
          echo "<input type='hidden' value='$cartid' name='packageidh' required />";
          echo "<button class='formbtn' id='fbtn1' name='buy1'>BOOK NOW</button>";
          echo '</div>';
          echo "</form>";
          // echo '</div>';
          echo '</div>';
          echo '</div>';
        } else {
          $tempstatus = 'Inactive';
          $sql2 = "UPDATE cart SET Activity='$tempstatus' WHERE CartId='$cartid'";
          $conn->query($sql2);
        }
      }
      if ($row['Activity'] === "Active" && strlen($row["ProductId"]) == 9) {
        $startdate = $row['StartDate'];
        $cartid = $row['CartId'];
        $selected_datetime = new DateTime($startdate);
        $interval = $selected_datetime->diff($curdate);
        $daysdifference = $interval->format('%a');
        if (($daysdifference >= 0) && ($selected_datetime > $curdate)) {
          echo '<div class="formcontainer">';
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
          echo "<form method='post' action='cartremove.php' id='myForm' class='removeform'  style='margin-top:2vh;'>";
          echo '<div style="margin-bottom:3vh;">';
          echo "<input type='hidden' value='$cartid' name='packageidh' required />";
          echo '</div>';
          echo '<div class="detailsform" style="padding:0.6vh 0vw">';
          echo '<button class="formbtn" id="fbtn2" name="rem1">REMOVE</button>';
          echo '</div>';
          echo '</form>';
          echo "<form method='post' id='myForm' class='booknowform'>";
          // echo "<div class='booknowform'>";
          echo '<div class="detailsform" style="padding:1.5vh 0vw";margin-top:10vh;>';
          echo "<input type='hidden' value='$cartid' name='packageidh' required />";
          echo "<button class='formbtn' id='fbtn1' name='buy1'>BOOK NOW</button>";
          echo '</div>';
          echo "</form>";
          // echo '</div>';
          echo '</div>';
          echo '</div>';
        } else {
          $tempstatus = 'Inactive';
          $sql2 = "UPDATE cart SET Activity='$tempstatus' WHERE CartId='$cartid'";
          $conn->query($sql2);
        }
      }
    }
    echo '</div>';
    echo '</div>';
  }
  $conn->close();
  ?>
  <?php
  if (isset($_POST['buy1'])) {
    $id = $_POST['packageidh'];
    // echo "<script>alert('$id');</script>";
    // echo "<script>window.location.href='payment.php';</script>";
    echo "<script>callbutton($id);</script>";
  }
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
  <script>
    // document.getElementById("myForm").addEventListener("submit", function(event) {
    //   event.preventDefault();
    //   if (confirm("Are you sure, you want to remove?")) {
    //     this.submit();
    //   } else {
    //     return false;
    //   }
    // });
    var remform = document.querySelectorAll(".removeform");
    remform.forEach(function(form) {
      form.addEventListener("submit", function(event) {
        event.preventDefault();
        if (confirm("Are you sure you want to remove?")) {
          this.submit();
        } else {
          return false;
        }
      });
    });
    // var bookform = document.querySelectorAll(".booknowform");
    // bookform.forEach(function(form) {
    //   form.addEventListener("submit", function(event) {
    //     event.preventDefault();
    //     if (confirm("Are you sure you want to book?")) {
    // this.submit();
    //     } else {
    //       return false;
    //     }
    //   });
    // });
    var m11 = document.getElementById("myModal2");
    var m22 = document.getElementById("m22");
    var m33 = document.getElementById("m33");

    function callbutton(packageid) {
      document.getElementById("packageid").value = packageid;
      m11.style.display = "block";
    }
    m22.addEventListener('click', function(event) {
      if (event.target == m33) {
        return;
      } else {
        m11.style.display = "none";
      }
    });

    function stopPropagation(event) {
      event.stopPropagation();
    }
    m33.addEventListener('click', function(event) {
      event.stopPropagation();
    })
  </script>
  <script src="./script.js"></script>
</body>

</html>