<?php
	include("header.php");
    if ($_POST["btn"] == "Submit"){
        // echo "<div class='errdiv'>A password reset email has been sent to your email address.</div>";
        $select = "SELECT * FROM USERS WHERE username = ".toQuote($_POST['username']);
        $out = $db->returnRecord($select);
        $unhashpass = "A".$out[0]["token"];
        $message = "Please login with the new password: $unhashpass";
        mail($out[0]["email"], "Password reset for Camagru", $message);
        $newpass = hash("whirlpool", $unhashpass);
        $statement = "UPDATE users SET `password` = ".toQuote($newpass)." WHERE username = ".toQuote($out[0]["username"]);
        $db->runStatement($db->getDBConn(),$statement);
        header("Location: login.php");
    }
?>
<html>
    <body>
        <div class="centerdiv">
            <form action="" method="post" style="top:50%">
                <h4 style="margin-top:0">Password Reset</h4>
                <input type="text" name="username" placeholder="Enter Username"><br>
                <input class="btn1" type="submit" name="btn" value="Submit"><br>
            </form>
        </div>
    </body>
</html>