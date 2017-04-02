<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script></head>
  <style>
 div#map_canvas{
          margin:auto;
          width:800px;
          height:550px;
          overflow:hidden;

      }body {
    background-color: #CCccFF ;
    no-repeat fix; background-size: 100%;
      font-family: "TH SarabunPSK";
      font-size: 20px;
}
.wrapper{
    margin-left: 10px;
    margin-right: 10px;
    min-width: 650px;
    background-color: white ;
}h1{
  font-family: "TH SarabunPSK";

}
  </style>
</head>
<body >
  <div class="container-fluid">
      <form class="form-horizontal" method="post">
<center><h1>การปักหมุด เพื่อส่ง latitude และ longtitude ไปยัง Line Notify</h1></center><br>


            <div id="map_canvas"></div>

            <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyB604ioym5bF296ScxXRyD7SzTCYU7uO-I" type="text/javascript"></script>
            <script type="text/javascript">
            navigator.geolocation.getCurrentPosition(
                function(position) {
                 var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                localStorage.setItem("lat",lat);
                localStorage.setItem("lon",lon);

                console.log(lat);
                console.log(lon);
                },
                function () {
                 alert('Error locating your device');
                },
                {enableHighAccuracy: true}
            );

            function initialize() {
              if (GBrowserIsCompatible()) {
                var lat = localStorage.getItem("lat");
                var lon = localStorage.getItem("lon");
                var map = new GMap2(document.getElementById("map_canvas"));
                var center = new GLatLng(lat,lon); // การกำหนดจุดเริ่มต้น
                map.setCenter(center, 13);  // เลข 13 คือค่า zoom  สามารถปรับตามต้องการ
                map.setUIToDefault();

                var marker = new GMarker(center, {draggable: true});
                map.addOverlay(marker);


                GEvent.addListener(marker, "dragend", function() {
                    var point = marker.getPoint();
                    map.panTo(point);

                    $("#lat_value").val(point.lat());
                    $("#lon_value").val(point.lng());
                    $("#zoom_value").val(map.getZoom());

                });

              }
            }
            </script>
            <script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
            <script type="text/javascript">
            $(function(){
                initialize();
                $(document.body).unload(function(){
                        GUnload();
                });
            });
            </script>
            <div id="showDD" style="margin:auto;padding-top:5px;width:600px;">

                <input type="hidden" name="lat_value" class="form-control" type="text" id="lat_value" value="0" />
                <input type="hidden" name="lon_value" class="form-control" type="text" id="lon_value" value="0" />
                <br>
            </div>


  </div>


  <div class="row content">

  <fieldset>

    <div class="form-group">
      <br>
      <label for="textArea" class="col-lg-2 control-label">ข้อความ</label>
      <div class="col-lg-10">
        <textarea class="form-control" rows="3" id="textArea"  name="textArea"></textarea>

      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <br>
        <button type="reset" class="btn btn-default">Cancel</button>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </div>
    </div>
  </fieldset>
</form>
<?php
if ($_POST) {

//Setting
$lineapi = "4fTTTUtJfpRLnv12eT75D7exrPclv2UV3YWj1iSare1";

$lat= "\n Latitude : ".$_POST['lat_value'];
$lon= "\n Lontitude : ".$_POST['lon_value'];
$mms =  trim($_POST['textArea'].$lat.$lon);



date_default_timezone_set("Asia/Bangkok");

$chOne = curl_init();
curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");

curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt( $chOne, CURLOPT_POST, 1);

curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$mms");

curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1);

$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$lineapi.'', );
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);

curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec( $chOne );

if(curl_error($chOne)) { echo 'error:' . curl_error($chOne); }
else { $result_ = json_decode($result, true);
  echo '<center>';
echo "Status message : ". $result_['message']; }
echo '</center>';

curl_close( $chOne );
}
?>
</div>
</div>
</body>
</html>
