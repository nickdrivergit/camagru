<?php
	include("header.php");
    if ($_POST["btn"] == "Submit"){
        $username = toQuote($_POST["username"]);
        $password = hash("whirlpool",$_POST["pass"]);
        $statement = "SELECT * FROM  users WHERE username = $username";
        $out = $db->returnRecord($statement);
        echo "<div class='errdiv'>You must verify your email address before signing in.</div>";
        if ($out[0]["password"] == $password && $out[0]["verified"] == "1"){
            $_SESSION["username"] = $_POST["username"];
            header("Location: video.php");
        }
    }
?>
<html>
    <body>
        <div class="centerdiv">
            <form action="" method="post" style="top:50%">
                <h4 style="margin-top:0">Login</h4>
                <input type="text" name="username" placeholder="Enter Username"><br>
                <input title="Password requires one lower case letter, one upper case letter, one digit, 6-13 characters, and no spaces." pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" type="password"  name="pass" placeholder="Enter Password"><br>
                <input class="btn1" type="submit" name="btn" value="Submit"><br>
            </form>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <afdgshng style="color:white;font-family:K2D">Forgot password? <a href=forgotpass.php>Reset here.</a><br>
        </div>
    </body>
</html>