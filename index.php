<?php
	include("includes/config.php");

	if($_SESSION['userLoggedIn']) {
		$userLogged = $_SESSION['name'];
	} else {
		header("Location: register.php");
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to Spotify!</title>
</head>
<body>
	Hello!
</body>
</html>