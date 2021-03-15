<?php
    include("header.php");
    $i = 0;
    if($_POST["btn"]){
        $i = 1;
    }
    if($_POST["btn2"]){
        $select = "SELECT * FROM USERS WHERE username =".toQuote($_SESSION['username']);
        $out = $db->returnRecord($select);
        if($_POST["curruser"] === $_SESSION["username"]){
            if($_POST["newuser"] != "" && isUnique("username",$_POST["newuser"])){
                $statement = "UPDATE users SET username = ".toQuote($_POST['newuser'])." WHERE username = ".toQuote($_SESSION["username"]);
                $statement .= "; UPDATE images SET username = ".toQuote($_POST['newuser'])." WHERE username = ".toQuote($_SESSION["username"]);
                $statement .= "; UPDATE comments SET username = ".toQuote($_POST['newuser'])." WHERE username = ".toQuote($_SESSION["username"]);
                $db->runStatement($db->getDBConn(),$statement);
                $_SESSION["username"] = $_POST["newuser"];
            }
        }
        if(hash("whirlpool",$_POST["currpass"]) == $out[0]["password"]){
            $newpass = hash("whirlpool", $_POST["newpass"]);
            $statement = "UPDATE users SET `password` = ".toQuote($newpass)." WHERE `password` = ".toQuote(hash("whirlpool",$_POST["currpass"]))." AND username = ".toQuote($_SESSION["username"]);
            $db->runStatement($db->getDBConn(),$statement);
        }
        if($_POST["curremail"] == $out[0]["email"]){
            $statement = "UPDATE users SET email = ".toQuote($_POST['newemail'])." WHERE email = ".toQuote($_POST["curremail"]);
            $db->runStatement($db->getDBConn(),$statement);
        }
        if($_POST["notifications"]){
            if($_POST["notifications"] == "noteon")
                $onoff = 1;
            else
                $onoff = 0;
            $statement = "UPDATE users SET notifications = ".toQuote($onoff)." WHERE username = ".toQuote($_SESSION["username"]);
            $db->runStatement($db->getDBConn(),$statement);
        }
        // header("Location: video.php");
    }
?>
<html>
    <div class="centerdiv">
        <form style="align-text:left" action="" method="post" style="top:50%">
            <h4 style="margin-top:0">Change settings:</h4>
            <?php 
                if($i == 0){
                    echo "<label><input type='checkbox' name='usercheck' value='usercheck'>Change Username</label><br>";
                    echo "<label><input type='checkbox' name='passcheck' value='passcheck'>Change Password</label><br>";
                    echo "<label><input type='checkbox' name='emailcheck' value='emailcheck'>Change Email Address</label><br>";
                    echo "<label><input type='checkbox' name='notecheck' value='notecheck'>Change Notification Settings</label><br>";
                    echo "<input class='btn1' type='submit' name='btn' value='Submit'><br>";
                }
                if($i >= 1 && $_POST["usercheck"]==usercheck){
                    echo "<input type='text' name='curruser' placeholder='Enter Current Username'><br>";
                    echo "<input type='text' name='newuser' placeholder='Enter New Username'><br>";
                }
                if($i >= 1 && $_POST["passcheck"]==passcheck){
                    echo "<input title='Password requires one lower case letter, one upper case letter, one digit, 8+ characters, and no spaces.' pattern='^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$'' type='password'  name='currpass' placeholder='Enter Current Password'><br>";
                    echo "<input title='Password requires one lower case letter, one upper case letter, one digit, 8+ characters, and no spaces.' pattern='^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$' type='password'  name='newpass' placeholder='Enter New Password'><br>";
                }
                if($i >= 1 && $_POST["emailcheck"]==emailcheck){
                    echo "<input type='email' name='curremail' placeholder='Enter Current Email Address'><br>";
                    echo "<input type='email' name='newemail' placeholder='Enter New Email Address'><br>";
                }
                if($i >= 1 && $_POST["notecheck"] == notecheck){
                    echo "<label><input type='radio' name='notifications' value='noteon'>Receive Email Updates</label><br>";
                    echo "<label><input type='radio' name='notifications' value='noteoff'>Don't Receive Email Updates</label><br>";
                }
                if($i == 1){
                    echo "<input class='btn1' type='submit' name='btn2' value='Submit'><br>";
                }
            ?>
        </form>
    </div>
</html>