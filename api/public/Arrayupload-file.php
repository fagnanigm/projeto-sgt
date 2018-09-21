<?php

include ("../../app.php");

$id_parent = $_POST['id_parent'];

if($id_parent == 'false' || $id_parent == false || $id_parent == 0 || !$id_parent){

    if($_POST['subject'] == 'projetos'){

        $response = $projetos->get_next_code();
        $id_parent = $response['next_id'];

    }else{
        die();
    }
}

$id_file = $files->upload(array(
    'tmp_name' => $_FILES['file']['tmp_name'],
    'name' => $_FILES['file']['name'],
    'id_parent' => $id_parent,
    'id_flow' => $_POST['flowIdentifier'],
    'subject' => $_POST['subject'],
    'file_type' => $_POST['file_type'], 
    'params' => json_encode($_POST)
));

echo $id_file;

?>