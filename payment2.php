<?php
session_start();

use Twilio\Rest\Client;

require_once './twilio-php-main/src/Twilio/autoload.php';


$sucss = $ourotp = $bkbalance = $bkcvv = $bkexpiry = $bkname = $bkphone = $bkcard = "";
$tempcartid = $_SESSION['tempcartid'];
if (isset($_POST['query'])) {
  $bkcard = $_POST['query'];
  $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $id_check_query = "SELECT * FROM validations2 WHERE CardNum='$bkcard'";
  $res = mysqli_query($connect, $id_check_query);
  $resch = mysqli_num_rows($res);
  if ($resch > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
      $bkname = $row['UserName'];
      $bkphone = $row['Phone'];
      $bkcvv = $row['Cvv'];
      $bkbalance = $row['Balance'];
      $bkexpiry = $row['ExpDate'];
    }
  }
  $_SESSION['cardnumber'] = $bkcard;
  echo json_encode(array("bkname" => $bkname, "bkphone" => $bkphone, "bkexpiry" => $bkexpiry, "bkcvv" => $bkcvv, "bkbalance" => $bkbalance));
  $connect->close();
  exit;
}
if (isset($_POST['query2'])) {
  $recipient = $_POST['query2'];
  $ourotp = random_int(100000, 999999);
  $_SESSION['otpnum'] = $ourotp;
  $accountSid = 'AC77bcb9ef7ed67a218d7c47b4b6f55655';
  $authToken = 'a3d6b7cd9b8b52acb3d4f901290c3cd5';
  $client = new Client($accountSid, $authToken);
  $message = "Your One Time Password(OTP) is $ourotp";
  $message = $client->messages
    ->create(
      "whatsapp:$recipient",
      array(
        "from" => "whatsapp:+14155238886",
        "body" => $message
      )
    );
  $sucss = "success";
  echo json_encode(array("sucss" => $sucss, "otpval" => $ourotp));
  exit;
}
if (isset($_POST['query3'])) {
  $inputotp = intval($_POST['query3']);
  $messagetransaction = "";
  if ($inputotp === $_SESSION['otpnum']) {
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $tempcard = $_SESSION['cardnumber'];
    $id_check_query = "SELECT * FROM validations2 WHERE CardNum='$tempcard'";
    $res = mysqli_query($connect, $id_check_query);
    $resch = mysqli_num_rows($res);
    if ($resch > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
        $bkaccnum = $row['AccId'];
        $bkname = $row['UserName'];
        $bkphone = $row['Phone'];
        $bkcvv = $row['Cvv'];
        $tempbal = intval($row['Balance']);
        $bkexpiry = $row['ExpDate'];
      }
    }
    // if($_SESSION['membership']=="Yes"){
    //   $tempg=intval($_SESSION['totalcartprice']);
    //   $tempg=$tempg-(($tempg/100)*10);
    //   $_SESSION['totalcartprice']=intval($tempg);
    // }
    $golddisprice = intval($_SESSION['totalcartprice']);
    if (intval($_SESSION['totalcartprice']) <= intval($tempbal)) {
      $tempbal = intval($tempbal) - intval($_SESSION['totalcartprice']);
      $tsid = "Purchased";
      $ctid = $_SESSION['tempcartid'];
      $query = "UPDATE validations2 SET Balance='$tempbal' WHERE CardNum='$tempcard'";
      $query_run = mysqli_query($connect, $query);

      $recipient = $_SESSION['phone'];
      $accountSid = 'AC77bcb9ef7ed67a218d7c47b4b6f55655';
      $authToken = 'a3d6b7cd9b8b52acb3d4f901290c3cd5';
      $messagename = $_SESSION['username'];
      $messageemail = $_SESSION['email'];
      $messageprice = $_SESSION['totalcartprice'];
      $message = "";
      if ($_SESSION['tempcartid'] == "goldmem") {
        $xid = $_SESSION['userid'];
        $query2 = "UPDATE validations SET Membership='Yes' WHERE UserId='$xid'";
        $query2_run = mysqli_query($connect, $query2);
        if ($query_run && $query2_run) {
          $messagetransaction = "Transaction Successful!";
          $client1 = new Client($accountSid, $authToken);
          $id1 = $_SESSION['userid'];
          $message = "Payment Successful!!\n\nPayment Details\n\nMembership Id: $id1\nPurchased by: $messagename\nEmail ID: $messageemail\nPurchase Details: Gold Membership\nValidity: Lifetime\nTotal Price: $messageprice\n\nThank you for choosing WOLF-TRAVEL!";
          $message = $client1->messages
            ->create(
              "whatsapp:$recipient",
              array(
                "from" => "whatsapp:+14155238886",
                "body" => $message
              )
            );
            $_SESSION['membership']="Yes";
        }
      } else {
        $query2 = "UPDATE cart SET TransactionId='$tsid' WHERE CartId='$ctid'";
        $query2_run = mysqli_query($connect, $query2);
        if ($query_run && $query2_run) {
          $messagetransaction = "Transaction Successful!";
          // message regarding purchase
          $messagedestination = $messagestartingdate = $messagedays = $messagepeople = "";
          $messagecartid = $_SESSION['tempcartid'];
          $id_check_query3 = "SELECT * FROM cart WHERE CartId='$messagecartid'";
          $res3 = mysqli_query($connect, $id_check_query3);
          $resch3 = mysqli_num_rows($res3);
          if ($resch3 > 0) {
            while ($row = mysqli_fetch_assoc($res3)) {
              $messageproductid = $row['ProductId'];
              $messagedestination = $row['PlaceName'];
              $messagehotelname = $row['HotelName'];
              $messagestartingdate = $row['StartDate'];
              $messagedays = $row['Days'];
              $messagepeople = $row['Persons'];
              $messagerooms = $row['RoomsBooked'];
              $messageroomtype = $row['RoomType'];
              $messageprice = $row['TotalPrice'];
            }
          }

          $client2 = new Client($accountSid, $authToken);
          $message = "";
          if (strlen($messageproductid) == 5) {
            $message = "Booking Successful!!\n\nBooking Details\n\nCart ID: $messagecartid\nBooked by: $messagename\nEmail ID: $messageemail\nDestination: $messagedestination\nStarting date: $messagestartingdate\nNo of days: $messagedays\nNo of People: $messagepeople\nTotal Price: $messageprice\n\nPlease make sure to carry an ID card along with you.\nThank you for choosing WOLF-TRAVEL!";
          } else if (strlen($messageproductid) == 9) {
            $message = "Booking Successful!!\n\nBooking Details\n\nCart ID: $messagecartid\nBooked by: $messagename\nEmail ID: $messageemail\nDestination: $messagedestination\nHotel Name: $messagehotelname\nCheck-in date: $messagestartingdate\nNo of days: $messagedays\nNo of People: $messagepeople\nRoom type: $messageroomtype\nTotal rooms: $messagerooms\nTotal Price: $messageprice\n\nPlease make sure to carry an ID card along with you.\nThank you for choosing WOLF-TRAVEL!";
          }
          $message = $client2->messages
            ->create(
              "whatsapp:$recipient",
              array(
                "from" => "whatsapp:+14155238886",
                "body" => $message
              )
            );
          $_SESSION['tempcartid'] = "";
        } else {
          $messagetransaction = "Transaction Failed!";
        }
      }
      // 
      // message regarding balance
      $recipient = $bkphone;
      $client3 = new Client($accountSid, $authToken);
      $message = "Purchase Successful!!\n\nYour card Details:\n\nAccount No: " . $bkaccnum . "\nCard holder name: " . $bkname . "\nCard No: " . $tempcard . "\nExp mm/yr: " . $bkexpiry . "\nCVV: " . $bkcvv . "\nBalance:" . $tempbal;
      $message = $client3->messages
        ->create(
          "whatsapp:$recipient",
          array(
            "from" => "whatsapp:+14155238886",
            "body" => $message
          )
        );
    } else {
      $messagetransaction = "Insufficient Balance!";
    }
    $connect->close();
  } else {
    $messagetransaction = "Invalid OTP!";
  }
  echo json_encode(array("bkmessage" => $messagetransaction));
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>payment</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
      <a href="./orders.php">Orders</a>
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
      <span>C</span>
      <span>A</span>
      <span>R</span>
      <span>T</span>
    </h1>
  </section>
  <br>
  <div id="myModal2" class="modal">
    <div id="m22" class="modalcont2">
      <div class="money" id="m33">
        <p class="headofpayment" align="middle">Payment Portal</p>
        <div class="money2">
          <div class="div1con">
            <?php
            echo "<table>";
            echo "<tr><th>Cart ID</th><th>Purchase Details</th><th>Price</th></tr>";
            if ($_SESSION['tempcartid'] != "goldmem") {
              $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
              $sql = "SELECT * FROM cart WHERE CartId='$tempcartid'";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  if ($_SESSION['membership'] == 'Yes') {
                    $_SESSION['totalcartprice'] = (intval($row["TotalPrice"]) - ((intval($row["TotalPrice"] / 100)) * 10));
                  } else {
                    $_SESSION['totalcartprice'] = intval($row["TotalPrice"]);
                  }
                  // $temptotcartprice=intval($_SESSION['totalcartprice']);
                  // $temptotcartprice = $temptotcartprice + intval($row["TotalPrice"]);
                  echo "<tr><td>" . $row["CartId"] . "</td><td>" . $row["PlaceName"] . "</td><td>" . "&#8377;" . $_SESSION['totalcartprice'] . "</td></tr>";
                }
                // echo "<tr><td colspan='2' class='total-price'>Total Price:</td><td class='total-price'>&#8377;" . $totalcartprice . "</td></tr>";
              }
              $conn->close();
            } else if ($_SESSION['tempcartid'] == "goldmem") {
              echo "<tr><td>" . 01 . "</td><td>" . "Gold Membership" . "</td><td>" . "&#8377;" . $_SESSION['totalcartprice'] . "</td></tr>";
            }

            echo "</table>";
            ?>
            <div class="totalamnt">
              <div class="tdiv1">
                <h1>Total Price Value:</h1>
              </div>
              <div class="tdiv2">
                <h1>&#8377;<?php echo $_SESSION['totalcartprice'] ?></h1>
              </div>
            </div>
          </div>
          <div class="div2con">
            <div class="div2details">
              <div class="divlabelsinputs">
                <!-- <label class="cardlabels" for="bkcard">Enter Card Number:</label> -->
                <input type="text" class="cardinputs" id="bkcard" placeholder="Enter Card Number" name="bkcard">
              </div>
              <div class="divlabelsinputs">
                <button class="cardbuttons" onclick="getDetails()">Submit</button>
              </div>
              <div class="divlabelsinputs" style="padding-top:1.5vh">
                <!-- <label class="cardlabels" for="bkname">Name:</label> -->
                <input type="text" class="cardinputs" id="bkname" placeholder="Name" name="bkname" disabled>
              </div>
              <div class="divlabelsinputs">
                <input type="text" class="cardinputs" id="bkexpiry" placeholder="Expiry Month and Year" name="bkexpiry" disabled>
              </div>
              <div class="divlabelsinputs" style="padding-bottom:2vh">
                <!-- <label class="cardlabels" for="bkcvv">CVV:</label> -->
                <input type="text" class="cardinputs" id="bkcvv" placeholder="CVV" name="bkcvv" disabled>
              </div>
              <div class="divlabelsinputs">
                <input type="hidden" class="cardinputs" id="bkphone" name="bkphone">
              </div>
              <div class="divlabelsinputs">
                <button class="cardbuttons" id="genotpbtn" onclick="generateOTP()" disabled>Generate OTP</button>
              </div>
              <div class="divlabelsinputs" style="padding-top:1.5vh">
                <input type="tel" maxlength="6" minlength="6" class="cardinputs" id="otpinput" name="otpinput" placeholder="Enter OTP" disabled required>
              </div>
              <div class="divlabelsinputs">
                <button class="cardbuttons" id="valotpbtn" onclick="validateOTP()" disabled>Purchase</button>
              </div>
            </div>
            <div class="div2details2">
              <img src="./images/qr.png" style="margin-top: 0vh;margin-bottom: 3vh;" width="200" alt="">
              <p>Scan and text "join past-before" to activate messages.</p>
              <p>or</p>
              <p>Send "join past-before" text to "+14155238886" to activate messages.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function getDetails() {
      // if (confirm('Are you sure, want to purchase?')) {
      var query = document.getElementById('bkcard').value;
      $.ajax({
        type: "POST",
        url: "payment2.php",
        data: {
          query: query
        },
        success: function(response) {
          var data = JSON.parse(response);
          document.getElementById('bkname').value = data.bkname;
          document.getElementById('bkexpiry').value = data.bkexpiry;
          document.getElementById('bkcvv').value = data.bkcvv;
          document.getElementById('bkphone').value = data.bkphone;
          if (data.bkname) {
            var btn = document.getElementById('genotpbtn');
            btn.disabled = false;
            document.getElementById('bkname').disabled = false;
            document.getElementById('bkexpiry').disabled = false;
            document.getElementById('bkcvv').disabled = false;
            document.getElementById('bkphone').disabled = false;
          } else {
            var btn = document.getElementById('genotpbtn');
            btn.disabled = true;
            document.getElementById('bkname').disabled = true;
            document.getElementById('bkexpiry').disabled = true;
            document.getElementById('bkcvv').disabled = true;
            document.getElementById('bkphone').disabled = true;
          }
        }
      });
      // }
    }

    function generateOTP() {
      var query2 = document.getElementById('bkphone').value;
      $.ajax({
        type: "POST",
        url: "payment2.php",
        data: {
          query2: query2
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.sucss) {
            var otpinput = document.getElementById('otpinput');
            otpinput.disabled = false;
            var btnval = document.getElementById('valotpbtn');
            btnval.disabled = false;
            var btn = document.getElementById('genotpbtn');
            btn.disabled = true;
            alert('Please check your Mobile phone for OTP!');
          } else {
            var otpinput = document.getElementById('otpinput');
            otpinput.disabled = true;
            var btnval = document.getElementById('valotpbtn');
            btnval.disabled = true;
            alert('Something wrong with your card!');
          }
        }
      });
    }

    function validateOTP() {
      var query3 = document.getElementById('otpinput').value;
      console.log("Query3:", query3);
      $.ajax({
        type: "POST",
        url: "payment2.php",
        data: {
          query3: query3
        },
        success: function(response) {
          console.log("Response:", response);
          var data = JSON.parse(response);
          console.log("Data:", data);
          console.log("Message:", data.bkmessage);
          if (confirm(data.bkmessage)) {
            window.location.href = './payment.php';
          } else {
            window.location.href = './payment.php';
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);
        }
      });
    }
  </script>
</body>

</html>