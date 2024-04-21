function loadScript(url, callback) {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = url;
  if (script.readyState) {
      script.onreadystatechange = function() {
          if (script.readyState === "loaded" || script.readyState === "complete") {
              script.onreadystatechange = null;
              callback();
          }
      };
  } else {
      script.onload = function() {
          callback();
      };
  }
  document.getElementsByTagName("head")[0].appendChild(script);
}

// Load jQuery from CDN https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js
loadScript("https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js", function() {
  // function goldmembership() {
  //     // Make AJAX request
  //     $.ajax({
  //         url: 'your_api_endpoint_here', // Replace 'your_api_endpoint_here' with your actual API endpoint
  //         method: 'GET', // Or 'POST', 'PUT', etc. depending on your API
  //         success: function(data) {
  //             // Handle successful response
  //             $('#output').html(data); // Update the output div with the received data
  //         },
  //         error: function(xhr, status, error) {
  //             // Handle errors
  //             console.error(xhr, status, error); // Log the error
  //             $('#output').html('Error occurred. Please try again.'); // Show error message
  //         }
  //     });
  // }
  function goldmembership() {
    console.log("vicky");
    var price = 200000;
    $.ajax({
      type: "POST",
      url: "goldmem.php",
      data: {
        price: price,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if(data.sucss=="success"){
          window.location.href="./payment2.php";
        }
      },
    });
  }

  $(document).ready(function() {
      $('#fetchDataBtn').click(function() {
          goldmembership();
      });
  });
});






