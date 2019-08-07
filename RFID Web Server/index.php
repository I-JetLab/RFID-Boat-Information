<!DOCTYPE html>
<html>
  <head>
    <title>RFID Reader</title>
    <style>
      @font-face {
        font-family: 'AvenirNext-Regular';
        src: url('fonts/AvenirNextLTPro-Regular.woff') format('woff');
        font-weight: normal;
        font-style: normal;
      }

      @font-face {
        font-family: 'AvenirNext-UltLt';
        src: url('fonts/AvenirNextLTPro-UltLt.woff') format('woff');
        font-weight: normal;
        font-style: normal;
      }

      @font-face {
        font-family: 'AvenirNext-Bold';
        src: url('fonts/AvenirNextLTPro-Bold.woff') format('woff');
        font-weight: normal;
        font-style: normal;
      }

      @font-face {
        font-family: 'AvenirNext-Demi';
        src: url('fonts/AvenirNextLTPro-Demi.woff') format('woff');
        font-weight: normal;
        font-style: normal;
      }

      @font-face {
        font-family: 'AvenirNext-MediumIt';
        src: url('fonts/AvenirNextLTPro-MediumIt.woff') format('woff');
        font-weight: normal;
        font-style: normal;
      }
      * { box-sizing: border-box; }
      html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        font-family: 'AvenirNext-Regular', sans-serif;
        background-color: #F4F1DE;
        color: #3D405B;
      }
      .wrapper {
        width: 65%;
        margin: 20px auto;
        padding: 20px;
      }
      .logo {
        font-family: 'AvenirNext-Demi', sans-serif;
        font-size: 30px;
      }
      .main_bar, .button_section {
        margin: 20px 0;
      }
      .button_section {
        margin: 50px 0;
        text-align: center;
      }
      .button {
        background: #E07A5F;
        color: #66382C;
        border-radius: 15px;
        padding: 30px;
        margin: 0 auto;
        width: 65%;
        cursor: pointer;
        font-family: 'AvenirNext-Bold', sans-serif;
        text-transform: uppercase;
        box-shadow: 2px 2px #CC6F57;
        -webkit-box-shadow: #CC6F57 2px 2px;
        -moz-box-shadow: #CC6F57 2px 2px;
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE10+/Edge */
        user-select: none; /* Standard */
      }
      .button:hover {
        color: #7B4334;
      }
      .button:active {
        box-shadow: -2px -2px 1px #CC6F57;
        -webkit-box-shadow: #CC6F57 -2px -2px;
        -moz-box-shadow: #CC6F57 -2px -2px;
      }
      .loading {
        text-align: center;
        margin: 50px;
      }
      .cancel {
        font-family: 'AvenirNext-UltLt', sans-serif;
        text-align: center;
        cursor: pointer;
        display: none;
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE10+/Edge */
        user-select: none; /* Standard */
      }
      @media only screen and (max-width: 768px) {
        .wrapper {
          width: 80%;
          margin: 15px auto;
          padding: 15px;
        }
      }
    </style>
  </head>
  <body>

    <div class="wrapper">

      <div class="main_bar">
        <div class="logo">
          RFID Boat Information
        </div>
        <div class="description">
          This is a demonstration of the RFID reader and chips and their ability to read information.
        </div>
      </div>

      <div class="button_section">
        <div class="button">
          Click here to begin
        </div>
      </div>

      <div class="content">
      </div>

      <div class="cancel">
        cancel
      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
      var stopRequests=!1,getData={},content="";$(document).ready(function(){$(".button").on("click",function(){$(".cancel").show(),$(".button").slideUp(),$(".content").html('<div class="loading"><img src="ajax-loader.gif" /><p>Loading please wait</p></div>'),$(".content").is(":visible")||$(".content").show(),function t(){$.ajax({url:"http://159.89.237.82/rfid/plugin_check.php",type:"GET",success:function(t){$(".content").slideUp("slow"),$(".content").css("opacity",0),stopRequests=!0,setTimeout(function(){getData=JSON.parse(t),content='<div class="info"><p><strong>EPC:</strong> '+getData.epc+"</p><p><strong>Hull ID:</strong> "+getData.hid+"</p><p><strong>Boat Name:</strong> "+getData.boat_name+"</p><p><strong>Boat Owner:</strong> "+getData.boat_owner+"</p></div>",$(".content").html(content),setTimeout(function(){$(".content").css("opacity",1),$(".content").slideDown()},200)},300)},fail:function(t){},complete:function(){0==stopRequests&&setTimeout(t,5e3)}})}()}),$(".cancel").on("click",function(){$(".cancel").is(":visible")&&($(".cancel").hide(),$(".content").hide(),$(".button").slideDown(),stopRequests=!1)})});
    </script>
  </body>
</html>
