<?php
    session_start();
    include_once("header.php");
    $check = array("username"=>$_GET['username'], "token"=>$_GET['token']);
    print_r($check);
    $db->verifyUser($check);
    $_SESSION["username"]=$_GET["username"];
    header("Location: video.php");
?>