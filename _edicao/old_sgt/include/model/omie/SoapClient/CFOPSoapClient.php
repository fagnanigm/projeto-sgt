<?php
if (!class_exists("CBSoapClient")) {
	class CBSoapClient extends SoapClient {
	    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
	        $xmlRequest = new DOMDocument("1.0");
	        $xmlRequest->loadXML($request);
	        $header = $xmlRequest->createElement("SOAP-ENV:Header");
	        if (defined("OMIE_APP_KEY")) { $header->appendChild( $xmlRequest->createElement("app_key", OMIE_APP_KEY) ); }
	        if (defined("OMIE_APP_SECRET")) { $header->appendChild( $xmlRequest->createElement("app_secret", OMIE_APP_SECRET) ); }
	        if (defined("OMIE_USER_LOGIN")) { $header->appendChild( $xmlRequest->createElement("user_login", OMIE_USER_LOGIN) ); }
	        if (defined("OMIE_USER_PASSWORD")) { $header->appendChild( $xmlRequest->createElement("user_password", OMIE_USER_PASSWORD) ); }
	        $envelope = $xmlRequest->firstChild;
	        $envelope->insertBefore($header, $envelope->firstChild);
	        $request = $xmlRequest->saveXML();
	        return parent::__doRequest($request, $location, $action, $version, $one_way);
	    }
	}
}
/**
 * @service CFOPSoapClient
 * @author omie
 */
class CFOPSoapClient {
	/**
	 * The WSDL URI
	 *
	 * @var string
	 */
	public static $_WsdlUri='http://app.omie.com.br/api/v1/produtos/cfop/?WSDL';
	/**
	 * The PHP SoapClient object
	 *
	 * @var object
	 */
	public static $_Server=null;

	/**
	 * Send a SOAP request to the server
	 *
	 * @param string $method The method name
	 * @param array $param The parameters
	 * @return mixed The server response
	 */
	public static function _Call($method,$param){
		if(is_null(self::$_Server))
			self::$_Server=new CBSoapClient(self::$_WsdlUri);
		return self::$_Server->__soapCall($method,$param);
	}

	/**
	 * Listar as CFOPs
	 *
	 * @param cfopListarRequest $cfopListarRequest Solicitação da listagem de CFOPs.
	 * @return cfopListarResponse Resposta da solicitação da listagem de CFOPs.
	 */
	public function ListarCFOP($cfopListarRequest){
		return self::_Call('ListarCFOP',Array(
			$cfopListarRequest
		));
	}
}

/**
 * Solicitação da listagem de CFOPs.
 *
 * @pw_element integer $pagina Número da página retornada
 * @pw_element integer $registros_por_pagina Número de registros retornados na página.
 * @pw_element string $ordenar_por Ordem de exibição dos dados. Padrão: Código.
 * @pw_element string $ordem_decrescente Se a lista será apresentada em ordem decrescente.
 * @pw_complex cfopListarRequest
 */
class cfopListarRequest{
	/**
	 * Número da página retornada
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Número de registros retornados na página.
	 *
	 * @var integer
	 */
	public $registros_por_pagina;
	/**
	 * Ordem de exibição dos dados. Padrão: Código.
	 *
	 * @var string
	 */
	public $ordenar_por;
	/**
	 * Se a lista será apresentada em ordem decrescente.
	 *
	 * @var string
	 */
	public $ordem_decrescente;
}

/**
 * Resposta da solicitação da listagem de CFOPs.
 *
 * @pw_element integer $pagina Número da página retornada
 * @pw_element integer $total_de_paginas Número total de páginas
 * @pw_element integer $registros Número de registros retornados na página.
 * @pw_element integer $total_de_registros total de registros encontrados
 * @pw_element cadastrosArray $cadastros Lista os CFOPs encontrados.
 * @pw_complex cfopListarResponse
 */
class cfopListarResponse{
	/**
	 * Número da página retornada
	 *
	 * @var integer
	 */
	public $pagina;
	/**
	 * Número total de páginas
	 *
	 * @var integer
	 */
	public $total_de_paginas;
	/**
	 * Número de registros retornados na página.
	 *
	 * @var integer
	 */
	public $registros;
	/**
	 * total de registros encontrados
	 *
	 * @var integer
	 */
	public $total_de_registros;
	/**
	 * Lista os CFOPs encontrados.
	 *
	 * @var cadastrosArray
	 */
	public $cadastros;
}

/**
 * Lista os CFOPs encontrados.
 *
 * @pw_element string $nCodigo Código do CFOP.
 * @pw_element string $cDescricao Descrição do CFOP.
 * @pw_element string $cObservacao Observação do CFOP.
 * @pw_element string $cTipo Tipo do CFOP.<BR>E-Entrada / S-Saída
 * @pw_complex cadastros
 */
class cadastros{
	/**
	 * Código do CFOP.
	 *
	 * @var string
	 */
	public $nCodigo;
	/**
	 * Descrição do CFOP.
	 *
	 * @var string
	 */
	public $cDescricao;
	/**
	 * Observação do CFOP.
	 *
	 * @var string
	 */
	public $cObservacao;
	/**
	 * Tipo do CFOP.<BR>E-Entrada / S-Saída
	 *
	 * @var string
	 */
	public $cTipo;
}


/**
 * Erro gerado pela aplicação.
 *
 * @pw_element integer $code Codigo do erro
 * @pw_element string $description Descricao do erro
 * @pw_element string $referer Origem do erro
 * @pw_element boolean $fatal Indica se eh um erro fatal
 * @pw_complex omie_fail
 */
if (!class_exists('omie_fail')) {
class omie_fail{
	/**
	 * Codigo do erro
	 *
	 * @var integer
	 */
	public $code;
	/**
	 * Descricao do erro
	 *
	 * @var string
	 */
	public $description;
	/**
	 * Origem do erro
	 *
	 * @var string
	 */
	public $referer;
	/**
	 * Indica se eh um erro fatal
	 *
	 * @var boolean
	 */
	public $fatal;
}
}