<?php 


include("../../../app.php");

$query = "SELECT * FROM SGT_CFOP_Omie WHERE Descricao LIKE '%".$_POST['term']."%' OR Codigo_CFOP LIKE '%".$_POST['term']."%';";
$ans = sqlsrv_query($connection, $query, array()) or die("Error");

$response = array();

while($row = sqlsrv_fetch_array($ans, SQLSRV_FETCH_ASSOC)){

	$response[] = $row;

}

echo json_encode($response);

?>