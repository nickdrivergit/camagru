<?php
    session_start();
    include("./config/setup.php");
    if($_POST["logout-btn"]){
        session_destroy();
        header('Location: signup.php');
    }
    else if($_POST["login-btn"]){
        header('Location: signup.php');
    }
    else if($_POST["home-btn"]){
        header('Location: index.php');
    }
    else if($_POST["user-btn"]){
        header('Location: video.php');
    }
    else if($_POST["settings-btn"]){
        header('Location: settings.php');
    }
    else if($_POST["usergal-btn"]){
        header('Location: usergal.php');
    }
?>
<head>
    <title>Camagru</title>
    <div id="heading" class="header_div">
    <form action="" method="post">
        <div style="position:fixed;leftt:1vw;">
        <table>
            <tr>
            <td><input type="submit" class="header_button" name="home-btn" value="Gallery"></td>
                <?php
                    if($_SESSION["username"] != "")
                    {
                        echo '<td><input type="submit" class="header_button" name="user-btn" value="Camera"></td>';
                        echo '<td><input type="submit" class="header_button" name="usergal-btn" value="'.$_SESSION["username"].'\'s Images"></td>';
                    }
                ?>
            </tr>
        </table>
        </div>
        <div style="position:fixed;right:1vw;">
        <table>
            <tr>
                <?php
                    if($_SESSION["username"] != "")
                    {
                        echo
                        '<td><input type="submit" class="header_button" name="logout-btn" id="logout-btn" value="Log Out"></td>'.
                        '<td><input type="submit" class="header_button" name="settings-btn" value="Settings"></td>';
                    }
                    else
                        echo '<td><input type="submit" class="header_button" name="login-btn" value="Login/Sign Up"></td>';
                    ?>
            </tr>
            </table>
        </div>
    </form>
    </div>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=K2D" rel="stylesheet"> 
</head>
<footer id="footing" class="footer_div">
    <div>
        &copy Nick
    </div>
</footer>
</html>