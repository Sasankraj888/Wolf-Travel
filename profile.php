<?php
session_start();

use Twilio\Rest\Client;

require_once './twilio-php-main/src/Twilio/autoload.php';

if (isset($_POST['otpgen'])) {
  $recipient = $_SESSION['phone'];
  $ourotp = random_int(100000, 999999);
  $_SESSION['otpnum2'] = $ourotp;
  $accountSid = 'AC77bcb9ef7ed67a218d7c47b4b6f55655';
  $authToken = 'a3d6b7cd9b8b52acb3d4f901290c3cd5';
  $client = new Client($accountSid, $authToken);
  $message = "Your One Time Password(OTP) for Password change is $ourotp";
  $message = $client->messages
    ->create(
      "whatsapp:$recipient",
      array(
        "from" => "whatsapp:+14155238886",
        "body" => $message
      )
    );
  $sucss = "success";
  echo json_encode(array("sucss" => $sucss));
  exit;
}

if(isset($_POST['otpinput'])){
  $input=$_POST['otpinput'];
  $sucss="";
  if($input==intval($_SESSION['otpnum2'])){
    $sucss = "success";
  }
  echo json_encode(array("sucss" => $sucss));
  exit;
}

if(isset($_POST['passw'])){
  $input=$_POST['passw'];
  $sucss="";
  $xid = $_SESSION['userid'];
  $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $query2 = "UPDATE validations SET UserPassword='$input' WHERE UserId='$xid'";
  $query2_run = mysqli_query($connect, $query2);
  if ($query2_run) {
    $sucss = "success";
  }
  echo json_encode(array("sucss" => $sucss));
  exit;
}

if (isset($_POST['a1']) && isset($_POST['a2']) && isset($_POST['a3'])) {
  $a1 = $_POST['a1'];
  $a2 = $_POST['a2'];
  $a3 = $_POST['a3'];
  $xid = $_SESSION['userid'];
  $message = "Something went wrong!";
  $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $query2 = "UPDATE validations SET UserName='$a1', Email='$a2', Phone='$a3' WHERE UserId='$xid'";
  $query2_run = mysqli_query($connect, $query2);
  if ($query2_run) {
    $message = "Profile Updated!";
    $_SESSION['username'] = $a1;
    $_SESSION['email'] = $a2;
    $_SESSION['phone'] = $a3;
  }
  echo json_encode(array("message" => $message));
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/profile.css">
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
      <a href="">Profile</a>
      <?php if (empty($_SESSION['username'])) :
        echo "<script>alert('Please Login first!')</script>";
        echo "<script>window.location.href='./index.php'</script>"; ?>
      <?php else : ?>
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


  <section id="myModal2" class="modal">
    <div id="m22" class="modalcont2">
      <div class="money" id="m33">
        <div class="form__group">
          <input type="tel" maxlength="6" minlength="6" onblur="validateOTP();" required name="otpinputbox" id="otpinputbox" placeholder="Enter OTP">
          <input type="text" style="margin-top: 2vh;" minlength="6" required name="passwordbox" id="passwordbox" placeholder="Enter New Password" disabled>
          <div class="moneybtn">
            <input type="submit" onclick="changePassword();" name="addtocart" class="moneybtn2" id="moneybtn2" value="Change Password" disabled>
          </div>
        </div>
      </div>
    </div>
  </section>


  <div class="container">
    <h1 class="title">PROFILE</h1>
    <div>
      <div class="grid">
        <div class="form-group">
          <label for="name">User Name:</label>
          <input type="text" id="name" value="<?php echo $_SESSION['username']; ?>" required disabled>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" value="<?php echo $_SESSION['email']; ?>" required disabled>
        </div>
        <div class="form-group">
          <label for="phone">Mobile:</label>
          <input type="tel" id="phone" minlength="10" maxlength="13" value="<?php echo $_SESSION['phone']; ?>" required disabled>
        </div>
        <div class="btncont">
          <div class="button-container">
            <button class="button1" onclick="profilebtn1()" name="editbtn" id="editbtn">Edit Profile</button>
            <button class="button2" onclick="profilebtn2()" name="changebtn" id="changebtn" style="display: none;">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    <div class="changepswrd">
      <a class="changepassbtn" id="changepassbtn" onclick="generateOTP();">Change Password?</a>
    </div>
  </div>

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
    var m11 = document.getElementById("myModal2");
    var m22 = document.getElementById("m22");
    var m33 = document.getElementById("m33");

    function generateOTP() {
      var otpgen = "generate";
      $.ajax({
        type: "POST",
        url: "profile.php",
        data: {
          otpgen: otpgen
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.sucss) {
            alert('Please check your Mobile phone for OTP!');
            m11.style.display = "block";
          } else {
            alert('Something went wrong!');
          }
        }
      });
    }

    function validateOTP() {
      var otpinput = document.getElementById('otpinputbox').value;
      $.ajax({
        type: "POST",
        url: "profile.php",
        data: {
          otpinput: otpinput
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.sucss) {
            document.getElementById('passwordbox').disabled = false;
            document.getElementById('moneybtn2').disabled = false;
          } else {
            document.getElementById('passwordbox').disabled = true;
            document.getElementById('moneybtn2').disabled = true;
          }
        }
      });
    }
    function changePassword(){
      var passw = document.getElementById('passwordbox').value;
      $.ajax({
        type: "POST",
        url: "profile.php",
        data: {
          passw: passw
        },
        success: function(response) {
          var data = JSON.parse(response);
          console.log(data);
          if (data.sucss) {
            alert('Password updated!');
            window.location.href="./profile.php";
          } else {
            alert('Password update failed!');
            window.location.href="./profile.php";
          }
        }
      });
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

    function profilebtn1() {
      document.getElementById('editbtn').style.display = 'none';
      document.getElementById('changebtn').style.display = 'block';
      document.getElementById('name').disabled = false;
      document.getElementById('email').disabled = false;
      document.getElementById('phone').disabled = false;
    }

    function profilebtn2() {
      document.getElementById('editbtn').style.display = 'block';
      document.getElementById('changebtn').style.display = 'none';
      document.getElementById('name').disabled = true;
      document.getElementById('email').disabled = true;
      document.getElementById('phone').disabled = true;

      var a1 = document.getElementById('name').value;
      var a2 = document.getElementById('email').value;
      var a3 = document.getElementById('phone').value;

      // console.log("Query3:", query3);
      if (a1 && a2 && a3) {
        $.ajax({
          type: "POST",
          url: "profile.php",
          data: {
            a1: a1,
            a2: a2,
            a3: a3
          },
          success: function(response) {
            var data = JSON.parse(response);
            console.log("Data:", data);
            console.log("Message:", data.message);
            if (confirm(data.message)) {
              window.location.href = './profile.php';
            } else {
              window.location.href = './profile.php';
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
          }
        });
      } else {
        alert("Invalid details!");
      }
    }
  </script>
</body>

</html>