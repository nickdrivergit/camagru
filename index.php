<?php
    // ini_set('display_errors', 'On');
    include("header.php");
    $imagelimit = 5;
    $out2 = $db->returnRecord("SELECT * FROM images");
    $total = count($out2);
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $i = ($_GET["page"] - 1 )* $imagelimit;
    }
    else{
        $i = 0;
        $page = 1;
    }
    $pages = ceil($total / $imagelimit);
    echo "<div class='galdiv'>";
    while ($i < $imagelimit*$page){
        echo "<a class='username' href='image.php?imageID=".$out2[$i]["imageID"]."'</a>";
        echo "<div class='imagediv'><img src=".$out2[$i]["image"]."></div>";
        $i++;
    }
    echo "<br><div class='imagediv' style='bottom:0%'>";
    for ($x = 1; $x <= $pages; $x++){
        echo "<a href='index.php?page=$x'>$x</a>"."\t";
    }
    echo "</div>";
    echo "</div>";

?>