<?php 

	include('../../../app.php');
	
	$data = array(
		'email' => $_POST['email'],
		'password' => password_generate($_POST['password'])
	);

	$query = "SELECT * FROM SGT_Usuarios WHERE Email = '".$data['email']."' AND Senha = '".$data['password']."'";
	$ans = sqlsrv_query($connection, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	$count = sqlsrv_num_rows($ans);

	if($count > 0){

		$row = sqlsrv_fetch_array($ans, SQLSRV_FETCH_ASSOC);
		$_SESSION['CustomerID'] = $row['Codigo_Cliente'];
		$_SESSION['Customer'] = $row;
		unset($_SESSION['Customer']['Senha']);
		redirect('/index.php');

	}else{

		$_SESSION['erro'] = "Usuario/Senha não Encontrado!";	
		redirect('/login.php');

	}	

	
?>