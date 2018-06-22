<?php 

include("../../../app.php");

// CADASTRA CTE no BANCO

if($_POST['id'] == '0'){

	$response = array(
		'code' => get_next_cte()
	);

}else{

	$query = "SELECT * FROM sgt_cte WHERE id = '".$_POST['id']."'";
	$ans = sqlsrv_query($connection, $query, array()) or die("Error");

	$row = sqlsrv_fetch_array($ans, SQLSRV_FETCH_ASSOC);

	$response = array(
	    'result' => true,
	    'cte' => array(
	    	'content' => json_decode($row['content']),
	    	'return_acbr' => json_decode($row['return_acbr'])
	    )
	);

}

echo json_encode($response);



?>
