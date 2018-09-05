<?php

namespace Classes;

class Localidades {

	private $db;

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function refresh($args = array()){

		$response = array(
			'result' => false,
			'data' => array(),
			'failed' => array()
		);

		// Limpa tabela

		$stmt = $this->db->query('TRUNCATE TABLE localidades;');
		$stmt->execute();

		// Puxa os estados do IBGE

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache"
			),	
		));

		$ufs_json = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$response['error'] = "cURL Error #:" . $err;
			return $response;
		} else {
			$ufs_array = json_decode($ufs_json);
		}

		// Puxa todas as cidades de acordo com o estado, e salva no banco

		foreach ($ufs_array as $uf_key => $uf) {
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://servicodados.ibge.gov.br/api/v1/localidades/estados/".$uf->id."/municipios",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"Cache-Control: no-cache",
				),
			));

			$cidades_json = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$response['error'] = "cURL Error #:" . $err;
				return $response;
			} else {
				$cidades_array = json_decode($cidades_json);
			}

			foreach ($cidades_array as $mn_key => $cidade) {
				
				$data = array(
					"uf_code_ibge" => $uf->id,
					"uf_sigla" => $uf->sigla,
					"uf_nome" => $uf->nome,
					"municipio_code_ibge" => $cidade->id,
					"municipio_nome" => $cidade->nome,
					"pais_code_ibge" => "1058",
					"pais_nome" => "Brasil"
				);

				$insertStatement = $this->db->insert(array_keys($data))->into('localidades')->values(array_values($data));
				$localidade_id = $insertStatement->execute();

				if(strlen($localidade_id) > 0){
					$response['data'][] = $data;
				}else{
					$response['failed'][] = $data;
				}

			}

		}

		if(count($response['failed']) == 0){
			$response['result'] = true;
		}

		return $response;

	}

	public function getAllUfs(){
		$response = array();
		$select = $this->db->query('SELECT uf_code_ibge, uf_sigla, uf_nome FROM localidades GROUP BY uf_code_ibge, uf_sigla, uf_nome ORDER BY uf_nome;');
		$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');
		return $response;
	}

	public function getUf($term){
		$response = array();
		$term = Utilities::escape_sql_term($term);
		$select = $this->db->query("SELECT uf_code_ibge, uf_sigla, uf_nome FROM localidades WHERE uf_sigla = '$term' OR uf_code_ibge = '$term' OR uf_sigla = '$term' OR uf_nome = '$term' GROUP BY uf_code_ibge, uf_sigla, uf_nome;");
		$uf = $this->parser_fecth($select->fetch(\PDO::FETCH_ASSOC));

		if(!$uf){
			$response['result'] = false;
			$response['error'] = "Nenhum estado encontrado para este termo.";
			return $response;
		}else{
			$response['result'] = true;
			$response['estado'] = $uf;
		}

		return $response;
	}

	public function getMunicipios($term){
		$response = array();
		$term = Utilities::escape_sql_term($term);
		$select = $this->db->query("SELECT id, municipio_code_ibge, municipio_nome  FROM localidades WHERE uf_sigla = '$term' OR uf_code_ibge = '$term' OR uf_sigla = '$term' OR uf_nome = '$term' ORDER BY uf_nome, municipio_nome;");
		$uf = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		if(!$uf){
			$response['result'] = false;
			$response['error'] = "Nenhum município encontrado para este termo.";
			return $response;
		}else{
			$response['result'] = true;
			$response['municipios'] = $uf;
		}

		return $response;
	}

	public function getMunicipio($term){
		$response = array();
		$term = Utilities::escape_sql_term($term);
		$select = $this->db->query("SELECT municipio_code_ibge, municipio_nome FROM localidades WHERE municipio_code_ibge = '$term' OR municipio_nome = '$term';");
		$uf = $this->parser_fecth($select->fetch(\PDO::FETCH_ASSOC));

		if(!$uf){
			$response['result'] = false;
			$response['error'] = "Nenhum município encontrado para este termo.";
			return $response;
		}else{
			$response['result'] = true;
			$response['municipio'] = $uf;
		}

		return $response;
	}

	public function parser_fecth($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_localidade($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_localidade($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_localidade($local){

		foreach ($local as $key => $field) {
			$local[$key] = trim($field);
		}		

		return $local;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do local não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('locais')->where('id', '=', $args['id']);
		$affectedRows = $updateStatement->execute();

		if($affectedRows > 0){

			$response['result'] = true;
			$response['affectedRows'] = $affectedRows;
			return $response;

		}else{
			$response['error'] = 'Nenhum registro afetado.';
		}

		return $response;

	}

}

?>