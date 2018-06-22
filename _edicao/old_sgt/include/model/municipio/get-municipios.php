<?php 


include("../../../app.php");

$query = "SELECT * FROM MunicipioG WHERE nome LIKE '%".$_POST['term']."%';";
$ans = sqlsrv_query($connection, $query, array()) or die("Error");

$response = array();

while($row = sqlsrv_fetch_array($ans, SQLSRV_FETCH_ASSOC)){

	$response[] = $row;

}

echo json_encode($response);

?>