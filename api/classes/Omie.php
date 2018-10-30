<?php

namespace Classes;

class Omie {

	private $app_secret = false;
	private $app_key = false;

	public function incluirOS($args = false){

		$response = array(
			'result' => false
		);

		$data1 = json_encode(array(
			'call' => 'IncluirOS',
			'app_key' => '1560731700',
			'app_secret' => '226dcf372489bb45ceede61bfd98f0f1',
			'param' => array(
				array(
					'Cabecalho' => array(
						"cCodIntOS" => time(), 			// Código de integração da Ordem de Serviço.
					    "cEtapa" => "10", 				// Etapa da Ordem de Serviço.
					    "dDtPrevisao" => "29/10/2018", 	// Data de Previsão
					    "nCodCli" => 2485994, 			// Código do Cliente
					    "nQtdeParc" => 7 				// Quantidade de Parcelas 
					),
					'Email' => array(
						"cEnvBoleto" => "N", 	// Enviar ao Cliente e-mail com os Boletos de Cobrança gerados pelo faturamento.
					    "cEnvLink" => "N", 		// Enviar ao Cliente e-mail com o link do site da prefeitura para consultar a NFSe emitida.
					    "cEnviarPara" => "" // Utilizar os seguintes endereços de e-mail
					),
					'InformacoesAdicionais' => array(
						"cCidPrestServ" => "SAO PAULO (SP)", 	// Cidade da Prestação do Serviço
					    "cCodCateg" => "1.01.02", 				// Categoria
					    "cDadosAdicNF" => "OS incluida via API de teste 33039", // 	Dados Adicionais da Nota Fiscal
					    "nCodCC" => 11850365 					// Código da Conta Corrente
					),
					'ServicosPrestados' => array(
						array(
							"cCodServLC116" => "7.07", 					// Código do Serviço LC 116
							"cCodServMun" => "01015", 					// Código do Serviço Municipal
							"cDadosAdicItem" => "Serviços prestados", 	// Dados adicionais do Item
							"cDescServ" => "Serviço prestado 001", 		// Descrição dos Serviços
							"cRetemISS" => "N", 						// Retem ISS (S/N)
							"cTribServ" => "01", 						// Tipo de Tributação dos Serviços
							"impostos" => array(
								"cFixarCOFINS" => "", 	// Considerar a alíquota de COFINS Confirmada (S/N)?
								"cFixarCSLL" => "", 	// Considerar a alíquota de CSLL Confirmada (S/N)?
								"cFixarIRRF" => "", 	// Retém IRRF (S/N)
								"cFixarISS" => "", 		// Considerar a alíquota de ISS informada (S/N)?
								"cFixarPIS" => "", 		// Considerar a alíquota de PIS informado (S/N)?
								"nAliqCOFINS" => 0, 	// Alíquota de COFINS
								"nAliqCSLL" => 0, 		// Alíquota de CSLL
								"nAliqIRRF" => 0, 		// Alíquota de IRRF
								"nAliqISS" => 3, 		// Alíquota de ISS
								"nAliqPIS" => 0,		// Alíquota de PIS
								"nBaseISS" => 100,		// Base de ISS
								"nTotDeducao" => 0,		// Total de Deduções
								"nValorCOFINS" => 0,	// Valor do COFINS
								"nValorCSLL" => 0,		// Valor de CSLL
								"nValorIRRF" => 0,		// Valor do IRRF
								"nValorISS" => 3,		// Valor do ISS
								"nValorPIS" => 0		// Valor do PIS
							),
							"nQtde" => 3, 		// Quantidade
							"nValUnit" => 1000 	// Valor Unitário
						)
					)
				)
			)
		));

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://app.omie.com.br/api/v1/servicos/os/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $data1,
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "Postman-Token: f4b7d9dc-3b94-43a0-a04a-14a147200d9a",
		    "cache-control: no-cache"
		  ),
		));

		$return = curl_exec($curl);
		$return = json_decode($return,true);
		$err = curl_error($curl);

		curl_close($curl);

		$return['result'] = (isset($return['nCodOS']) ? true : false);

		$response['response'] = $return;

		return $response;

	}

}

?>