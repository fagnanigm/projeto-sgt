<?php

function ConnectDB(){

    $conn = sqlsrv_connect(SERVER_NAME_SQL, array("Database"=> SERVER_CON_DATABASE, "CharacterSet" => "UTF-8"));

    if(!$conn){
        echo "Connection could not be established.<br />";
        die( print_r( sqlsrv_errors(), true));
        exit();
    }

    return $conn;
}


?>