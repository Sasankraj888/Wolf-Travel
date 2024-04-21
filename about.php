<?php
session_start();

if(isset($_POST['send'])){
    $email=$_POST['emailsend'];
    $phone="+91".$_POST['phonesend'];
    $message=$_POST['messagesend'];
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $stmt = $connect->prepare("insert into reviews(UserEmail,UserPhone,UserMessage)values(?,?,?)");
    $stmt->bind_param("sss", $email, $phone,$message);
    $stmt->execute();
    echo "<script>alert('Message received!')</script>";
    echo '<script>window.location.href="index.php";</script>';
    $connect->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wolf-travel - About Us</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/about.css" />
    <link rel="stylesheet" href="./css/navbar.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700&display=swap');

        .navbar {
            --bs-navbar-padding-y: 0rem;
            display: block;
        }
    </style>
</head>

<body style="line-height: normal;">
    <header>
        <!-- <a href="./index.php" style="text-decoration: none;font-family: 'Nunito', sans-serif;" class="logo"><span>W</span>olf - <span>T</span>ravel</a> -->
        <?php
        if (!empty($_SESSION['membership'])) {
            $checkmem = $_SESSION['membership'];
        } else {
            $checkmem = "";
        }
        if ($checkmem == 'Yes') {
        ?>
            <a href="./index.php" class="logo" style="text-decoration: none;font-family: 'Nunito', sans-serif;color: gold;">
                <span>W</span>olf - <span>T</span>ravel
            </a>
        <?php
        } else {
        ?>
            <a href="./index.php" style="text-decoration: none;font-family: 'Nunito', sans-serif;" class="logo">
                <span>W</span>olf - <span>T</span>ravel
            </a>
        <?php
        }
        ?>
        <nav class="navbar">
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./index.php">Home</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./gallery.php">Gallery</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./packages.php">Packages</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./booking.php">Hotels</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./orders.php">Orders</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./payment.php">Cart</a>
            <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="">About</a>
            <?php if (empty($_SESSION['username'])) :
                // echo "<script>alert('Please Login first!')</script>";
                // echo "<script>window.location.href='./index.php'</script>"; 
            ?>
            <?php else : ?>
                <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" href="./profile.php">Profile</a>
                <a style="text-decoration: none;font-family: 'Nunito', sans-serif;" id="logoutanchor" href="logout.php">Logout</a>
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
    <section class="about" id="about">
        <h1 class="heading">
            <span>A</span>
            <span>b</span>
            <span>o</span>
            <span>u</span>
            <span>t</span>
            <span class="space"></span>
            <span>U</span>
            <span>s</span>
        </h1>
        <div class="content">
            <div class="items">
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5"></button>
                    </div>
                    <div class="carousel-inner" id="carousel-inner">
                        <div class="carousel-item active">
                            <img src="./images/sl-1.jpg" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>Kuang Si Falls, Laos</h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/sl-2.jpg" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>Mù Cang Chải, Vietnam</h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/sl-3.jpg" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>Lake Como, Italy</h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/pic-6.jpg" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>Maldives Island</h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/sl-4.png" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>The Eiffel Tower, Paris</h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="./images/sl-5.avif" class="d-block w-100" alt="..." style="height: 370px; width: 100%;object-fit:cover;">
                            <div class="carousel-caption block">
                                <h5>Mysore Palace, India</h5>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="item">
                    <div class="item-content">
                        <!-- <h1><strong>About Us</strong></h1> -->
                        <br>
                        <div class="desc">
                            <h2 class="title"><b>Welcome to Our Journey</b></h2>
                            <p>We are passionate travelers dedicated to exploring the world's wonders. Our mission is to
                                share inspiring stories and provide valuable insights for your next adventure.</p>
                            <p>Join us in discovering the beauty of diverse cultures, breathtaking landscapes, and
                                extraordinary experiences. Let's make every journey unforgettable!</p>
                        </div>
                    </div>
                    <div class="item-data">
                        <div class="col">
                            <h1>179</h1>
                            <span>Completed Trips</span>
                        </div>
                        <div class="col">
                            <h1>12</h1>
                            <span>Tour Guides</span>
                        </div>
                        <div class="col">
                            <h1>17</h1>
                            <span>Destinations</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <hr style="margin-top:10vh;">
    <section class="contact" id="contact">
        <div class="content">
            <div class="items">
                <div class="item" style="margin-top: 7vh;">
                    <div class="heading">
                        <h1 class="title"><strong>Contact our Travel webpage</strong> </h1><br>
                        <p class="subtitle">Have questions or want to get in touch? Fill out the form,
                            and we'll get back to you as soon as possible.</p>
                    </div>
                    <div class="contgrid">
                        <div class="contitem">
                            <h2>Call Us</h2>
                            <h4>+91 9550873370</h4>
                        </div>
                        <div class="contitem">
                            <h2>Mail Us</h2>
                            <h4>psasankraj888@gmail.com</h4>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <form action="" method="post">
                        <input type="email" class="input" name="emailsend" placeholder="Your Email" required>
                        <input type="text" class="input" name="phonesend" id="phonesend" placeholder="Your Phone" required>
                        <textarea rows="6" type="text" name="messagesend" class="input" placeholder="Your Message" required></textarea>
                        <div class="sendbtn">
                            <button class="btn-contact" name="send">Send a message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
        document.getElementById('phonesend').addEventListener('input', function(event) {
        let inputValue = event.target.value.replace(/\D/g, ''); // Remove non-numeric characters
        
        // Limit the length to 10 digits
        if (inputValue.length > 10) {
            inputValue = inputValue.slice(0, 10);
        }
        
        event.target.value = inputValue; // Update the input value
    });
    </script>
</body>

</html>