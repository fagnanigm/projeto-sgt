<?php 

	include("../../../app.php");

	$_SESSION['Context'] = $_GET['context'];

	header('Location:/index.php');

?>