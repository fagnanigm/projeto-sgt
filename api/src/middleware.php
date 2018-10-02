<?php
// Application middleware

use Slim\Middleware\TokenAuthentication;
use \Firebase\JWT\JWT;
use Classes\User;

$authenticator = function($request, TokenAuthentication $tokenAuth){

	global $app;

	$c = $app->getContainer();

	$token = $tokenAuth->findToken($request);

	try{

		$token_decoded = JWT::decode($token, AUTHENTICATE_KEY, array('HS256'));

		$user = new User($c['db']);
		$data = $user->get_by_id($token_decoded->sub);

		if($data['result']){
			$c['user_request'] = $data['user'];
		}

		return ($data['result'] ? true : false);	

	}catch (Exception $e) {
	    echo 'Exceção capturada: ',  $e->getMessage(), "\n";
	    return false;
	}
    
};

$app->add(new TokenAuthentication([
    'path' => array( // Bloqueados
    	'/users',
    	'/veiculos',
    	'/projetos',
    	'/motoristas',
    	'/localidades',
    	'/locais',
    	'/empresas',
    	'/cotacoes',
    	'/configuracoes',
    	'/clientes',
    	'/categorias',
    	'/as',
        '/vendedores',
        '/formas-pagamento',
        '/autorizacao-servico-prazo-pg'
    ),
    'passthrough' => array( // Permitidos
    	'/users/login'
    ),
    'authenticator' => $authenticator,
    'secure' => false
]));
