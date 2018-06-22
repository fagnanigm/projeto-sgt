<?php  

    Gerar_ini_CTE(41);

    function Gerar_ini_CTE($CodigoCTE){

        include_once("/Conecta_DB.php");
        include_once("../Classes/Classe_CTE_iniFile.php");

        /* Fazendo Conexão com o banco */
        $connection = ConnectDB();  

        /* ---------------------------------------------------------- */
        /*                                                            */
        /*        Montando as Querys e Retornando a Pesquisa          */
        /*                                                            */
        /* ---------------------------------------------------------- */


        $queryCTE           = "select SUBSTRING(cast(munOrigem.cd_municipio_concla as varchar), 1, 2) as Codigo_Estado_Origem, 
        munOrigem.estado as Estado_Origem, munOrigem.nome as Municipio_Origem, 
        munDestino.estado as Estado_Destino, munDestino.nome as Municipio_Destino, cte.* 
        from SGT_Conhecimento_Transporte_Eletronico cte
            left join MunicipioG munOrigem
                on cte.Geral_Cidade_Origem_Codigo_IBGE = munOrigem.cd_municipio_concla
            left join MunicipioG munDestino
                on cte.Geral_Cidade_Destino_Codigo_IBGE = munDestino.cd_municipio_concla
            where Codigo_CTE = $CodigoCTE";

        $queryCargas        = "select * from SGT_Conhecimento_Transporte_Eletronico_Cargas where Codigo_CTE = $CodigoCTE";
        $queryDoc           = "select * from SGT_Conhecimento_Transporte_Eletronico_Documentos where Codigo_CTE = $CodigoCTE";
        $queryDocTA         = "select * from SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior where Codigo_CTE = $CodigoCTE";
        $queryDocTAP        = "select * from SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel where Codigo_CTE = $CodigoCTE";
        $queryDocTAE        = "select * from SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico where Codigo_CTE = $CodigoCTE";
        $queryPrestacoes    = "select * from SGT_Conhecimento_Transporte_Eletronico_Prestacoes where Codigo_CTE = $CodigoCTE";
        $querySeguros       = "select * from SGT_Conhecimento_Transporte_Eletronico_Seguros where Codigo_CTE = $CodigoCTE";
        $queryMotorista     = "select Mot.* from SGT_Conhecimento_Transporte_Eletronico_Motoristas CTE left join SGT_Motoristas Mot on CTE.Codigo_Motorista = Mot.Codigo_Motorista where CTE.Codigo_CTE = $CodigoCTE";       
        $queryVeiculos      = "select Vei.* from SGT_Conhecimento_Transporte_Eletronico_Veiculos CTE left join SGT_Veiculos Vei on CTE.Codigo_Veiculo = Vei.Codigo_Veiculo where CTE.Codigo_CTE = $CodigoCTE";      
        
        $ansCTE = array();
        $ansCTE[0] = sqlsrv_query($connection, $queryCTE,          array()) or die (mssql_get_last_message());
        $ansCTE[1] = sqlsrv_query($connection, $queryCargas,       array()) or die (mssql_get_last_message());
        $ansCTE[2] = sqlsrv_query($connection, $queryDoc,          array()) or die (mssql_get_last_message());
        $ansCTE[3] = sqlsrv_query($connection, $queryDocTA,        array()) or die (mssql_get_last_message());
        $ansCTE[4] = sqlsrv_query($connection, $queryDocTAP,       array()) or die (mssql_get_last_message());
        $ansCTE[5] = sqlsrv_query($connection, $queryDocTAE,       array()) or die (mssql_get_last_message());
        $ansCTE[6] = sqlsrv_query($connection, $queryPrestacoes,   array()) or die (mssql_get_last_message());
        $ansCTE[7] = sqlsrv_query($connection, $querySeguros,      array()) or die (mssql_get_last_message());
        $ansCTE[8] = sqlsrv_query($connection, $queryMotorista,    array()) or die (mssql_get_last_message());
        $ansCTE[9] = sqlsrv_query($connection, $queryVeiculos,     array()) or die (mssql_get_last_message());

        /*foreach ($ansCTE as &$Tabela) {
            while($row = sqlsrv_fetch_array($Tabela, SQLSRV_FETCH_ASSOC)){                
                var_dump($row);
            }    
        }     */

        /* ---------------------------------------------------------- */
        /*                                                            */
        /*   Atribuindo as Classes aos Valores da Classe Principal    */
        /*                                                            */
        /* ---------------------------------------------------------- */

        $iniCTE = new CTE_ini();                                $iniCTE->infCte = new ini_infCte();
        $iniCTE->ide = new ini_ide();                           $iniCTE->toma3 = new ini_toma3();
        $iniCTE->toma4 = new ini_toma4();                       $iniCTE->enderToma = new ini_enderToma();
        $iniCTE->compl = new ini_compl();                       $iniCTE->fluxo = new ini_fluxo();
        $iniCTE->pass = new ini_pass();                         $iniCTE->Entrega = new ini_Entrega();
        $iniCTE->semData = new ini_semData();                   $iniCTE->comData = new ini_comData();
        $iniCTE->noPeriodo = new ini_noPeriodo();               $iniCTE->semHora = new ini_semHora();
        $iniCTE->comHora = new ini_comHora();                   $iniCTE->noInter = new ini_noInter();
        $iniCTE->ObsCont = new ini_ObsCont();                   $iniCTE->ObsFisco = new ini_ObsFisco();
        $iniCTE->emit = new ini_emit();                         $iniCTE->enderEmit = new ini_enderEmit();
        $iniCTE->rem = new ini_rem();                           $iniCTE->enderReme = new ini_enderReme();
        $iniCTE->exped = new ini_exped();                       $iniCTE->enderExped = new ini_enderExped();
        $iniCTE->receb = new ini_receb();                       $iniCTE->enderReceb = new ini_enderReceb();
        $iniCTE->dest = new ini_dest();                         $iniCTE->enderDest = new ini_enderDest();
        $iniCTE->vPrest = new ini_vPrest();                     $iniCTE->Comp = new ini_Comp();
        $iniCTE->imp = new ini_imp();                           $iniCTE->ICMS = new ini_ICMS();
        $iniCTE->ICMS00 = new ini_ICMS00();                     $iniCTE->ICMS20 = new ini_ICMS20();
        $iniCTE->ICMS45 = new ini_ICMS45();                     $iniCTE->ICMS60 = new ini_ICMS60();
        $iniCTE->icms90 = new ini_icms90();                     $iniCTE->ICMSOutraUF = new ini_ICMSOutraUF();
        $iniCTE->ICMSSN = new ini_ICMSSN();                     $iniCTE->ICMSUFFim = new ini_ICMSUFFim();
        $iniCTE->infCTeNorm = new ini_infCTeNorm();             $iniCTE->infCarga = new ini_infCarga();
        $iniCTE->infQ = new ini_infQ();                         $iniCTE->infDoc = new ini_infDoc();
        $iniCTE->infNF = new ini_infNF();                       $iniCTE->infUnidCarga = new ini_infUnidCarga();
        $iniCTE->lacUnidCarga = new ini_lacUnidCarga();         $iniCTE->infUnidTransp = new ini_infUnidTransp();
        $iniCTE->lacUnidTransp = new ini_lacUnidTransp();       $iniCTE->infNFe = new ini_infNFe();
        $iniCTE->infOutros = new ini_infOutros();               $iniCTE->docAnt = new ini_docAnt();
        $iniCTE->emiDocAnt = new ini_emiDocAnt();               $iniCTE->idDocAnt = new ini_idDocAnt();
        $iniCTE->idDocAntPap = new ini_idDocAntPap();           $iniCTE->idDocAntEle = new ini_idDocAntEle();
        $iniCTE->infModal = new ini_infModal();                 $iniCTE->veicNovos = new ini_veicNovos();
        $iniCTE->cobr = new ini_cobr();                         $iniCTE->fat = new ini_fat();
        $iniCTE->dup = new ini_dup();                           $iniCTE->infCteSub = new ini_infCteSub();
        $iniCTE->tomaICMS = new ini_tomaICMS();                 $iniCTE->refNF = new ini_refNF();
        $iniCTE->infGlobalizado = new ini_infGlobalizado();     $iniCTE->infServVinc = new ini_infServVinc();
        $iniCTE->infCTeMultimodal = new ini_infCTeMultimodal(); $iniCTE->infCteComp = new ini_infCteComp();
        $iniCTE->infCteAnu = new ini_infCteAnu();               $iniCTE->autXML = new ini_autXML();  
        

        /* ---------------------------------------------------------- */
        /*                                                            */
        /*              Atribuindo Valores as Classes                 */
        /*                                                            */
        /* ---------------------------------------------------------- */

        while($row = sqlsrv_fetch_array($ansCTE[0], SQLSRV_FETCH_ASSOC)){                
            $iniCTE->infCte->Versao .= '3.0';
            $iniCTE->infCte->Id .= $row['Codigo_CTE'];

            $iniCTE->ide->cUF .= $row['Codigo_Estado_Origem'];      //Estado de origem do CTE, arrumar
            $iniCTE->ide->cCT .= mt_rand(10000000 ,99999999);       //Chave Randomica de 8 Digitos
            $iniCTE->ide->CFOP .= $row['Geral_CFOP'];
            $iniCTE->ide->natOp .= $row['Geral_Codigo_Natureza'];   //Dar join para pegar a descricao
            $iniCTE->ide->mod .= '57';
            $iniCTE->ide->serie .= '0';
            $iniCTE->ide->nCT .= '1';
            $iniCTE->ide->dhEmi .= $row['Geral_Data_Emissao']->format('d/m/Y');      //Formatar para AAAA-MM-DDTHH:MM:DD TZD
            $iniCTE->ide->tpImp .= '1';
            $iniCTE->ide->tpEmis .= '1';
            $iniCTE->ide->cDV .= '1';                               //Calcular
            $iniCTE->ide->tpAmb .= '2';                             //1 = Producao, 2 = Homologação
            $iniCTE->ide->tpCTe .= '0';                             //0 = Normal, 1 = Complementar, 2 = Anulação, 3 = Substituição
            $iniCTE->ide->verProc .= '000000001'; 
            $iniCTE->ide->indGlobalizado .= '0'; 
            $iniCTE->ide->cMunEnv .= $row['Geral_Cidade_Origem_Codigo_IBGE'];   //Arrumar (cidade de Emissao do documento)
            $iniCTE->ide->xMunEnv .= $row['Municipio_Origem'];                  //Arrumar (cidade de Emissao do documento)
            $iniCTE->ide->UFEnv .= $row['Estado_Origem'];                       //Arrumar (UF de Emissao do documento)
            $iniCTE->ide->modal .= '04';                            //Fazer Relacao com RNTC do banco, ou criar o campo
            $iniCTE->ide->tpServ .= $row['Geral_Tipo_Servico']; 
            $iniCTE->ide->cMunIni .= $row['Geral_Cidade_Origem_Codigo_IBGE'];
            $iniCTE->ide->xMunIni .= $row['Municipio_Origem'];
            $iniCTE->ide->UFIni .= $row['Estado_Origem'];
            $iniCTE->ide->cMunFim .= $row['Geral_Cidade_Destino_Codigo_IBGE'];
            $iniCTE->ide->xMunFim .= $row['Municipio_Destino'];
            $iniCTE->ide->UFFim .= $row['Estado_Destino'];
            $iniCTE->ide->retira .= '0';
            $iniCTE->ide->xDetRetira .= '';
            $iniCTE->ide->indIEToma .= '1';

            $iniCTE->emit->CNPJ .= '17120482000116';
            $iniCTE->emit->IE .= '00';
            $iniCTE->emit->IEST .= '00';
            $iniCTE->emit->xNome .= 'Razão Social do Emitente';
            $iniCTE->emit->xFant .= 'Nome Fantasia do Emitente';

            $iniCTE->enderEmit->xLgr .= 'Rua do Emitente';
            $iniCTE->enderEmit->nro .= '31';
            $iniCTE->enderEmit->xCpl .= 'Complemento';
            $iniCTE->enderEmit->xBairro .= 'Bairro do Emitente';
            $iniCTE->enderEmit->cMun .= $row['Geral_Cidade_Origem_Codigo_IBGE'];
            $iniCTE->enderEmit->xMun .= $row['Municipio_Origem'];
            $iniCTE->enderEmit->CEP .= '13467444';
            $iniCTE->enderEmit->UF .= 'RO';
            $iniCTE->enderEmit->fone .= '34051234';

        }

        /* ---------------------------------------------------------- */
        /*                                                            */
        /*          Atribuindo as Classes ao Arquivo .ini             */
        /*                                                            */
        /* ---------------------------------------------------------- */

        $conteudo = 'CTE.CRIARENVIARCTE("'.PHP_EOL;
        //infCTE
        $conteudo .= $iniCTE->infCte->Header.PHP_EOL;
        $conteudo .= $iniCTE->infCte->Versao.PHP_EOL;
        $conteudo .= $iniCTE->infCte->Id.PHP_EOL;
        //ide
        $conteudo .= PHP_EOL.$iniCTE->ide->Header.PHP_EOL;
        $conteudo .= $iniCTE->ide->cUF.PHP_EOL;
        $conteudo .= $iniCTE->ide->cCT.PHP_EOL;
        $conteudo .= $iniCTE->ide->CFOP.PHP_EOL;
        $conteudo .= $iniCTE->ide->natOp.PHP_EOL;
        $conteudo .= $iniCTE->ide->mod.PHP_EOL;
        $conteudo .= $iniCTE->ide->serie.PHP_EOL;
        $conteudo .= $iniCTE->ide->nCT.PHP_EOL;
        $conteudo .= $iniCTE->ide->dhEmi.PHP_EOL;
        $conteudo .= $iniCTE->ide->tpImp.PHP_EOL;
        $conteudo .= $iniCTE->ide->tpEmis.PHP_EOL;
        $conteudo .= $iniCTE->ide->cDV.PHP_EOL;
        $conteudo .= $iniCTE->ide->tpAmb.PHP_EOL;
        $conteudo .= $iniCTE->ide->tpCTe.PHP_EOL;
        $conteudo .= $iniCTE->ide->verProc.PHP_EOL;
        $conteudo .= $iniCTE->ide->indGlobalizado.PHP_EOL;
        $conteudo .= $iniCTE->ide->cMunEnv.PHP_EOL;
        $conteudo .= $iniCTE->ide->xMunEnv.PHP_EOL;
        $conteudo .= $iniCTE->ide->UFEnv.PHP_EOL;
        $conteudo .= $iniCTE->ide->modal.PHP_EOL;
        $conteudo .= $iniCTE->ide->tpServ.PHP_EOL;
        $conteudo .= $iniCTE->ide->cMunIni.PHP_EOL;
        $conteudo .= $iniCTE->ide->xMunIni.PHP_EOL;
        $conteudo .= $iniCTE->ide->UFIni.PHP_EOL;
        $conteudo .= $iniCTE->ide->cMunFim.PHP_EOL;
        $conteudo .= $iniCTE->ide->xMunFim.PHP_EOL;
        $conteudo .= $iniCTE->ide->UFFim.PHP_EOL;
        $conteudo .= $iniCTE->ide->indIEToma.PHP_EOL;

        $conteudo .= PHP_EOL.$iniCTE->emit->Header.PHP_EOL;
        $conteudo .= $iniCTE->emit->IE.PHP_EOL;
        $conteudo .= $iniCTE->emit->IEST.PHP_EOL;
        $conteudo .= $iniCTE->emit->CNPJ.PHP_EOL;
        $conteudo .= $iniCTE->emit->xNome.PHP_EOL;
        $conteudo .= $iniCTE->emit->xFant.PHP_EOL;

        $conteudo .= $iniCTE->enderEmit->xLgr.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->nro.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->xCpl.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->xBairro.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->cMun.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->xMun.PHP_EOL;        
        $conteudo .= $iniCTE->enderEmit->CEP.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->UF.PHP_EOL;
        $conteudo .= $iniCTE->enderEmit->fone.PHP_EOL;
        
        $conteudo.='")';        

        /* ---------------------------------------------------------- */
        /*                                                            */
        /*              Trabalhando com o arquivo .ini                */
        /*                                                            */
        /* ---------------------------------------------------------- */

        //deleta o arquivo na pasta Saida
        unlink(realpath('C:/ACBrMonitorPLUS/Saida/CTE_Teste-resp.ini'));

        //Cria o arquivo que será colocado na pasta teste_CTE para que possa ser visualizado pessoalmente
        $arquivoEntrada = '/ACBrMonitorPLUS/teste_CTE/CTE_Teste.ini';
        $fileEntrada = fopen($arquivoEntrada, 'w');
        fwrite($fileEntrada, $conteudo);
        fclose($fileEntrada);

        //Cria o arquivo que será colocado na pasta Entrada
        $arquivoEntrada = '/ACBrMonitorPLUS/Entrada/CTE_Teste.ini';
        $fileEntrada = fopen($arquivoEntrada, 'w');
        fwrite($fileEntrada, $conteudo);
        fclose($fileEntrada);

        //Le o arquivo que foi colocado na pasta Entrada
        $fileEntrada = fopen($arquivoEntrada, 'r');
        while(!feof($fileEntrada)) { echo fgets($fileEntrada) . "<br>"; }
        fclose($fileEntrada);

        sleep(1);
        echo '<br /><hr /><br />';

        //Le o arquivo que foi gerado na pasta Saida
        $arquivoSaida = '/ACBrMonitorPLUS/Saida/CTE_Teste-resp.ini';
        $fileSaida = fopen($arquivoSaida, 'r');
        while(!feof($fileSaida)) { echo str_replace("1871", "<br /><br />1871", str_replace("TAG:", "<br />TAG <", str_replace("<infCTeNorm><", "", fgets($fileSaida)))); }
        fclose($fileSaida);        
    }

?>