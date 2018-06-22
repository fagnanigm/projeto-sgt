<?php

function required ($url){
	return DIR_TEMPLATE."$url.php";	
}

function redirect($url){
	header("Location:$url");
}

function redirectToError(){
	redirect("/pagina-nao-encontrada");
}

function is_logged(){
	return (isset($_SESSION['CustomerID']) ? true : false );
}

function protectPage(){
	if(!is_logged()){
		redirect('/login.php');
	}else{
		return true;
	}
}

function echo_error(){
	if (isset($_SESSION['erro'])){
		echo '<div class="alert alert-danger">'.$_SESSION['erro'].'</div>';
		unset($_SESSION['erro']);
	}
}

function password_generate($pass){
	return hash('sha256', $pass);
}

function cut_string($string,$limit){

    if(strlen($string) > $limit){

        $pieces = explode( " ", $string);

        $string = '';

        foreach ($pieces as $key => $value) {
                
            if((strlen($string) + strlen($value) + 1) > $limit){
                $string = trim($string)."...";
                break;
            }
            
            $string .= $value." ";

        }


    }

    return trim($string);

}

function get_next_cte(){

	global $connection;

	$query = "SELECT MAX(id) AS id FROM sgt_cte;";
	$id = sqlsrv_query($connection, $query, array()) or die (mssql_get_last_message());
	$row = sqlsrv_fetch_array($id, SQLSRV_FETCH_ASSOC);

	if(is_null($row['id'])){
		return 1;
	}else{
		return $row['id'] + 1;
	}

}

?>