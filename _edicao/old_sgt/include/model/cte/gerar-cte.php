<?php 

date_default_timezone_set("Brazil/East");

$dt = new DateTime();

include("../../../app.php");

$codigo_cte = $_POST['code'];

$query = "SELECT * FROM sgt_cte WHERE cod_cte = '".$codigo_cte."';";
$ans = sqlsrv_query($connection, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count = sqlsrv_num_rows($ans);

if($count > 0){

    echo json_encode(array(
        'retorno' => array(
            'CStat' => '0',
            'XMotivo' => 'Número de CTE já utilizado.'
        )
    ));

    die();

}



// Gera INI FILE

$ini_content = 'CTE.CRIARENVIARCTE("'.PHP_EOL;

## Config
$ini_content .= '[infCte]'.PHP_EOL;
$ini_content .= 'Versao=3.0'.PHP_EOL;
$ini_content .= 'Id=41'.PHP_EOL;

$ini_content .= PHP_EOL;

## Identificação do CTE
$ini_content .= '[ide]'.PHP_EOL;

// Fixos
$ini_content .= 'cUF=35'.PHP_EOL;
$ini_content .= 'cCT=84694796'.PHP_EOL;
$ini_content .= 'mod=57'.PHP_EOL;
$ini_content .= 'serie=0'.PHP_EOL;
$ini_content .= 'tplmp=1'.PHP_EOL;
$ini_content .= 'tpEmis=1'.PHP_EOL;
$ini_content .= 'cDV=1'.PHP_EOL;
$ini_content .= 'tpAmb=2'.PHP_EOL;
$ini_content .= 'tpCTe=0'.PHP_EOL;
$ini_content .= 'verproc=000000001'.PHP_EOL;
$ini_content .= 'indGlobalizado=0'.PHP_EOL;
$ini_content .= 'modal=01'.PHP_EOL;
$ini_content .= 'tpServ=0'.PHP_EOL;
$ini_content .= 'indIEToma=1'.PHP_EOL;
$ini_content .= 'cMunEnv=3501608'.PHP_EOL;
$ini_content .= 'xMunEnv=AMERICANA'.PHP_EOL;                              
$ini_content .= 'UFEnv=SP'.PHP_EOL;
$ini_content .= 'nCT='.$codigo_cte.PHP_EOL;
$ini_content .= 'dhEmi='.$dt->format('d/m/Y').PHP_EOL;

// Variável 
$ini_content .= 'CFOP=5353'.PHP_EOL;
$ini_content .= 'natOp=1'.PHP_EOL;

$ini_content .= 'cMunini='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Origem_Code'].PHP_EOL;
$ini_content .= 'xMunini='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Origem'].PHP_EOL; 
$ini_content .= 'UFini='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Origem_UF'].PHP_EOL;

$ini_content .= 'cMunFim='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Destino_Code'].PHP_EOL;
$ini_content .= 'xMunFim='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Destino'].PHP_EOL;
$ini_content .= 'UFFim='.$_POST['cte']['informacoes_gerais']['Geral_Cidade_Destino_UF'].PHP_EOL;

$ini_content .= PHP_EOL;

## Tomador
$ini_content .= '[toma4]'.PHP_EOL;
$ini_content .= 'toma=4'.PHP_EOL;
$ini_content .= 'CNPJ='.$_POST['cte']['informacoes_gerais']['tomador']['cnpj'].PHP_EOL;
$ini_content .= 'IE='.$_POST['cte']['informacoes_gerais']['tomador']['ie'].PHP_EOL;
//$ini_content .= 'xNome=B-PROJECTS TRANSPORTES NACIONAIS E INTERN.LTDA.'.PHP_EOL;
//$ini_content .= 'xFant=B-PROJECTS TRANSPORTES NACIONAIS E INTERN.LTDA.'.PHP_EOL;

$ini_content .= PHP_EOL;

$ini_content .= '[enderToma]'.PHP_EOL;
$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['tomador']['logradouro'].PHP_EOL;
$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['tomador']['numero'].PHP_EOL;
$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['tomador']['bairro'].PHP_EOL;
$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['tomador']['codigo_cidade'].PHP_EOL;
$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['tomador']['cidade'].PHP_EOL;
$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['tomador']['cep'].PHP_EOL;
$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['tomador']['estado'].PHP_EOL;
$ini_content .= 'cPais='.$_POST['cte']['informacoes_gerais']['tomador']['codigo_pais'].PHP_EOL;
$ini_content .= 'xPais='.$_POST['cte']['informacoes_gerais']['tomador']['pais'].PHP_EOL;

$ini_content .= PHP_EOL;

## Emitente - Fixo
$ini_content .= '[emit]'.PHP_EOL;
$ini_content .= 'IE=110817205116'.PHP_EOL;
$ini_content .= 'CNPJ=47698881000120'.PHP_EOL;
$ini_content .= 'xNome=TRANSPORTADORA CRUZ DE MALTA LTDA'.PHP_EOL;
$ini_content .= 'xFant=TRANSPORTADORA CRUZ DE MALTA LTDA'.PHP_EOL;
$ini_content .= 'xLgr=Rua do Emitente'.PHP_EOL;
$ini_content .= 'nro=31'.PHP_EOL;
$ini_content .= 'xCpl=Complemento'.PHP_EOL;
$ini_content .= 'xBairro=PARQUE NOVO MUNDO'.PHP_EOL;
$ini_content .= 'cMun=3501608'.PHP_EOL;
$ini_content .= 'xMun=AMERICANA'.PHP_EOL;
$ini_content .= 'CEP=13467444'.PHP_EOL;
$ini_content .= 'UF=SP'.PHP_EOL;
$ini_content .= 'fone=34051234'.PHP_EOL;

$ini_content .= PHP_EOL;


## Remetente
$ini_content .= '[rem]'.PHP_EOL;
$ini_content .= 'CNPJCPF='.$_POST['cte']['informacoes_gerais']['remetente']['cnpj'].PHP_EOL;
$ini_content .= 'IE='.$_POST['cte']['informacoes_gerais']['remetente']['ie'].PHP_EOL;
$ini_content .= 'xNome='.$_POST['cte']['informacoes_gerais']['remetente']['nome'].PHP_EOL;
$ini_content .= 'xFant='.$_POST['cte']['informacoes_gerais']['remetente']['nome'].PHP_EOL;
$ini_content .= 'fone=993115474'.PHP_EOL;
$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['remetente']['logradouro'].PHP_EOL;
$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['remetente']['numero'].PHP_EOL;
$ini_content .= 'xCpl='.PHP_EOL;
$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['remetente']['bairro'].PHP_EOL;
$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['remetente']['codigo_cidade'].PHP_EOL;
$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['remetente']['cidade'].PHP_EOL;
$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['remetente']['cep'].PHP_EOL;
$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['remetente']['estado'].PHP_EOL;
$ini_content .= 'PaisCod='.$_POST['cte']['informacoes_gerais']['remetente']['codigo_pais'].PHP_EOL;
$ini_content .= 'Pais='.$_POST['cte']['informacoes_gerais']['remetente']['pais'].PHP_EOL;
$ini_content .= 'Email=fagnanigm@gmail.com'.PHP_EOL;

$ini_content .= PHP_EOL;

## Destinatário 
$ini_content .= '[Dest]'.PHP_EOL;
$ini_content .= 'CNPJCPF='.$_POST['cte']['informacoes_gerais']['destinatario']['cnpj'].PHP_EOL;
$ini_content .= 'xNome='.$_POST['cte']['informacoes_gerais']['destinatario']['nome'].PHP_EOL;
$ini_content .= 'fone='.PHP_EOL;
$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['destinatario']['logradouro'].PHP_EOL;
$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['destinatario']['numero'].PHP_EOL;
$ini_content .= 'xCpl='.PHP_EOL;
$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['destinatario']['bairro'].PHP_EOL;
$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['destinatario']['codigo_cidade'].PHP_EOL;
$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['destinatario']['cidade'].PHP_EOL;
$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['destinatario']['cep'].PHP_EOL;
$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['destinatario']['estado'].PHP_EOL;
$ini_content .= 'cPais='.$_POST['cte']['informacoes_gerais']['destinatario']['codigo_pais'].PHP_EOL;
$ini_content .= 'xPais='.$_POST['cte']['informacoes_gerais']['destinatario']['pais'].PHP_EOL;

$ini_content .= PHP_EOL;


## Carga
$ini_content .= '[infCTeNorm]'.PHP_EOL;

$ini_content .= PHP_EOL;

// Variavel
$ini_content .= '[infCarga]'.PHP_EOL;
$ini_content .= 'vCarga=1000,00'.PHP_EOL;
$ini_content .= 'proPred=Produto de teste'.PHP_EOL;
$ini_content .= 'xOutCat=Carga viva'.PHP_EOL;

$ini_content .= PHP_EOL;

// Varivel
$ini_content .= '[infQ001]'.PHP_EOL;
$ini_content .= 'cUnid=KG'.PHP_EOL;
$ini_content .= 'tpMed=PESO BRUTO'.PHP_EOL;
$ini_content .= 'qCarga=100'.PHP_EOL;

$ini_content .= PHP_EOL;

// Variavel
$ini_content .= '[rodo]'.PHP_EOL;
$ini_content .= 'RNTRC=12345678'.PHP_EOL;

$ini_content .= PHP_EOL;

## Documentos
$ini_content .= '[infDoc]'.PHP_EOL;

$ini_content .= PHP_EOL;

$ini_content .= '[infNFe001]'.PHP_EOL;
$ini_content .= 'chave=35140289237911011690550000000093571642024852'.PHP_EOL;
$ini_content .= 'PIN='.PHP_EOL;
$ini_content .= 'dPrev='.PHP_EOL;

$ini_content .= '")';



//Cria o arquivo que será colocado na pasta teste_CTE para que possa ser visualizado pessoalmente
$file = 'cte-'.date("d-m-Y-H-i-s");
$fileEntrada = fopen('entrada/'.$file.'.ini', 'w');
fwrite($fileEntrada, $ini_content);
fclose($fileEntrada);

copy('entrada/'.$file.'.ini', 'C:/ACBrMonitorPLUS/Entrada/'.$file.'.ini');

sleep(5);

copy('C:/ACBrMonitorPLUS/Saida/'.$file.'-resp.ini', 'saida/'.$file.'-resp.ini');

sleep(5);

$file_response = fopen('saida/'.$file.'-resp.ini','r');

$response = array(
    'post' => $_POST
);

$main_index = 'info';

$is_error = false;

// Lê o conteúdo do arquivo 
while(!feof($file_response)){

    $linha = fgets($file_response, 10240);
    $linha = trim($linha);


    if($is_error == true){

        $tag = str_replace(array('{','}'), array('',''),  trim(strip_tags(utf8_encode($linha))));

        if(strlen($tag) > 0){

            $response['retorno']['XMotivo'][] = $tag;

        }

        continue; 
    }


    if(preg_match('/ERRO/', $linha)){

        $response['retorno'] = array(
            'CStat' => '0',
            'XMotivo' => array()
        );

        $is_error = true;

        continue;

    }else{

        if($linha == '[ENVIO]' || $linha == '[RETORNO]' || $linha == '[CTE'.$codigo_cte.']'){

            if($linha == '[CTE'.$codigo_cte.']'){
                $linha = 'cte';
            }

            $main_index = str_replace(array('[',']'), array('',''), strtolower($linha));
            $response[$main_index] = array();

        }else{

            if(strlen($linha) > 0){

                if(preg_match('/=/', $linha)){

                    $linha = explode('=', $linha);

                    $response[$main_index][$linha[0]] = $linha[1];

                }else{
                    $response[$main_index][] = $linha;
                }

            }

        }


    }
}

fclose($file_response);

if(isset($response['cte'])){

    if($response['cte']['CStat'] == '100'){
        $query = "UPDATE sgt_cte SET return_acbr = '".json_encode($response)."', cod_cte = '".$codigo_cte."' WHERE id = '".$_POST['id']."';";  
        $ans = sqlsrv_query($connection, $query, array()) or die (mssql_get_last_message());
    }

}

echo json_encode($response);

?>
