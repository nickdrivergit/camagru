<?php
    session_start();
    session_destroy();
	session_start();
	include("header.php");
	include("config.php");
// grab recaptcha library
	require_once "recaptchalib.php";
	
	if($_POST["btn"] == "Submit"){
		switch(checkInfo($_POST)){
			case 1:
				$err = "Username already exists.";
				break;
			case 2:
				$err = "Email already registered.";
				break;
			case 3:
				$err = "Password doesn't match.";
                break;
            case 4:
                $err = "Password cannot be empty";
                break;
            case 5:
                $err = "Don't be a poes.";
                break;
			default:
				header("Location: reg_success.php");
				break;
		}
		echo "<div class='errdiv'>$err</div>";	
	}

	function checkInfo($info){
		if($info["username"] != ""){
            if (preg_match('/;/', $info["username"])){
                return 5;
            }
			else if(isUnique("username",$info["username"])){
				$_SESSION["username"] = mysqli_real_escape_string($info["username"]);
			}
		}
		else
			return 1;
		
		if($info["email"] != ""){
			if(isUnique("email",$info["email"])){
				$_SESSION["email"] = mysqli_real_escape_string($info["email"]);
			}
		}
		else
			return 2;
		
		if($info["pass"] != ""){
			$out = checkPassword($info["pass"], $info["conf"]);
			return $out;
        }
        else
            return 4;


	}

	function checkPassword($pass, $conf){
		$hashpass = mysqli_real_escape_string($pass);
		$hashconf = mysqli_real_escape_string($conf);
		$hashpass = hash("whirlpool",hash("whirlpool",$hashpass));
		$hashconf = hash("whirlpool",hash("whirlpool",$hashconf));
		if($hashpass === $hashconf){
			$_SESSION["pass"] = $hashpass;
			return 0;
		}
		else
			return 3;
	}

	function isUnique($param, $val){
		global $db;

		$statement = "SELECT * FROM USERS WHERE ".$param." = ".toQuote($val).";";
		$count = $db->returnRecord($statement);
		return (!count($count));
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Camagru</title>
		<meta http-equiv="Cache-control" content="no-cache">
		<link href="https://fonts.googleapis.com/css?family=K2D" rel="stylesheet"> 
		<link href="style.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="centerdiv">
			<form action="" method="post" style="top:50%">
				<h4 style="margin-top:0">Sign Up</h4>
				<input type="text" name="username" placeholder="Enter Username"><br>
				<input title="Password requires one lower case letter, one upper case letter, one digit, 6-13 characters, and no spaces." pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" type="password"  name="pass" placeholder="Enter Password"><br>
				<input title="Password requires one lower case letter, one upper case letter, one digit, 6-13 characters, and no spaces." pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" type="password"  name="conf" placeholder="Confirm Password"><br>
				<input type="email" name="email" placeholder="Enter Email Address"><br>
				<!-- <div class="g-recaptcha" data-sitekey="6LdGtnUUAAAAAP5qjdcqi6hiS0zrCLUcM7mhqXiX"></div> -->
				<input class="btn1" type="submit" name="btn" value="Submit"><br>
			</form>
			<script src='https://www.google.com/recaptcha/api.js'></script>
			<anything style="color:white;font-family:K2D">Already registered? <a href=login.php>Login here.</a><br>
		</div>
		<?php
			$secret = "6LdGtnUUAAAAAFwguSiwEjMFLPw7QkYxQBkx4K91";
			$response = null;
			$reCaptcha = new ReCaptcha($secret);

			if ($_POST["g-recaptcha-response"]){
				$response = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]
				);
			}
		?>
	</body>
</html>