<?php
    include("header.php");
    if ($_POST["like-btn"]){
        $statement = "UPDATE images SET likes = likes + 1 WHERE imageID = ".toQuote($_GET["imageID"]);
        $db->runStatement($db->getDBConn(),$statement);
    }
    if ($_POST["comm-btn"]){
        if ($_POST["commbox"]){
            $pattern = array("#;#", "#=#", "#\"#");
            $replace = array("%1", "%2", "%3");
            $noinjectcomm = preg_replace($pattern, $replace, $_POST["commbox"]);
            $statement = "INSERT INTO comments (imageID, username, comment) VALUES (";
            $statement .= toQuote($_GET["imageID"]).", ".toQuote($_SESSION["username"]).", ".toQuote($noinjectcomm).")";
            $db->runStatement($db->getDBConn(),$statement);
            $_POST["commbox"] = "";
            $statement = "SELECT * FROM images WHERE imageID = ".toQuote($_GET["imageID"]);
            $out = $db->returnRecord($statement);
            $user = $out[0]["username"];
            $statement = "SELECT * FROM users WHERE username = ".toQuote($user);
            $out = $db->returnRecord($statement);
            $message = $_SESSION["username"]." commented on your image. Go to 127.0.0.1:8080/camagru/image.PHP?imageID=".$_GET["imageID"]." to check it out!";
            if ($out[0]["notifications"]){
                mail($out[0]["email"], "New Camagru Comment", $message);
            }
        }
    }
    $imarray = $db->returnRecord("SELECT * FROM images WHERE imageID = ".toQuote($_GET["imageID"]));
    $commarray = $db->returnRecord("SELECT * FROM comments WHERE imageID = ".toQuote($_GET["imageID"]));
    echo "<div class='imagediv' style='top:10%'><img src=".$imarray[0]["image"].">";
    echo "<div class='commdiv'>";
    foreach ($commarray as $something){
        $pattern = array("#(%1)#", "#(%2)#", "#(%3)#");
        $replace = array(";", "=", "\"");
        $noinjectcomm = preg_replace($pattern, $replace, $something["comment"]);
        $out = "(".$something["date"].") ".$something["username"].": ".$noinjectcomm;
        echo $out."<br><hr>";
    }
    echo "</div>";
    echo "<br><anything style='color:white;font-family:K2D;font-size:150%'>Likes: ".$imarray[0]["likes"]."</anything>";
    if ($_SESSION["username"]){
        echo    
        "<div><form action='' method='post' id='commform'>
        <input type='submit' class='btn1' value='like!' name='like-btn'><input type='submit' class='btn1' value='Post Comment' name='comm-btn'>
        </form></div>";
        echo "<br><textarea name='commbox' form='commform' rows='5' cols='80' class='comm' placeholder='Comment Box'></textarea><br>";
    }
    echo "</div>";
?>