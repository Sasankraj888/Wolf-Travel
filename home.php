<?php

use Twilio\Rest\Client;

// require_once './twilio-php-main/src/Twilio/autoload.php';
require_once '../twilio-php-main/src/Twilio/autoload.php';

session_start();
$showcardnum = substr($_SESSION['bcardnum'], 0, 4) . " " . substr($_SESSION['bcardnum'], 4, 4) . " " . substr($_SESSION['bcardnum'], 8, 4) . " " . substr($_SESSION['bcardnum'], 12, 4);

if (isset($_POST['suun'])) {
  $accountSid = 'AC77bcb9ef7ed67a218d7c47b4b6f55655';
  $authToken = 'a3d6b7cd9b8b52acb3d4f901290c3cd5';
  $client = new Client($accountSid, $authToken);
  $recipient = $_SESSION['bphone'];
  $message = "Your card Details:\n\nAccount Id: " . $_SESSION['buserid'] . "\nCard holder name: " . $_SESSION['busername'] . "\nCard No: " . $showcardnum . "\nExp mm/yr: " . $_SESSION['bexpdate'] . "\nCVV: " . $_SESSION['bcvv'] . "\nBalance:" . $_SESSION['bbalance'];

  $message = $client->messages
    ->create(
      "whatsapp:$recipient",
      array(
        "from" => "whatsapp:+14155238886",
        "body" => $message
      )
    );
  echo "<script>alert('Message sent successful!  Check whatsapp for details.')</script>";
  echo "<script>window.location.href='home.php';</script>";
}


if (isset($_POST['moneybtn'])) {
  $depval = $_SESSION['bbalance'] + $_POST['moneydep'];
  $accid = $_SESSION['buserid'];
  $connection = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
  $query = "UPDATE `validations2` SET Balance='$depval' where AccId='$accid'";
  $query_run = mysqli_query($connection, $query);
  if ($query_run) {
    $_SESSION['bbalance'] = $depval;
    echo "<script> alert('Amount successfully deposited!');</script>";
    echo "<script> window.location.href='home.php'; </script> ";
  } else {
    echo "<script> alert('Deposit failed!');</script>";
    echo "<script> window.location.href='home.php'; </script> ";
  }
  $connection->close();
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BSite</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./home.css">
</head>

<body>
  <div id="main">
    <div class="main1">
      <ul>
        <li><a class="navlinks" href="">Home</a></li>
        <li>
          <p class="navlinks" onclick="buttoncall();">Profile</p>
        </li>
        <li>
          <?php if (empty($_SESSION['busername'])) :
            echo "<script>alert('Please Login first!')</script>";
            echo "<script>window.location.href='./index.php'</script>"; ?>
          <?php else : ?>
            <a id="logoutanchor" class="navlinks" href="logout.php">Logout</a>
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
        </li>
      </ul>
    </div>
    <div class="main2">
      <div class="btn1" id="myBtn">View Card</div>
      <div class="btn2" id="myBtn2">Add Money</div>
      <form action="" class="btn3" method="post">
        <input type="submit" name="suun" class="btn33" value="Send Details">
      </form>
    </div>
    <div class="gradient"></div>
    <div class="background-image"></div>
  </div>

  <section id="myModal" class="modal">
    <div class="promptcard">
      <h4 style="font-family: outfit;">Use Send details for CVV and Balance</h4>
    </div>
    <div id="m2" class="modalcont">
      <div class="card" id="m3">
        <div class="face front">
          <h3 class="debit">Debit Card</h3>
          <h3 class="bank">Assure Bank</h3>
          <img src="https://img.icons8.com/plasticine/2x/sim-card-chip.png" class="chip" alt="Chip">
          <h3 class="number"><?php echo $showcardnum ?></h3>
          <h5 class="valid"><span>Exp date:</span><span><?php echo $_SESSION['bexpdate'] ?></span></h5>
          <h5 class="cardHolder"><?php echo $_SESSION['busername'] ?></h5>
        </div>
      </div>
    </div>
  </section>

  <section id="myModal2" class="modal">
    <div id="m22" class="modalcont2">
      <div class="money" id="m33">
        <form method="post" action="" class="form__group field">
          <input type="tel" maxlength="8" class="form__field" placeholder="Money" name="moneydep" id='name' required />
          <label for="name" class="form__label">Money</label>
          <div class="moneybtn">
            <input type="submit" name="moneybtn" class="moneybtn2" value="Add Money">
          </div>
        </form>
      </div>
    </div>
  </section>






  <script>
    const menu = document.querySelector('#main')
    document.querySelectorAll('li').forEach((li, index) => {
      li.onmouseover = () => {
        menu.dataset.indexes = index;
      }
    })

    // modal 1
    var m1 = document.getElementById("myModal");
    var m2 = document.getElementById("m2");
    var m3 = document.getElementById("m3");
    var btn = document.getElementById("myBtn");

    btn.onclick = function() {
      m1.style.display = "block";
    }

    m2.addEventListener('click', function(event) {
      if (event.target == m3) {
        return;
      } else {
        m1.style.display = "none";
      }
    });

    function stopPropagation(event) {
      event.stopPropagation();
    }

    m3.addEventListener('click', function(event) {
      event.stopPropagation();
    });


    // modal2
    var m11 = document.getElementById("myModal2");
    var m22 = document.getElementById("m22");
    var m33 = document.getElementById("m33");
    var btn2 = document.getElementById("myBtn2");

    btn2.onclick = function() {
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


    // modal 2
    document.querySelector('.form__field').addEventListener('input', function(event) {
      this.value = this.value.replace(/\D/g, '');
    });



    function buttoncall() {
      alert('Use Send details option!');
      window.location.href = 'home.php';
    }
  </script>
</body>

</html>