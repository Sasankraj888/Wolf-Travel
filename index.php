<?php

function cardnumgen()
{
  $cardnum = random_int(1000000000000000, 9999999999999999);
  $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $user_check_query = "SELECT * FROM validations2 WHERE CardNum='$cardnum' LIMIT 1";
  $result = mysqli_query($connect, $user_check_query);
  if ($result->num_rows > 0) {
    cardnumgen();
    $connect->close();
  }
  $connect->close();
  return $cardnum;
}

function cvvnumgen()
{
  $cvv = random_int(100, 999);
  return $cvv;
}

function expdategen()
{
  $a = random_int(1, 12);
  $c = random_int(2025, 2030);
  $date = strval($a) . "/" . strval($c);
  return $date;
}

if (isset($_POST['signup'])) {
  $count = 1;
  if (empty($_POST['sname'])) {
    $count = 0;
  }
  if (!empty($_POST['semail'])) {
    $email = $_POST['semail'];
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $user_check_query = "SELECT * FROM validations2 WHERE Email='$email' LIMIT 1";
    $result = mysqli_query($connect, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($result->num_rows > 0) {
      echo '<script>alert("Email already exists!")</script>';
      $count = 0;
    }
    $connect->close();
  } else {
    $count = 0;
  }

  if (empty($_POST['sphone'])) {
    $count = 0;
  }

  if (empty($_POST['spassword'])) {
    $count = 0;
  }

  if ($count == 1) {
    $name = $_POST['sname'];
    $email = $_POST['semail'];
    $phone = "+91" . $_POST['sphone'];
    $password = $_POST['spassword'];
    $balance = 50000;
    $cvv = cvvnumgen();
    $expdate = expdategen();
    $cardnum = cardnumgen();
    // echo"<script>alert('$cardnum');</script>";
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    if ($connect->connect_error) {
      die('Connection Failed : ' . $connect->connect_error);
      $count = 1;
    } else {
      $stmt = $connect->prepare("insert into validations2(CardNum,UserName,Email,UserPassword,Balance,Phone,Cvv,ExpDate)values(?,?,?,?,?,?,?,?)");
      $stmt->bind_param("isssisis", $cardnum, $name, $email, $password, $balance, $phone, $cvv, $expdate);
      $stmt->execute();
      echo "<script>alert('Registration Successful!\\nPlease send \"join past-before\" text to \"+14155238886\"');</script>";

      $stmt->close();
      $connect->close();
      echo '<script>window.location.href="index.php";</script>';
    }
  }
}

session_start();
if (isset($_POST['signin'])) {
  // echo"<script>alert('HELLO');</script>";
  if (!empty($_POST['lemail'])) {
    $lemail = $_POST['lemail'];
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $email_check_query = "SELECT * FROM validations2 WHERE Email='$lemail' LIMIT 1";
    $result1 = mysqli_query($connect, $email_check_query);
    $user = mysqli_fetch_assoc($result1);
    if ($result1->num_rows > 0) {
      $lpassword = $_POST['lpassword'];
      $pass_check_query = "SELECT * FROM validations2 WHERE UserPassword='$lpassword' AND Email='$lemail' LIMIT 1";
      $result2 = mysqli_query($connect, $pass_check_query);
      if ($result2->num_rows > 0) {
        $eemail_check_query = "SELECT * FROM validations2 WHERE Email='$lemail'";
        $res = mysqli_query($connect, $eemail_check_query);
        $resch = mysqli_num_rows($res);
        if ($resch > 0) {
          while ($row = mysqli_fetch_assoc($res)) {
            $_SESSION['buserid'] = $row['AccId'];
            $_SESSION['bcardnum'] = $row['CardNum'];
            $_SESSION['busername'] = $row['UserName'];
            $_SESSION['bemail'] = $row['Email'];
            $_SESSION['bpassword'] = $row['UserPassword'];
            $_SESSION['bbalance'] = $row['Balance'];
            $_SESSION['bphone'] = $row['Phone'];
            $_SESSION['bcvv'] = $row['Cvv'];
            $_SESSION['bexpdate'] = $row['ExpDate'];
          }
        }
        $email = '';
        echo "<script>alert('Login Successful!')</script>";
        echo "<script>window.location.href='home.php';</script>";
      } else {
        echo "<script>alert('Invalid username/password!')</script>";
        echo "<script>window.location.href='logout.php';</script>";
      }
    } else {
      echo "<script>alert('Invalid username/password!')</script>";
      echo "<script>window.location.href='logout.php';</script>";
    }
  }
  echo "<script>console.log($phone)</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <title>Document</title>
  <link rel="stylesheet" href="./index.css">
</head>

<body>
  <!-- <button style="float: inline-end;z-index: 1;background: none;padding: 0;font-size: 3rem;border: none;">&times;</button> -->
  <div class="con">
    <div class="container" id="container">
      <div class="form-container sign-up-container">
        <form action="" method="post">
          <h2 style="color: rgb(255, 140, 0);font-size: 2rem;font-family: outfit;margin-bottom:1vh;">Assure Bank</h2>
          <h2>Create Account</h2>
          <span>Use your email for registration</span>
          <input type="text" placeholder="Name" name="sname" required />
          <input type="email" placeholder="Email" name="semail" required />
          <div class="phonediv" style="display: flex;">
            <span class="phonenum">+91</span>
            <input type="tel" placeholder="Phone" name="sphone" required />
          </div>
          <input type="password" placeholder="Password" name="spassword" required />
          <br>
          <button name="signup">Sign Up</button>
        </form>
      </div>
      <div class="form-container sign-in-container">
        <form action="#" style="padding-bottom: 50px;" method="post">
          <h2 style="color: rgb(255, 140, 0);font-size: 2rem;font-family: outfit;margin-bottom:1vh;">Assure Bank</h2>
          <h2>Sign in</h2>
          <input type="email" placeholder="Email" name="lemail" required />
          <input type="password" placeholder="Password" name="lpassword" required />
          <button style="margin-top: 3vh;" name="signin">Sign In</button>
        </form>
      </div>
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h1>Hello, Friend!</h1>
            <img src="./qr.png" style="margin-top: 3vh;" width="200" alt="">
            <p>Scan and text "join past-before" to activate messages.</p>
            <button class="ghost" id="signIn">Sign In</button>
          </div>
          <div class="overlay-panel overlay-right">
            <h1>Welcome Back!</h1>
            <img src="./qr.png" style="margin-top: 3vh;" width="200" alt="">
            <p>Scan and text "join past-before" to activate messages.</p>
            <button class="ghost" id="signUp">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
      container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
  </script>

</body>

</html>