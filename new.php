<?php
include("header.php");
$headers = getallheaders();
if ($headers["Content-type"] == "application/json") {
    $stuff = json_decode(file_get_contents("php://input"), true);
    var_dump($stuff);
}
if ($stuff != "sticker1"){
    echo "poes";
}
?>