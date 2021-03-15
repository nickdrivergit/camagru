<?php
	include("header.php");
	if(!$_SESSION["username"]){
		header("Location: login.php");
	}
	$headers = getallheaders();
	if ($headers["Content-type"] == "application/json") {
    	$stuff = json_decode(file_get_contents("php://input"), true);
		switch($stuff){
			case "sticker1":
				$sticker = imagecreatefrompng('./images/sticker1.png');
				$src = "./images/sticker1.png";
				break;
			case "sticker2":
				$sticker = imagecreatefrompng('./images/sticker2.png');
				$src = "./images/sticker2.png";
				break;
			case "sticker3":
				$sticker = imagecreatefrompng('./images/sticker3.png');
				$src = "./images/sticker3.png";
				break;
			case "sticker4":
				$sticker = imagecreatefrompng('./images/sticker4.png');
				$src = "./images/sticker4.png";
				break;
		}
		$im = imagecreatefrompng('./bot.png');
		imagecopyresampled($im, $sticker, 0, 0, 0, 0, 640, 480, 640, 480);
		imagepng($im, $_SESSION["username"]."new.png");
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>
	<div style="position:absolute; z-index:1; top:10%; display:flex; flex-direction:column">
		<video class="webcamma" autoplay="true" id="video"></video><br>
		<button class="btn1" style="margin-top:10px; flex:1; width:100%" onclick="snap();">Take Picture</button>
		<input id="add_gal" type="submit" name="addgal" class="btn1" value="Add to gallery">
		<input  class="filters" style="width:100%; color:white; font-family:'K2D'; margin-top:10px" type="file" id="imageLoader" name="imageLoader"/><br>
		<div style='width:640px; height:480px; position:relative; z-index:2; color:white; font-family:K2D' id='canvdiv'>
			<canvas class="webcamma" id="canvas"></canvas>
		</div>
		<div style='width:640px; height:50px; position:relative; z-index:2; color:white; font-family:K2D' id='errdiv'>
		</div>
	</div>
	<div class="filterdiv" style="position:absolute; top:10%; left:700px;">
		<?php
			$i = 0;
			$imarray = $db->returnRecord("SELECT * FROM images WHERE username = ".toQuote($_SESSION["username"]));
			while ($imarray[$i]){
			echo "<div style='position:relative; display:flex; flex-direction:column'><img style='width:380px;height:280px;margin:auto' src=".$imarray[$i]["image"]."></div><br>";
				$i++;
			}
		?>
	</div>
	<div class="filterdiv" style="position:absolute; bottom:5%; left:700px">
		<img src='./images/sticker1.png' id='sticker1' width='80%' onclick='addSticker(id)'>
		<img src='./images/sticker2.png' id='sticker2' width='80%' onclick='addSticker(id)'>
		<img src='./images/sticker3.png' id='sticker3' width='80%' onclick='addSticker(id)'>
		<img src='./images/sticker4.png' id='sticker4' width='80%' onclick='addSticker(id);'>
	</div>
</body>
</html>
<script type="text/javascript">
	var video = document.getElementById('video');
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	var imageLoader = document.getElementById('imageLoader');
		imageLoader.addEventListener('change', handleImage, false);

	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
	navigator.mozGetUserMedia || navigator.oGetUserMedia || navigator.msGetUserMedia;

	if (navigator.getUserMedia){
		navigator.getUserMedia({video:true}, streamWebCam, throwError);
	}

	function streamWebCam(stream){
		video.srcObject = stream;
		video.play();
	}

	function throwError(e){
		alert(e.name);
	}

	function addSticker(id){
		var sticker = new Image();
		sticker.src = "./images/"+id+".png";
		if (canvas.width == 640){
			document.getElementById("errdiv").innerHTML = ""; 
			context = canvas.getContext('2d');
			context.drawImage(sticker,0,0,video.clientWidth, video.clientHeight);
			img.src = canvas.toDataURL('image/png');
			document.getElementById("canvdiv").innerHTML = "<img src="+img.src+">";
		}
		else{
			document.getElementById("errdiv").innerHTML = "You need to add/take a picture first."; 
		}
	}

	function snap(){
		document.getElementById("errdiv").innerHTML = ""; 
		canvas.width = video.clientWidth;
		canvas.height = video.clientHeight;
		context.translate(canvas.width, 0);
		context.scale(-1, 1);
		context.save();
		context.restore();
		context.drawImage(video, 0, 0);
		document.getElementById("canvas").style.transform = "rotateY(0deg)";
		document.getElementById("imageLoader").value="";
	}
	var image = document.querySelector('canvas');

	function handleImage(e){
		var reader = new FileReader();
		reader.onload = function(event){
			var img = new Image();
			img.onload = function(){
				canvas.width = video.clientWidth;
				canvas.height = video.clientHeight;
				context.drawImage(img,0,0,img.width,img.height,0,0,canvas.width,canvas.height);
			}
			img.src = event.target.result;
		}
		reader.readAsDataURL(e.target.files[0]);
		document.getElementById("canvas").style.transform = "rotateY(0deg)";
	}

	function download(){
		var download = document.getElementById("download");
		var image = document.getElementById("canvas").toDataURL("image/png")
					.replace("image/png", "image/octet-stream");
		
		download.setAttribute("href", image);
	}

	function replaceImage(){
		var src = "./<?php echo $_SESSION["username"]?>new.png"
		var img1 = new Image();
		img1.src = src;
		canvas.width = video.clientWidth;
		canvas.height = video.clientHeight;
		context.drawImage(img1,0,0,640,480,0,0,canvas.width,canvas.height);
	}

	document.getElementById("add_gal").addEventListener("click", function(){
		var img = new Image();
		img.src = canvas.toDataURL();
		if (canvas.width == video.clientWidth){
			var json = {
					pic: img.src
				}
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'save.php', true);
				xhr.setRequestHeader('Content-type', 'application/json');
				xhr.onreadystatechange = function (data) {
					if (xhr.readyState == 4 && xhr.status == 200)
						console.log(xhr.responseText);
				}
				xhr.send(JSON.stringify(json))
		}
		});
</script>