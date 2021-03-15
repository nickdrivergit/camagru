<?php
	include("header.php");
// grab recaptcha library
	require_once "recaptchalib.php";
	if($_POST["btn"] == "Submit"){
		switch(checkInfo($_POST)){
			case 1:
				$err = "Username already exists.";
				session_destroy();
				break;
			case 2:
				$err = "Email already registered.";
				session_destroy();
				break;
			case 3:
				$err = "Password doesn't match.";
				session_destroy();
				break;
			case 4:
				$err = "Your password cannot be empty.";
				session_destroy();
				break;
			case 5:
				$err = "You can't use special characters, poes.";
				session_destroy();
				break;
			case 6:
				$err = "Please input a username.";
				session_destroy();
				break;
			case 7:
				$err = "Your password confirmation cannot be empty.";
				session_destroy();
				break;
			case 8:
				$err = "Your email cannot be empty.";
				session_destroy();
				break;
			default:
				header("Location: reg_success.php");
				break;
		}
		echo "<div class='errdiv'>$err</div>";	
	}

	function checkInfo($info){
		if($info["username"] != ""){
			if (preg_match('/[;"=]/', $info["username"])){
				return 5;
            }
			else if(isUnique("username",$info["username"])){
				$_SESSION["username"] = $info["username"];
			}
			else
				return 1;
		}
		else
			return 6;
		
		if($info["email"] != ""){
			if (preg_match('/[;"=]/', $info["email"])){
				return 5;
			}
			else if(isUnique("email",$info["email"])){
					$_SESSION["email"] = $info["email"];
			}
			else
				return 2;
		}
		else
			return 8;

		if($info["pass"] != ""){
			if (preg_match('/[;"=]/', $info["pass"])){
				return 5;
			}
			else{
				$out = checkPassword($info["pass"], $info["conf"]);
				return $out;
			}
		}
		else
			return 4;

		if($info["conf"] != ""){
			if (preg_match('/[;"=]/', $info["conf"])){
                return 5;
			}
		}
		else
			return 7;
	}

	function checkPassword($pass, $conf){
		$hashpass = hash("whirlpool",$pass);
		$hashconf = hash("whirlpool",$conf);
		if($hashpass === $hashconf){
			$_SESSION["pass"] = $hashpass;
			return 0;
		}
		else
			return 3;
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
				<input title="Password requires one lower case letter, one upper case letter, one digit, 8+ characters, and no spaces." pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" type="password"  name="pass" placeholder="Enter Password"><br>
				<input title="Password requires one lower case letter, one upper case letter, one digit, 8+ characters, and no spaces." pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" type="password"  name="conf" placeholder="Confirm Password"><br>
				<input type="email" name="email" placeholder="Enter Email Address"><br>
				<div class="g-recaptcha" data-sitekey="6LdGtnUUAAAAAP5qjdcqi6hiS0zrCLUcM7mhqXiX"></div>
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