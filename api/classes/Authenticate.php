<?php 

namespace Classes;

class Authenticate {

	private $db;

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function create_account($args){

		$response = array();

		/*
		$date = new \DateTime();
		$insertStatement = $this->db->insert(array('email', 'username', 'password','create_time'))->into('users')->values(array('fagnanigm@gmail.com', 'Guilherme', 'your_password', $date->format("Y-m-d\TH:i:s") ));
		$response['user_id'] = $insertStatement->execute();
		*/

		return $response;

	}
	
	
}


?>