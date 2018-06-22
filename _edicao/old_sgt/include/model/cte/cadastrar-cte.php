<?php 

date_default_timezone_set("Brazil/East");

$dt = new DateTime();

include("../../../app.php");

$codigo_cte = (isset($_POST['content']['informacoes_gerais']['numero_cte']) ? $_POST['content']['informacoes_gerais']['numero_cte'] : get_next_cte());

// CADASTRA CTE no BANCO

if($_POST['content']['is_update'] == '1'){

	$query = "UPDATE sgt_cte SET content = '".json_encode($_POST['content'])."' WHERE id = '".$codigo_cte."';";

	$ans = sqlsrv_query($connection, $query, array()) or die("Error");

	$response = array(
	    'result' => true
	);


}else{

	$query = "insert into sgt_cte (
	    id,
	    content,
	    return_acbr,
	    create_time
	) values (
	    '".$codigo_cte."',
	    '".json_encode($_POST['content'])."',
	    '0',
	    '".time()."');";

	$ans = sqlsrv_query($connection, $query, array()) or die("Error");

	$response = array(
	    'result' => true
	);

}


echo json_encode($response);



?>
