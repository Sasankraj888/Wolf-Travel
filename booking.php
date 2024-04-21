<?php
session_start();
function runtimedate()
{
    $curdate = "";
    $api_url = "https://worldtimeapi.org/api/ip";
    // $api_url = "https://www.timeapi.io/api/Time/current/zone?timezone=UTC";
    $data = @file_get_contents($api_url);
    if ($data !== false) {
        $result = json_decode($data, true);
        $current_date = $result['datetime'];
        $curdate = date("Y-m-d", strtotime($current_date));
    } else {
        echo "Failed to retrieve data from the API";
    }
    return $curdate;
}
if (isset($_POST["place"])) {
    $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $tempplace = $_POST["place"];
    $sql = "SELECT DISTINCT HotelName FROM hotels WHERE Place='$tempplace'";
    $result = $conn->query($sql);
    $hotels = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // echo '<option value="' . $row["HotelName"] . '">' . $row["HotelName"] . '</option>';
            $hotels[] = $row["HotelName"];
        }
    }
    $conn->close();
    echo json_encode(array("hotels" =>  $hotels));
    exit;
}
if (isset($_POST["hotel"])) {

    $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $temphotel = $_POST["hotel"];
    $sql = "SELECT * FROM hotels WHERE HotelName='$temphotel'";
    $result = $conn->query($sql);
    $countroom = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["RoomType"] == "Deluxe Room") {
                if ($countroom <= ($row['RoomBal'] * 4)) {
                    $countroom = $row['RoomBal'] * 4;
                }
            } else {
                if ($countroom <= ($row['RoomBal'] * 2)) {
                    $countroom = $row['RoomBal'] * 2;
                }
            }
        }
    }
    // $countroom = $countroom * 2;
    $conn->close();
    echo json_encode(array("count" =>  $countroom));
    exit;
}
if (isset($_POST["adults"]) && isset($_POST["hotelname"])) {

    $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $tempadults = $_POST["adults"];
    $temphotel = $_POST['hotelname'];
    $sql = "SELECT * FROM hotels WHERE HotelName='$temphotel'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $roomtypes = array();
        while ($row = $result->fetch_assoc()) {
            if ($row["RoomType"] == "Deluxe Room") {
                if ($tempadults <= (intval($row['RoomBal']) * 4)) {
                    $roomtypes[] = $row["RoomType"];
                }
            } else {
                if ($tempadults <= (intval($row['RoomBal']) * 2)) {
                    $roomtypes[] = $row["RoomType"];
                }
            }
        }
        echo json_encode(array("rooms" =>  $roomtypes));
    }
    $conn->close();
    exit;
}
if (isset($_POST["roomdata"]) && isset($_POST["hotelnamedata"]) && isset($_POST["adultsdata"])) {

    $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $tempadults = intval($_POST['adultsdata']);
    $temproom = $_POST["roomdata"];
    $temphotel = $_POST['hotelnamedata'];
    $tempprice = $totalrooms = 0;
    $totalavailrooms = 0;
    $temphotelid = "";
    $sql = "SELECT * FROM hotels WHERE HotelName='$temphotel' AND RoomType='$temproom'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tempprice = intval($row['Price']);
            $tempavailrooms = intval($row['RoomBal']);
            $temphotelid = $row['HotelId'];
        }
    }
    if ($temproom == "Deluxe Room" && $tempadults % 4 == 0) {
        $totalrooms = intval(intval($tempadults) / 4);
    } else if ($temproom == "Deluxe Room" && $tempadults % 4 != 0) {
        $totalrooms = intval((intval($tempadults) / 4) + 1);
    } else if ($temproom != "Deluxe Room" && $tempadults % 2 == 0) {
        $totalrooms = intval(intval($tempadults) / 2);
    } else if ($temproom != "Deluxe Room" && $tempadults % 2 != 0) {
        $totalrooms = intval((intval($tempadults) / 2) + 1);
    }
    $totalprice = $totalrooms * $tempprice;
    $dprice = $tempprice - (($tempprice / 100) * 25);
    $totalavailrooms = $tempavailrooms - $totalrooms;
    if ($tempadults > 5) {
        $totalprice = $totalprice - (($totalprice / 100) * 25);
    }
    $conn->close();
    echo json_encode(array("roomsbooked" =>  $totalrooms, "price" => $totalprice, "hotelid" => $temphotelid, "priceperroom" => $tempprice, "dpriceperroom" => $dprice));
    exit;
}
if (isset($_POST["checkOut"]) && isset($_POST["checkin"]) && isset($_POST["priceperh"])) {
    $startdate = $_POST['checkin'];
    $enddate = $_POST['checkOut'];
    $priceperh = $_POST['priceperh'];
    $selected_datetime = new DateTime($startdate);
    $end_datetime = new DateTime($enddate);
    $interval = $selected_datetime->diff($end_datetime);
    $daysdifference = $interval->format('%a');
    if ($daysdifference > 0 && $selected_datetime < $end_datetime) {
        $priceperh = $priceperh * $daysdifference;
        echo json_encode(array("price" =>  $priceperh));
    } else {
        echo json_encode(array("price" =>  0));
    }
    exit;
}
if (isset($_POST["fplacename"]) && isset($_POST["fcheckin"]) && isset($_POST["fcheckout"]) && isset($_POST["ftprice"]) && isset($_POST["fhotel"]) && isset($_POST["fadlts"]) && isset($_POST["froom"]) && isset($_POST["fhotelid"]) && isset($_POST["fpriceperroom"]) && isset($_POST["fdpriceperroom"]) && isset($_POST["froomsbooked"])) {
    $a = $_POST['fplacename'];
    $b = $_POST['fcheckin'];
    $c = $_POST['fcheckout'];
    $d = $_POST['ftprice'];
    $e = $_POST['fhotel'];
    $f = $_POST['fadlts'];
    $g = $_POST['froom'];
    $h = $_POST['fhotelid'];
    $i = $_POST['fpriceperroom'];
    $j = $_POST['fdpriceperroom'];
    $k = $_POST['froomsbooked'];
    $l = "Active";
    $m = "Cart";
    $message = "";
    $connect = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
    $tid = $_SESSION['userid'];
    $datetime1 = new DateTime($b);
    $datetime2 = new DateTime($c);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->format('%a');
    if (!empty($a) && !empty($b) && !empty($c) && !empty($d) && !empty($e) && !empty($f) && !empty($g) && !empty($h) && !empty($i) && !empty($j) && !empty($k) && !empty($l) && !empty($m) && !empty($tid) && !empty($days)) {
        $stmt = $connect->prepare("insert into cart(UserId,ProductId,PlaceName,HotelName,Price,DiscountPrice,Persons,RoomType,RoomsBooked,TotalPrice,Days,StartDate,Activity,TransactionId)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssiiisiiisss", $tid, $h, $a, $e, $i, $j, $f, $g, $k, $d, $days, $b, $l, $m);
        $stmt->execute();
        $stmt->close();
        $message = "Added to Cart!";
    }
    $connect->close();
    echo json_encode(array("message" =>  $message));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css/booking.css">
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
            <a href="">Hotels</a>
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
    <section class="book" id="book">
        <h1 class="heading">
            <span>H</span>
            <span>O</span>
            <span>T</span>
            <span>E</span>
            <span>L</span>
            <span>S</span>
        </h1>
        <div class="row">
            <div class="divcontent">
                <div class="divcontent1">
                    <select name="placename" id="placename" onchange="storeSelectedPlace()" required>
                        <option value="">Select Place</option>
                        <?php

                        $conn = new mysqli('127.0.0.1:3307', 'root', '', 'sas');
                        $sql = "SELECT DISTINCT Place FROM hotels";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row["Place"] . '">' . $row["Place"] . '</option>';
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                    <select name="hotelname" id="hotelname" onchange="storeSelectedHotel()" required>
                        <option value="">Select Hotel</option>
                    </select>
                    <select name="adultcount" id="adultcount" onchange="storeSelectedAdult()" required>
                        <option value="">Adult Count</option>
                    </select>
                    <select name="roomtype" id="roomtype" onchange="storeSelectedRoomtype()" required>
                        <option value="">Room Type</option>
                    </select>
                </div>
                <div class="divcontent1_2">
                    <img src="./images/bg.jpg" id="imagehotels" class="imagehotels" alt="HOTELS">
                    <input type="submit" name="addtocartbtn" id="addtocartbtn" onclick="addtocartbuttonforhotels()" value="Add to Cart">
                </div>
                <div class="divcontent2">
                    <input type="text" name="roomsbookedt" id="roomsbookedt" placeholder="No of Rooms" disabled>
                    <input type="hidden" name="hotelidh" id="hotelidh">
                    <input type="hidden" name="priceperroom" id="priceperroom">
                    <input type="hidden" name="dpriceperroom" id="dpriceperroom">
                    <input type="hidden" name="roomsbooked" id="roomsbooked">
                    <input type="text" onfocus="(this.type='date')" onblur="(this.type='text');datecheckin();" name="checkindate" id="checkindate" placeholder="Check-In Date" disabled required>
                    <input type="text" onfocus="(this.type='date')" onblur="(this.type='text');datecheckout();" name="checkoutdate" id="checkoutdate" placeholder="Check-Out Date" disabled required>
                    <input type="hidden" name="totalamounth" id="totalamounth">
                    <input type="hidden" name="totalamounthh" id="totalamounthh">
                    <input type="text" name="totalamount" id="totalamount" placeholder="Total Amount" style="margin-bottom: 3vh;" disabled required>
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
        function storeSelectedPlace() {
            var selectedPlace = document.getElementById("placename").value;
            $.ajax({
                type: "POST",
                url: "booking.php", // Replace with your PHP script
                data: {
                    place: selectedPlace
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var hotels = data.hotels;
                    var hotelSelect = document.getElementById("hotelname");
                    hotelSelect.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Select Hotel";
                    option.value = "";
                    hotelSelect.appendChild(option);
                    var adultCount = document.getElementById("adultcount");
                    adultCount.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Adult Count";
                    option.value = "";
                    adultCount.appendChild(option);
                    var roomSelect = document.getElementById("roomtype");
                    roomSelect.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Room Type";
                    option.value = "";
                    roomSelect.appendChild(option);
                    document.getElementById('totalamount').value = "";
                    document.getElementById('totalamounth').value = "";
                    document.getElementById('checkindate').value = "";
                    document.getElementById('checkoutdate').value = "";
                    document.getElementById('checkindate').disabled = true;
                    document.getElementById('checkoutdate').disabled = true;
                    document.getElementById('hotelidh').value = "";
                    document.getElementById('priceperroom').value = "";
                    document.getElementById('dpriceperroom').value = "";
                    document.getElementById('roomsbooked').value = "";
                    document.getElementById('roomsbookedt').value = "";
                    hotels.forEach(function(hotel) {
                        option = document.createElement("option");
                        option.text = hotel;
                        option.value = hotel;
                        hotelSelect.appendChild(option);
                    });
                }
            });
        }

        function storeSelectedHotel() {
            var selectedHotel = document.getElementById("hotelname").value;
            $.ajax({
                type: "POST",
                url: "booking.php",
                data: {
                    hotel: selectedHotel
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var adults = data.count;
                    var adultCount = document.getElementById("adultcount");
                    adultCount.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Adult Count";
                    option.value = "";
                    adultCount.appendChild(option);
                    var roomSelect = document.getElementById("roomtype");
                    roomSelect.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Room Type";
                    option.value = "";
                    roomSelect.appendChild(option);
                    document.getElementById('totalamount').value = "";
                    document.getElementById('totalamounth').value = "";
                    document.getElementById('checkindate').value = "";
                    document.getElementById('checkoutdate').value = "";
                    document.getElementById('checkindate').disabled = true;
                    document.getElementById('checkoutdate').disabled = true;
                    document.getElementById('hotelidh').value = "";
                    document.getElementById('priceperroom').value = "";
                    document.getElementById('dpriceperroom').value = "";
                    document.getElementById('roomsbooked').value = "";
                    document.getElementById('roomsbookedt').value = "";
                    for (var i = 0; i < data.count; i++) {
                        option = document.createElement("option");
                        option.text = i + 1;
                        option.value = i + 1;
                        adultCount.appendChild(option);
                    }
                }
            });
        }

        function storeSelectedAdult() {
            var selectedAdult = document.getElementById("adultcount").value;
            var hotelname = document.getElementById("hotelname").value;
            $.ajax({
                type: "POST",
                url: "booking.php",
                data: {
                    adults: selectedAdult,
                    hotelname: hotelname,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var roomSelect = document.getElementById("roomtype");
                    roomSelect.innerHTML = '';
                    var option = document.createElement("option");
                    option.text = "Room Type";
                    option.value = "";
                    roomSelect.appendChild(option);
                    document.getElementById('totalamount').value = "";
                    document.getElementById('totalamounth').value = "";
                    document.getElementById('checkindate').value = "";
                    document.getElementById('checkoutdate').value = "";
                    document.getElementById('checkindate').disabled = true;
                    document.getElementById('checkoutdate').disabled = true;
                    document.getElementById('hotelidh').value = "";
                    document.getElementById('priceperroom').value = "";
                    document.getElementById('dpriceperroom').value = "";
                    document.getElementById('roomsbooked').value = "";
                    document.getElementById('roomsbookedt').value = "";
                    data.rooms.forEach(function(room) {
                        option = document.createElement("option");
                        option.text = room;
                        option.value = room;
                        roomSelect.appendChild(option);
                    });
                }
            });
        }

        function storeSelectedRoomtype() {
            var selectedRoom = document.getElementById("roomtype").value;
            var hotelname = document.getElementById("hotelname").value;
            var selectedAdult = document.getElementById("adultcount").value;
            $.ajax({
                type: "POST",
                url: "booking.php",
                data: {
                    roomdata: selectedRoom,
                    hotelnamedata: hotelname,
                    adultsdata: selectedAdult,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    // alert(data.roomsavail);
                    document.getElementById('checkindate').value = "";
                    document.getElementById('checkoutdate').value = "";
                    document.getElementById('totalamount').value = "";
                    document.getElementById('totalamounth').value = "";
                    document.getElementById('hotelidh').value = "";
                    document.getElementById('priceperroom').value = "";
                    document.getElementById('dpriceperroom').value = "";
                    document.getElementById('roomsbooked').value = "";
                    document.getElementById('roomsbookedt').value = "";
                    if (data.price) {
                        document.getElementById('checkindate').disabled = false;
                        document.getElementById('checkoutdate').disabled = true;
                        // document.getElementById('checkoutdate').disabled=false;
                        document.getElementById('totalamounth').value = data.price;
                        document.getElementById('hotelidh').value = data.hotelid;
                        document.getElementById('priceperroom').value = data.priceperroom;
                        document.getElementById('dpriceperroom').value = data.dpriceperroom;
                        document.getElementById('roomsbooked').value = data.roomsbooked;
                        document.getElementById('roomsbookedt').value = data.roomsbooked + " (Rooms)";
                    } else {
                        document.getElementById('checkindate').disabled = true;
                        document.getElementById('checkoutdate').disabled = true;
                    }
                }
            });
        }

        function datecheckin() {
            var checkIn = document.getElementById("checkindate").value;
            const apiURL = "https://worldtimeapi.org/api/ip";
            fetch(apiURL)
                .then(response => response.json())
                .then(data => {
                    const currentDate = new Date(data.datetime);
                    const selectedDate = new Date(checkIn);
                    if (selectedDate > currentDate) {
                        document.getElementById('checkoutdate').disabled = false;
                    } else {
                        document.getElementById('checkoutdate').disabled = true;
                        alert("Select a valid Check-In date!");
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function datecheckout() {
            var checkin = document.getElementById("checkindate").value;
            var checkOut = document.getElementById("checkoutdate").value;
            var priceperh = document.getElementById("totalamounth").value;
            // var htype = document.getElementById("hotelname").value;
            // var adlts = document.getElementById("adultcount").value;
            $.ajax({
                type: "POST",
                url: "booking.php",
                data: {
                    checkin: checkin,
                    checkOut: checkOut,
                    priceperh: priceperh,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.price == 0) {
                        alert("Select a valid Check-Out date!");
                        document.getElementById('totalamount').value = "";
                        document.getElementById('totalamounthh').value = "";
                    } else {
                        document.getElementById('totalamount').value = data.price + "/-";
                        document.getElementById('totalamounthh').value = data.price;
                    }
                }
            });
        }

        function addtocartbuttonforhotels() {
            var fplacename = document.getElementById("placename").value;
            var fcheckin = document.getElementById("checkindate").value;
            var fcheckout = document.getElementById("checkoutdate").value;
            var ftprice = document.getElementById("totalamounthh").value;
            var fhotel = document.getElementById("hotelname").value;
            var fadlts = document.getElementById("adultcount").value;
            var froom = document.getElementById("roomtype").value;
            var fhotelid = document.getElementById('hotelidh').value;
            var fpriceperroom = document.getElementById('priceperroom').value;
            var fdpriceperroom = document.getElementById('dpriceperroom').value;
            var froomsbooked = document.getElementById('roomsbooked').value;
            console.log("fplacename:", fplacename);
            console.log("fcheckin:", fcheckin);
            console.log("fcheckout:", fcheckout);
            console.log("ftprice:", ftprice);
            console.log("fhotel:", fhotel);
            console.log("fadlts:", fadlts);
            console.log("froom:", froom);
            console.log("fhotelid:", fhotelid);
            console.log("fpriceperroom:", fpriceperroom);
            console.log("fdpriceperroom:", fdpriceperroom);
            console.log("froomsbooked:", froomsbooked);
            if (fplacename && fcheckin && fcheckout && ftprice && fhotel && fadlts && froom && fhotelid && fpriceperroom && fdpriceperroom && froomsbooked) {
                $.ajax({
                    type: "POST",
                    url: "booking.php",
                    data: {
                        fplacename: fplacename, //a
                        fcheckin: fcheckin, //b
                        fcheckout: fcheckout, //c
                        ftprice: ftprice, //d
                        fhotel: fhotel, //e
                        fadlts: fadlts, //f
                        froom: froom, //g
                        fhotelid: fhotelid, //h
                        fpriceperroom: fpriceperroom, //i
                        fdpriceperroom: fdpriceperroom, //j
                        froomsbooked: froomsbooked, //k
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.message) {
                            alert(data.message);
                            window.location.href = "./booking.php";
                        } else {
                            alert("Something went wrong!");
                        }
                    }
                });
            } else {
                alert("Please fill all the fields!");
            }
        }
    </script>
</body>

</html>