<?php
	session_start();
	unset($_SESSION['CustomerID']);
	unset($_SESSION['Customer']);
	unset($_SESSION['erro']);
	header("Location: /index.php");
?>