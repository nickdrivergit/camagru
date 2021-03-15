<?php
include("header.php");
$headers = getallheaders();
if ($headers["Content-type"] == "application/json") {
    $stuff = json_decode(file_get_contents("php://input"), true);
    var_dump($stuff);
}
$fields = array(
    "image",
    "username"
);
$table = array(
    "name"      => "images",
    "fields"   => $fields
);
$values = array(
                toQuote($stuff["pic"]),
                toQuote($_SESSION["username"])
);
$db->insertRecord(
    array(
            "table"     => $table,
            "values"    => $values
    )
);
?>