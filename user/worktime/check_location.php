<?php
session_start();

// รับค่าพิกัดจาก GET request
$lat = $_GET['lat'];
$long = $_GET['long'];
// $lat = 18.801363695481417;
// $long = 98.96824888040905;

// ข้อมูล polygon ของ East-bar
$data = [
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
    ];
function pointInPolygon($point, $polygon)
{
    $x = $point[0];
    $y = $point[1];
    $inside = false;

    for ($i = 0, $j = count($polygon) - 1; $i < count($polygon); $j = $i++) {
        $xi = $polygon[$i][0];
        $yi = $polygon[$i][1];
        $xj = $polygon[$j][0];
        $yj = $polygon[$j][1];

        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

        if ($intersect) {
            $inside = !$inside;
        }
    }

    return $inside;
}

// ตรวจสอบพิกัด
if (pointInPolygon([$lat, $long], $data)) {
    $_SESSION['text'] = "คุณอยู่ในพื้นที่แล้ว";
    $_SESSION['location'] = "1";
    header("Refresh:0");
    exit();
} else {
    $_SESSION['text'] = "คุณไม่ได้อยู่ในพื้นที่";
    $_SESSION['location'] = "0";
    header("Refresh:0");
    exit();
}