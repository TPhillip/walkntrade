<?php
require_once "framework/CredentialStore.php";
$cs = new CredentialStore();
$schoolTextId = $cs->cookieCheck("sPref");
if($cs->getSchoolName($schoolTextId) == null)
	header('Location: selector') ;
if(!$loggedIn = $cs->getLoginStatus())
	header('Location: ./');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>WalkNtrade.com</title>
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<link type="text/css" rel="stylesheet" href="/css/spritesheet.css">
	<link type="text/css" rel="stylesheet" href="css/login_window.css">
	<link type="text/css" rel="stylesheet" href="css/addlisting.css">
	<link type="text/css" rel="stylesheet" href="/css/feedback_slider.css">
	<link rel="shortcut icon" href="http://www.walkntrade.com/favicon.ico?v=2" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	<script type="text/javascript" src="/client_js/include.js"></script>	
	<script type="text/javascript" src="/client_js/jquery.min.js"></script>
	<script type="text/javascript" src="/client_js/user_login.js"></script>
	<script type="text/javascript" src="/client_js/listings.js"></script>
	<script type="text/javascript" src="/client_js/feedback_slider.js"></script>	
		<script>
			(function(i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] ||
				function() {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o), m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

			ga('create', 'UA-42896980-1', 'walkntrade.com');
			ga('send', 'pageview');

		</script>
		<script type="text/javascript">
			function formatPrice(element) {
				value = element.value;
				if (value != "") {
					value = value.replace(/[^0-9.]/g, "");
					if (value.charAt(0) != "$")
						element.value = "$" + value;
				}
			}
		</script>
	</head>

	<body onload="javascript:initDropBox()">
		<div id="throbber"><img src="colorful/loader.gif">
		</div>
		<div class="headerBar"></div>
		<div id="pageHead"><?php $noLogin=false; include("include/header.php"); ?></div>
		<div id="sidebar"><?php include("include/sidebar.php");?></div>
		<div class="wrapper">
			<div id="addTable" class="boxStyle1">
				<p>
					<h1 style="text-align:center">Post a housing or roommate advertisement.</h1>
				</p>
				<table style="width:100%">
					<form action="javascript:void(0)" method="POST" name="housing" onSubmit="addHousing()">
						<tr>
					<td colspan="2">
						<input type="text" name="Name" placeholder="*The title of your post">
					</td>
				</tr>
				<tr class="errorClass">
					<td id="errTitle"></td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="Details" placeholder="*A short description of your advertisement."></textarea>
					</td>
				</tr>
				<tr class="errorClass">
					<td id="errDescription" colspan="4"></td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="imgDrop"><h3 style="color:#A0A0A0">Drag and drop at least two images here or select them below (limit 4 images each less than 5MB)</h3></div>
						<input type="file" accept="image/jpeg" multiple="multiple" onchange="getImages(this)"></input>
						<br>
						<br>
					</td>
				</tr>
				<tr class="errorClass">
					<td id="errImage"></td>
				</tr>
				<tr>
					<td width="50%" >
						<input type="text" name="Price" maxlength="7" onKeyUp="javascript:formatPrice(this)" placeholder="Price (optional)">
					</td>
					<td>
					</td>
				</tr>
				<tr class="errorClass">
					<td id="errPrice"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="text" name="Tags" placeholder="*Descriptive tags about your advertisement">
					</td>
				</tr>
				<tr class="errorClass">
					<td id="errTags" colspan="2"></td>
				</tr>
				<tr>
					<th colspan="4">
						<input type="submit" value="Post!">
					</th>
				</tr>
					</form>
				</table>
			</div>
		</div>
		<div class="footerBar">
			<?php
			include ("include/footer.html");
 ?>
		</div>
	</body>
</html>