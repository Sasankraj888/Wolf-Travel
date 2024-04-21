<?php
session_start();
$packageId = "";
function calculatePrice($count, $a, $b)
{
    $cost = "";
    if ($count > 0 && $count <= 5) {
        $cost = $count * $a;
    } else if ($count > 5 && $count <= 10) {
        $cost = $count * $b;
    }
    return $cost;
}
if (isset($_POST['addtocart'])) {
    $error = "";
    $place = $price = $disPrice = $days = '';
    $packageId = $_POST['packageidh'];
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $id_check_query = "SELECT * FROM packages WHERE PackageId='$packageId'";
    $res = mysqli_query($connect, $id_check_query);
    $resch = mysqli_num_rows($res);
    if ($resch > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $place = $row['Place'];
            $price = $row['Price'];
            $disPrice = $row['DPrice'];
            $days = $row['Days'];
        }
    }
    $userId = $_SESSION['userid'];
    $persons = $_POST['noofpep'];
    $totalprice = calculatePrice($persons, $price, $disPrice);
    $activity = "Active";
    $transaction = "Cart";
    $startdate = $_POST['startdate'];
    $api_url = "https://worldtimeapi.org/api/ip";
    $data = @file_get_contents($api_url);
    if ($data !== false) {
        $result = json_decode($data, true);
        $current_date = $result['datetime'];
        $curdate = date("Y-m-d", strtotime($current_date));
    } else {
        echo "Failed to retrieve data from the API";
    }
    $selected_datetime = new DateTime($startdate);
    $current_datetime = new DateTime($curdate);
    $interval = $selected_datetime->diff($current_datetime);
    $daysdifference = $interval->format('%a');
    if ($daysdifference > 0 && $selected_datetime > $current_datetime) {
        $stmt = $connect->prepare("insert into cart(UserId,ProductId,PlaceName,Price,DiscountPrice,Persons,TotalPrice,Days,StartDate,Activity,TransactionId)values(?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssiiiiisss", $userId, $packageId, $place, $price, $disPrice, $persons, $totalprice, $days, $startdate, $activity, $transaction);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Package is Added to Cart!')</script>";
        echo "<script>window.location.href='packages.php';</script>";
    } else {
        echo "<script>alert('Select a future date!')</script>";
        echo "<script>window.location.href='packages.php';</script>";
    }
    $connect->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>package</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css/package.css">
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
            <a href="">Packages</a>
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
    <section id="myModal2" class="modal">
        <div id="m22" class="modalcont2">
            <div class="money" id="m33">
                <form method="post" action="" class="form__group field">
                    <div style="color: #d99800;font-weight: 800;font-size: 1.5rem;">
                        <label for="packageid">Package Id</label>
                        <input type="text" class="form__field" value="<?php echo $packageId; ?>" name="packageid" id='packageid' required disabled />
                        <input type="hidden" class="form__field" value="<?php echo $packageId; ?>" name="packageidh" id='packageidh' required />
                    </div>
                    <div style="margin-top:2vh;color: #d99800;font-weight: 800;font-size: 1.5rem;">
                        <label for="noofpep">No of People</label>
                        <input type="tel" maxlength="2" min="1" max="15" class="form__field" placeholder="No of People" name="noofpep" id='noofpep' required />
                    </div>
                    <div style="margin-top:2vh;color: #d99800;font-weight: 800;font-size: 1.5rem;">
                        <label for="startdate">Starting date</label>
                        <input type="date" class="form__field" value="" placeholder="Starting date" name="startdate" id='startdate' required />
                    </div>
                    <div class="moneybtn">
                        <input type="submit" name="addtocart" class="moneybtn2" value="Add To Cart">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="packages" id="packages">
        <h1 class="heading">
            <span>P</span>
            <span>a</span>
            <span>c</span>
            <span>k</span>
            <span>a</span>
            <span>g</span>
            <span>e</span>
            <span>s</span>
        </h1>
        <div class="box-container">
            <div class="box">
                <img src="./images/pac-1.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Araku Valley</h3>
                        <p>Embark on a breathtaking trip from Visakhapatnam to Araku Valley,
                            exploring its natural wonders, lush landscapes, and tribal culture.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;3 Days / 2 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 5,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('AV001')" name="packbtn1" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-2.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Vizag City</h3>
                        <p>Visakhapatnam, also known as Vizag, is a coastal city in the state of Andhra Pradesh, India.
                            Famous for its serene beaches.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div><i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;3 Days / 2 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div><i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 10,000 Per Head</div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('VC001')" name="packbtn2" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-3.jpeg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Bhavani Island</h3>
                        <p>Bhavani island is one of the largest islands on a river and is located over the Krishna river at
                            Vijayawada, Andhra Pradesh, India.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;2 Days / 1 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 5,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('BI001')" name="packbtn3" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-4.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Kondapalli Fort</h3>
                        <p>Kondapalli Fort is a marvellous 14th-century fort located in the village
                            of Kondapalli in Guntur district near Vijayawada, Andhra Pradesh, India. </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;2 Days / 1 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 6,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('KF001')" name="packbtn4" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-5.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Hinkar Thirtha</h3>
                        <p>One of the most renowned Jain temples, this structure houses the only Jain
                            Shrine in the area. Adorned with the Jain style of architecture. </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;2 Days / 1 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 6,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('HT001')" name="packbtn5" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-6.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Undavalli Caves</h3>
                        <p>A monolithic example of Indian rock-cut architecture, the Undavalli Caves are located in the city
                            of Guntur, Andhra Pradesh. </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;2 Days / 1 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 4,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('UC001')" name="packbtn6" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-7.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Tirupati</h3>
                        <p>Situated in the Chittoor district of Andhra Pradesh,
                            Tirupati is known for Lord Venkateshwara Temple, one of the most visited pilgrimage in
                            the world. </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;4 Days / 3 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 10,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('TI001')" name="packbtn7" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-8.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Anantapur</h3>
                        <p>Anantapuram reverberates with flashes of India's glorious history
                            and the true ethnic traditions and values of India. </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;3 Days / 2 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 15,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('AN001')" name="packbtn8" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-9.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Ananthagiri Hills</h3>
                        <p>Ananthagiri is a hill town of ancient caves, temples, medieval fort palaces that showcases the
                            history of the area.</p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;2 Days / 1 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 15,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('AH001')" name="packbtn9" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-1.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Araku Valley</h3>
                        <p>Embark on a breathtaking trip from Visakhapatnam to Araku Valley,
                            exploring its natural wonders, lush landscapes, and tribal culture.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;7 Days / 6 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 10,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('AV002')" name="packbtn10" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-10.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Ladakh</h3>
                        <p>Ladakh, in India, offers stark landscapes, Buddhist monasteries, and a unique blend of Tibetan culture amidst the Himalayas.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div> <i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;8 Days / 7 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div> <i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 70,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('LD001')" name="packbtn11" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-11.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Golden Triangle</h3>
                        <p>Golden Triangle Tour Itinerary comprising the three famous cities of India - Delhi, Agra and Jaipur having grandeur, glory & history.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div><i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;9 Days / 8 Nights</div>&emsp;&emsp;&emsp;&emsp;
                            <div><i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 70,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('GT001')" name="packbtn12" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-12.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Nepal</h3>
                        <p>Nepal, nestled in the Himalayas, boasts stunning mountain landscapes, rich cultural diversity, ancient temples, and the birthplace of Lord Buddha.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div><i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;6 Days / 5 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div><i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 60,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('NP001')" name="packbtn13" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-13.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>Kerala Backwaters</h3>
                        <p>Kerala backwaters offer serene waterways lined with lush greenery, traditional houseboats, and a tranquil escape amidst Kerala's natural beauty.</p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div><i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;9 Days / 8 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div><i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 70,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('KB001')" name="packbtn14" class="btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="./images/pac-14.jpg" alt="">
                <div class="content">
                    <div class="infopack">
                        <h3><i class="fas fa-map-marker-alt"></i>South India Temples</h3>
                        <p>The temples are renowned for their towering gopurams, intricate Dravidian architecture, vibrant rituals, and rich cultural heritage steeped in tradition.
                        </p>
                    </div>
                    <div class="daysncount">
                        <div class="daysncount2">
                            <div><i class="far fa-clock" style="text-transform: none;"></i>&nbsp;&nbsp;9 Days / 8 Night</div>&emsp;&emsp;&emsp;&emsp;
                            <div><i class="fas fa-users" style="text-transform: none;"></i>&nbsp;&nbsp;Up to 10 people</div>
                        </div>
                    </div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="price">
                        <div>₹ 80,000 Per Head </div>
                        <h6 style="margin-bottom: 10px;">(Price Varies by Group Size)</h6>
                    </div>
                    <div class="btndiv">
                        <button onclick="callbutton('ST001')" name="packbtn15" class="btn">Add to Cart</button>
                    </div>
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
        // var packageid="";
        var m11 = document.getElementById("myModal2");
        var m22 = document.getElementById("m22");
        var m33 = document.getElementById("m33");

        function callbutton(packageid) {
            document.getElementById("packageid").value = packageid;
            document.getElementById("packageidh").value = packageid;
            // packageid=id;
            m11.style.display = "block";
            // document.getElementById("myModal2")="block";
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
        document.querySelector('.form__field').addEventListener('input', function(event) {
            this.value = this.value.replace(/\D/g, '');
        });
        document.getElementById("noofpep").addEventListener("input", function() {
            var inputValue = parseInt(this.value);
            if (inputValue == 0) {
                this.value = 1;
            }
            if (inputValue > 10) {
                this.value = 10;
            }
        });
    </script>
</body>

</html>