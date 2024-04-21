<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gallery</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <link rel="stylesheet" href="./css/gallery.css">
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
            <a href="">Gallery</a>
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
    <section class="gallery" id="gallery">
        <h1 class="heading">
            <span>G</span>
            <span>A</span>
            <span>L</span>
            <span>L</span>
            <span>E</span>
            <span>R</span>
            <span>Y</span>
        </h1>
        <div class="box-container">
            <div class="box">
                <img src="./images/gallery/gl-1.jpg" alt="">
                <div class="content">
                    <h3>Lawson'S Bay Beach</h3>
                </div>
            </div>
            <div class="box">
                <img src="./images/gallery/gl-2.jpg" alt="">
                <div class="content">
                    <h3>Tenneti Park</h3>
                </div>
            </div>
            <div class="box">
                <img src="./images/gallery/gl-3.jpg" alt="">
                <div class="content">
                    <h3>Kambalakonda Wildlife Sanctuary</h3>
                </div>
            </div>
            <div class="box">
                <img src="./images/gallery/gl-4.jpg" alt="">
                <div class="content">
                    <h3>Ross Hill Church</h3>
                </div>
            </div>
            <div class="box">
                <img src="./images/gallery/gl-5.jpg" alt="">
                <div class="content">
                    <h3>Katiki Waterfalls</h3>
                </div>
            </div>
            <div class="box">
                <img src="./images/gallery/gl-6.jpg" alt="">
                <div class="content">
                    <h3>Borra Caves</h3>
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
</body>

</html>