<?php
	include("header.php");
	if(!$_SESSION["username"]){
		header("Location: signup.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>
	<div style="width:950px; margin:auto">
		<div style="position:absolute; top:10%; display:flex; flex-direction:column">
			<video class="webcamma" autoplay="true" id="video"></video><br>
			<button class="btn1" style="margin-top:10px; flex:1; width:100%" onclick="snap();">Take Picture</button><br>
			<input id="add_gal" type="button" name="addgal" class="btn1" value="Add to gallery">
			<input  class="filters" style="width:100%; color:white; font-family:'K2D'; margin-top:10px" type="file" id="imageLoader" name="imageLoader"/><br>
			<canvas class="webcamma" id="canvas" style="margin-top:10px"></canvas>
		</div>
		<div>
			<form method="post" style="position:relative; margin-top:17.5%;" class="filters">
					<div class="filtereth">Blur
					<input min="0" max="20" value="0" step="1" oninput="applyFilter()" data-filter="blur" data-scale="px" type="range"></div>
				
					<div class="filtereth">Brightness
					<input min="0" max="200" value="100" step="1" oninput="applyFilter()" data-filter="brightness" data-scale="%" type="range"></div>
				
					<div class="filtereth">Contrast
					<input min="0" max="200" value="100" step="1" oninput="applyFilter()" data-filter="contrast" data-scale="%" type="range"></div>
				
					<div class="filtereth">Grayscale
					<input min="1" max="100" value="1" step="1" oninput="applyFilter()" data-filter="grayscale" data-scale="%" type="range"></div>
				
					<div class="filtereth">Hue Rotate
					<input min="0" max="360" value="0" step="1" oninput="applyFilter()" data-filter="hue-rotate" data-scale="deg" type="range"></div>
				
					<div class="filtereth">Invert
					<input min="0" max="100" value="0" step="1" oninput="applyFilter()" data-filter="invert" data-scale="%" type="range"></div>
				
					<div class="filtereth">Opacity
					<input min="0" max="100" value="100" step="1" oninput="applyFilter()" data-filter="opacity" data-scale="%" type="range"></div>
				
					<div class="filtereth">Saturate
					<input min="0" max="200" value="100" step="1" oninput="applyFilter()" data-filter="saturate" data-scale="%" type="range"></div>
				
					<div class="filtereth">Sepia
					<input min="0" max="100" value="0" step="1" oninput="applyFilter()" data-filter="sepia" data-scale="%" type="range"></div>
					<a id="download" download="image.png"><button style="margin-left:75px" class="btn1" type="button" onClick="download()">Download</button></a>
			</form>
			<div>
				<p>bla bla bla</p>
			</div>
		</div>
	</div>
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

		function snap(){
			canvas.width = video.clientWidth;
			canvas.height = video.clientHeight;
			context.drawImage(video, 0, 0);
			document.getElementById("canvas").style.transform = "rotateY(180deg)";
			document.getElementById("imageLoader").value="";
		}
		var image = document.querySelector('canvas');
		var filterControls = document.querySelectorAll('input[type=range]');
		function applyFilter() {
			var computedFilters = '';
			filterControls.forEach(function(item, index) {
				computedFilters += item.getAttribute('data-filter') + '(' + item.value + item.getAttribute('data-scale') + ')';
			});
			image.style.filter = computedFilters;
		};
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
		document.getElementById("add_gal").addEventListener("click", function(){
			var img = new Image();
			img.src = canvas.toDataURL();
			var json = {
                        pic: img.src
                    }
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'save.php', true);
                    xhr.setRequestHeader('Content-type', 'application/json');
                    xhr.onreadystatechange = function (data) {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log(xhr.responseText);
                        }
                    }
                    xhr.send(JSON.stringify(json))
            });
	</script>
</body>
</html>