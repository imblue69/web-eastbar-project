<script>
    var data = [{
        name: "East-bar",
        path: [
            [
                18.801561402385794,
                98.96819860119159
            ],
            [
                18.801353831848665,
                98.96815568584819
            ],
            [
                18.80122052953138,
                98.96818787235652
            ],
            [
                18.801167076371488,
                98.96833898296494
            ],
            [
                18.801334153892075,
                98.96843262392437
            ],
            [
                18.80153156810168,
                98.96845810490952
            ]
        ]
    }]
</script>
<!-- <style type="text/css">
        body {
            overflow: hidden;
            font-family: sans-serif;
        }

        .page,
        .map {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
        }

        .active {
            background-color: #FFD300 !important;
            color: black !important;
        }

        .btnAdd:hover {
            color: #FFD300;
        }

        .btnAdd {
            background-image: url('https://developer.nostramap.com/developer/V2/images/pin-trans.png');
            width: 90px;
            background-color: #222222;
            background-position: 0 0;
            border: none;
            color: #FFFFFF;
            padding: 5px 9px 6px;
            cursor: pointer;
            background-image: linear-gradient(rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0) 100%);
        }

        #show {
            right: 20px;
            top: 20px;
            background-color: #FFFFFF;
            border: 1px solid #2D2F37;
            border-radius: 3px;
            padding: 15px;
            position: fixed;
            width: 371px;
            vertical-align: middle;
            font-size: 14px;
        }

        #labelPanel {
            border: solid 1px #FFD300;
            padding: 5px
        }

        .lblRow {
            margin-left: 5px;
            margin-top: 3px;
        }

        .loadingWidget {
            position: absolute;
            width: 100%;
            height: 100%;
            background: White url('https://developer.nostramap.com/developer/V2/images/loader.gif') no-repeat fixed center center;
            filter: alpha(opacity=60);
            opacity: 0.6;
            z-index: 10000;
            vertical-align: middle;
            top: 0px;
            left: 0px;
        }
    </style> -->
<script type="text/javascript">
    var initExtent, map, point, lat, lon;
    var lineLayer, polygonLayer, pointLayer, mp;
    var prePointLayer, prePolygonLayer;
    var currentType = "point";
    var points = [];
    var lstLabel = [];
    var isFirstLoad = true;

    nostra.onready = function() {
        initialize();
    };

    function initialize() {
        //start your code here
        var map = new nostra.maps.Map("map", {
            id: "mapTest",
            logo: true,
            scalebar: true,
            basemap: "streetmap",
            slider: true,
            level: 18,
            lat: 18.801363695481417,
            lon: 98.96824888040905,
            country: "TH"
        });
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("เบราว์เซอร์นี้ไม่รองรับการขอตำแหน่ง. (เพื่อทำการลงเวลางาน ผู้ใข้จำเป็นต้องใช้เบราว์เซอร์ที่รองรับการขอตำแหน่ง)");
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("ผู้ใช้ปฏิเสธคำขอตำแหน่ง. (เพื่อทำการลงเวลางาน ผู้ใข้จำเป็นต้องให้อนุญาตคำขอตำแหน่ง)");
                    break;

                case error.POSITION_UNAVAILABLE:
                    alert("ไม่มีข้อมูลตำแหน่ง.");
                    break;

                case error.TIMEOUT:
                    alert("คำขอรับตำแหน่งของผู้ใช้หมดเวลา.");
                    break;

                case error.UNKNOWN_ERROR:
                    alert("เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ.");
                    break;
            }
        }

        function showPosition(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            var pointLayer = new nostra.maps.layers.GraphicsLayer(map, {
                id: "pointLayer",
                mouseOver: true
            });
            map.addLayer(pointLayer);
            var pointMarker = new nostra.maps.symbols.Marker({
                url: "",
                width: 60,
                height: 60,
                attributes: {
                    POI_NAME: "Current Location"
                }
            });
            var g = pointLayer.addMarker(lat, lon, pointMarker);
        }

        polygonLayer = new nostra.maps.layers.GraphicsLayer(map, {
            id: "polygonLayer",
            mouseOver: false
        });
        map.addLayer(polygonLayer);

        for (var area in data) {
            var nostraLabel = new nostra.maps.symbols.Label({
                text: data[area].name,
                size: "18",
                position: "top",
                color: "#353535",
                halocolor: "#ffffff",
                haloSize: "1",
                xoffset: "0",
                yoffset: "0"
            });

            var polygon = new nostra.maps.symbols.Polygon({
                color: "#F4FF18",
                outline: "#FF0000",
                transparent: 0.4,
                label: nostraLabel
            });
            polygonLayer.addPolygon(data[area].path, polygon);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการเช็คพิกัด");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // ส่งค่าพิกัดไปยังเซิร์ฟเวอร์ด้วย AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "worktime/check_location.php?lat=" + latitude + "&long=" + longitude, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                }
            };
            xhr.send();
        }
    }
</script>

<div id="map"></div>
<!-- <button onclick="getLocation()">เช็คพิกัด</button> -->