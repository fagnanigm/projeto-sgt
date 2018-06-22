<?php

class CTE_ini { //Classe principal
    
    public $infCte;
    public $ide;
    public $toma3;
    public $toma4;
    public $enderToma;
    public $compl;
    public $fluxo;
    public $pass;
    public $Entrega;
    public $semData;
    public $comData;
    public $noPeriodo;
    public $semHora;
    public $comHora;
    public $noInter;
    public $ObsCont;
    public $ObsFisco;
    public $emit;
    public $ederEmit;
    public $rem;
    public $enderReme;
    public $exped;
    public $enderExped;
    public $receb;
    public $enderReceb;
    public $dest;
    public $enderDest;
    public $vPrest;
    public $Comp;
    public $imp;
    public $ICMS;
    public $ICMS00;
    public $ICMS20;
    public $ICMS45;
    public $ICMS60;
    public $icms90;
    public $ICMSOutraUF;
    public $ICMSSN;
    public $ICMSUFFim;
    public $infCTeNorm;
    public $infCarga;
    public $infQ;
    public $infDoc;
    public $infNF;
    public $infUnidCarga;
    public $lacUnidCarga;
    public $infUnidTransp;
    public $lacUnidTransp;
    public $infNFe;
    public $infOutros;
    public $docAnt;
    public $emiDocAnt;
    public $idDocAnt;
    public $idDocAntPap;
    public $idDocAntEle;
    public $infModal;
    public $veicNovos;
    public $cobr;
    public $fat;
    public $dup;
    public $infCteSub;
    public $tomaICMS;
    public $refNF;
    public $infGlobalizado;
    public $infServVinc;
    public $infCTeMultimodal;
    public $infCteComp;
    public $infCteAnu;
    public $autXML;
}

class ini_infCte {          //1-1 - Obrigatório
    public $Header = '[infCte]';
    public $Versao = 'Versao=';
    public $Id = 'Id=';    
}

class ini_ide {             //1-1 - Obrigatório
    public $Header = '[ide]';
    public $cUF = 'cUF=';
    public $cCT = 'cCT=';
    public $CFOP = 'CFOP=';
    public $natOp = 'natOp=';
    public $mod = 'mod=';
    public $serie = 'serie=';
    public $nCT = 'nCT=';
    public $dhEmi = 'dhEmi=';
    public $tpImp = 'tplmp=';
    public $tpEmis = 'tpEmis=';
    public $cDV = 'cDV=';
    public $tpAmb = 'tpAmb=';
    public $tpCTe = 'tpCTe=';
    public $procEmi = 'procEmi=';
    public $verProc = 'verproc=';
    public $indGlobalizado = 'indGlobalizado=';
    public $cMunEnv = 'cMunEnv=';
    public $xMunEnv = 'xMunEnv=';
    public $UFEnv = 'UFEnv=';
    public $modal = 'modal=';
    public $tpServ = 'tpServ=';
    public $cMunIni = 'cMunini=';
    public $xMunIni = 'xMunini=';
    public $UFIni = 'UFini=';
    public $cMunFim = 'cMunFim=';
    public $xMunFim = 'xMunFim=';
    public $UFFim = 'UFFim=';
    public $retira = 'retira=';
    public $xDetRetira = 'xDetRetira=';
    public $indIEToma = 'indIEToma=';
}

class ini_toma3 {           //1-1
    public $Header = '[toma3]';
    public $toma = 'Toma=';
}

class ini_toma4 {           //1-1
    public $Header = '[toma4]';
    public $toma = 'toma=';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $IE = 'IE=';
    public $xNome = 'xNome=';
    public $xFant = 'xFant=';
    public $fone = 'fone=';
}

class ini_enderToma{        //1-1
    public $Header = '[enderToma]';
    public $xLgr = 'xLgr=';
    public $nro = 'nro=';
    public $xCpl = 'xCpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $cPais = 'cPais=';
    public $xPais = 'xPais=';
    public $email = 'email=';    
    public $dhCont = 'dhCont=';
    public $xJust = 'xJust=';
}

class ini_compl{            //0-1
    public $Header = '[comol]';
    public $xCaracAd = 'xCaracAd=';
    public $xCaracSer = 'xCaracSer=';
    public $xEmi = 'xEmi=';

}

class ini_fluxo{            //0-1
    public $Header = '[fluxo]';
    public $xOrig = 'xOrig=';
}

class ini_pass {            //0-n
    public $Header = '[pass]';
    public $xPass = 'xPass=';
    public $xDest = 'xDest=';
    public $xRota = 'xRota=';
}

class ini_Entrega {         //0-1
    public $Header = '[Entrega]';
}

class ini_semData {         //1-1
    public $Header ='[semData]';
    public $tpPer = 'tpPer=';
}

class ini_comData{          //1-1
    public $Header = '[comData]';
    public $tpPer = 'tpPer=';
    public $dProg = 'dProg=';
}

class ini_noPeriodo{        //1-1
    public $Header = '[noPeriodo]';
    public $tpPer = 'tpPer=';
    public $diti = 'dini=';
    public $dFim = 'dFim=';
}

class ini_semHora {         //1-1
    public $Header = '[semHora]';
    public $tpHor = 'tpHor=';
}

class ini_comHora {         //1-1
    public $Header = '[comHora]';
    public $tpHor = 'tpHor=';
    public $hProg = 'hProg=';
}

class ini_noInter {         //1-1
    public $Header = '[noInter]';
    public $hpHor = 'hpHor=';
    public $hini = 'hini=';
    public $hFim = 'hFim=';
    public $origCalc = 'origCalc=';
    public $destCalc = 'destCalc=';
    public $xObs = 'xObs=';

}

class ini_ObsCont {         //0-10
    public $Header = '[ObsCont]';
    public $xCampo = 'xCampo=';
    public $xTexto = 'xTexto=';
}

class ini_ObsFisco {        //0-10
    public $Header = 'ObsFisco';
    public $xCampo = 'xCampo=';
    public $xTexto = 'xTexto=';

}

class ini_emit {            //1-1 - Obrigatório
    public $Header = '[emit]';
    public $CNPJ = 'CNPJ=';
    public $IE = 'IE=';
    public $IEST = 'IEST=';
    public $xNome = 'xNome=';
    public $xFant = 'xFant=';
}

class ini_enderEmit{         //1-1 - Obrigatório
    public $Header = '[enderEmit]';
    public $xLgr = 'xLgr=';
    public $nro = 'nro=';
    public $xCpl = 'xCpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $fone = 'fone=';
}

class ini_rem {             //0-1
    public $Header = '[rem]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $IE = 'IE=';
    public $xNome = 'Xnome=';
    public $xFant = 'xFant=';
    public $fone = 'fone=';
}

class ini_enderReme {        //1-1
    public $Header = '[enderReme]';
    public $xLgr = 'xLgr=';
    public $nro = 'nro=';
    public $xCpl = 'xCpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $cPais = 'cPais=';
    public $xPais = 'xPais=';
    public $email = 'email=';

}
 
class ini_exped {          //0-1
    public $Header = '[exped]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $IE = 'IE=';
    public $xNome = 'xNome=';
    public $fone = 'fone=';
}

class ini_enderExped {      //1-1
    public $Header = '[enderExped]';
    public $xLgr = 'xLgr=';
    public $nro = 'nro=';
    public $xCpl ='xCpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $cPais = 'cPais=';
    public $xPais = 'xPais=';
    public $email = 'email=';

}

class ini_receb {           //0-1
    public $Header = '[receb]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $IE = 'IE=';
    public $xNome = 'xNome=';
    public $fone = 'fone=';
}

class ini_enderReceb{       //1-1
    public $Header = '[enderReceb]';
    public $xLgr = 'xLgr=';
    public $nro = 'nro=';
    public $xCpl = 'xCpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $cPais = 'cPais=';
    public $xPais = 'Xpais=';
    public $emais = 'email=';

}

class ini_dest {             //0-1
    public $Header = '[dest]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'IE=';
    public $IE = 'IE=';
    public $xNome = 'xNome=';
    public $fone = 'fone=';
    public $ISUF = 'ISUF=';
}

class ini_enderDest {       //1-1
    public $Header = '[enderDest]';
    public $xLgr = 'xLgr';
    public $nro = 'nro=';
    public $xCpl = 'xcpl=';
    public $xBairro = 'xBairro=';
    public $cMun = 'cMun=';
    public $xMun = 'xMun=';
    public $CEP = 'CEP=';
    public $UF = 'UF=';
    public $cPais = 'cPais=';
    public $xPais = 'xPais=';
    public $email = 'email=';

}

class ini_vPrest {          //1-1
    public $Header = '[vPrest]';
    public $vTPrest = 'vTPrest=';
    public $vRec = 'vRec=';
}

class ini_comp {            //0-n
    public $Header = '[comp]';
    public $xNome = 'xNome=';
    public $vComp = 'vComp=';   
}

class ini_imp {             //1-1
    public $Header = '[imp]';
}
 
class ini_ICMS {            //1-1
    public $Header = '[ICMS]';
}

class ini_ICMS00 {          //1-1
    public $Header = '[ICMS00]';
    public $CST = 'CST=';
    public $vBC = 'vBC=';
    public $plCMS = 'plCMS=';
    public $vlCMS = 'vlCMS=';

}

class ini_ICMS20 {          //1-1
    public $Header = '[ICMS20]';
    public $CST = 'CST=';
    public $pRedBC = 'pRedBC=';
    public $vBC = 'vBC=';
    public $plCMS = 'plCMS=';
    public $vlCMS = 'vlCMS=';
}

class ini_ICMS45 {          //1-1
    public $Header = '[ICMS45]';
    public $CST = 'CST=';
}

class ini_ICMS60 {           //1-1
    public $Header = '[ICMS60]';
    public $CTS = 'CTS=';
    public $vBCSTRet = 'vBCSTRet=';
    public $vlCMSSTRet = 'vlCMSSTRet=';
    public $plCMSSTRet = 'plCMSSTRet=';
    public $vCred = 'vCred=';
}

class ini_ICMS90 {           //1-1
    public $Header = '[ICMS90]';
    public $CTS = 'CTS=';
    public $pRedBC = 'pRedBC=';
    public $vBC = 'vBC=';
    public $plCMS = 'plCMS=';
    public $vlCMS = 'vlCMS=';
    public $vCred = 'vCred=';
}

class ini_ICMSOutraUF {      //1-1
    public $Header = '[ICMSOutraUF]';
    public $CTS = 'CTS=';
    public $pRedBCOutraUF = 'pRedBCOutraUF=';
    public $vBCOutraUF = 'vBCOutraUF=';
    public $plCMSOutraUF = 'plCMSOutraUF=';
    public $vlCMSOutraUF = 'vlCMSOutraUF=';
}

class ini_ICMSSN {           //1-1
    public $Header = '[ICMSSN]';
    public $CST = 'CST=';
    public $indSN = 'indSN=';
    public $vTotTrip = 'vTotTrip=';
    public $infAdFsico = 'infAdFisco=';
}

class ini_ICMSUFFim {        //1-1
    public $Header = '[ICMSUFFim]';
    public $vBCUFFim = 'vBCUFFim=';
    public $pFCPUFFim  = 'pFCPUFFim=';
    public $pICMSUFFim = 'pICMSUFFim';
    public $pICMSInter = 'pICMSInter=';
    public $pICMSInterPart  = 'pICMSInterPart=';
    public $vFCPUFFim  = 'vFCPUFFim=';
    public $vICMSUFFim = 'vICMSUFFim=';
    public $vICMSUFIni = 'vICMSUFIni=';
    
}

class ini_infCTeNorm {       //1-1
    public $Header = '[infCTeNorm]';
}

class ini_infCarga {         //1-1
    public $Header = '[infCarga]';
    public $vCarga = 'vCarga=';
    public $proPred = 'Propred=';
    public $xOutCat = 'xOutCat=';
}

class ini_infQ {             //1-n
    public $Header = '[infQ]';
    public $cUnid = 'cUnid=';
    public $tpMed = 'tpMed=';
    public $qCarga = 'qCarga=';
    public $vCargaAverb = 'vCargaAverb=';
}
 
class ini_infDoc{            //0-1
    public $Header = '[infDoc]';
}

class ini_infNF {            //1-n
    public $Header = '[infNF]';
    public $nRoma = 'nRoma=';
    public $nPed = 'nPed=';
    public $mod = 'mod=';
    public $serie = 'serie=';
    public $nDoc = 'nDoc=';
    public $dEmi = 'dEmi=';
    public $vBC = 'vBC=';
    public $vICMS = 'vICMS=';
    public $vBCST = 'vBCST=';
    public $vST = 'vST=';
    public $vProd = 'vProd=';
    public $vNF = 'vNF=';
    public $nCFOP = 'nCFOP=';
    public $nPeso = 'nPeso=';
    public $PIN = 'PIN=';
    public $dPrev = 'dPrev=';
}

class ini_infUnidCarga {     //0-n
    public $Header = '[infUnidCarga]';
    public $tpUnidCarga = 'tpUnidCarga=';
    public $idUnidCarga = 'idUnidCarga=';
}

class ini_lacUnidCarga {     //0-n
    public $Header = '[lacUnidCarga]';
    public $nLacre = 'nLacre=';
    public $qtdRat = 'qtdRat=';
}

class ini_infUnidTransp {    //0-n
    public $Header = '[infUnidTransp=]';
    public $tpUnidTransp = 'tpUnidTransp=';
    public $idUnidTransp = 'idUnidTransp=';

}

class ini_lacUnidTransp {    //0-n
    public $Header = '[lacUnidTransp]';
    public $nLacre = 'nLacre=';
}

class ini_infNFe {            //1-n
    public $Header = '[infNFe]';
    public $chave = 'chave=';
    public $PIN = 'PIN=';
    public $dPrev = 'dPrev=';
}

class ini_infOutros {        //1-n
    public $Header = '[infOutros]';
    public $descOutros = 'descOutros=';
    public $nDoc = 'nDoc=';
    public $dEmi = 'dEmi=';
    public $vDocFisc = 'vDocFisc=';
    public $dPrev = 'dPrev=';
}

class ini_docAnt {
    public $Header = '[docAnt]';
}

class ini_emiDocAnt {
    public $Header = '[emiDocAnt]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $IE = 'IE=';
    public $UF = 'UF=';
    public $xNome = 'xNome=';  
}

class ini_idDocAnt {          //0-1
    public $Header = '[idDocAnt]';
}

class ini_idDocAntPap {      //1-n
    public $Header = '[idDocAntPap]';
    public $tpDoc = 'tpDoc=';
    public $serie = 'serie=';
    public $subser =  'subser=';
    public $nDoc = 'nDoc=';
    public $dEmi = 'dEmi=';
}

class ini_idDocAntEle {      //1-n
    public $Header = '[idDocAntEle]';
    public $chCTe = 'chCTe=';  
}

class ini_infModal  {        //1-1
    public $header = '[infModal]';
    public $versaoModal  = 'versaoModal=';
    public $xs_any = 'xs:any=';
}

class ini_veicNovos  {       //0-n
    public $header = '[veicNovos]';
    public $chassi = 'chassi=';
    public $cCor = 'cCor=';
    public $xCor = 'xCor=';
    public $cMod = 'cMod=';
    public $vUnit = 'vUnit=';
    public $vFrete = 'vFrete=';
}

class ini_cobr {             //0-1
    public $header = '[cobr]';
}

class ini_fat {              //0-1
    public $header = '[fat]';
    public $nFat = 'nFat=';
    public $vOrig = 'vOrig=';
    public $vDesc = 'vDesc=';
    public $vliq = 'vLiq=';
}

class ini_dup {              //0-n
    public $Header = '[dup]';
    public $nDup = 'nDup=';
    public $dVenc = 'dVenc=';
    public $vDup = 'vDup=';
}

class ini_infCteSub{         //0-1
    public $Header = '[infCteSub]';
    public $chCte = 'chCte=';
    public $refCteAnu = 'refCteAnu=';
}

class ini_tomaICMS {         //1-1
    public $Header = '[tomaICMS]';
    public $refNFe = 'refNFe=';
}

class ini_refNF {            //1-1
    public $Header = '[refNF]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
    public $mod = 'mod=';
    public $serie = 'serie=';
    public $subserie = 'subserie=';
    public $nro = 'nro=';
    public $valor = 'valor=';
    public $dEmi = 'dEmi=';
    public $refCte = 'refCte=';
    public $indAlteraToma = 'indAlteraToma=';
}

class ini_infGlobalizado {   //0-1
    public $Header = 'infGlobalizado=';
    public $xObs = 'xObs=';
}

class ini_infServVinc {      //0-1
    public $Header = '[infServVinc]';
}

class ini_infCTeMultimodal {  //1-n
    public $Header = '[infCTeMultimodal]';
    public $chCTeMultimodal = 'chCTeMultimodal=';
}

class ini_infCteComp {        //1-1
    public $Header = '[infCteComp]';
    public $chCTe = 'chCTe=';
}

class ini_infCteAnu {         //1-1
    public $Header = '[infCteAnu]';
    public $chCte = 'chCte=';
    public $dEmi = 'dEmi=';
}

class ini_autXML{             //0-10
    public $Header = '[autXML]';
    public $CNPJ = 'CNPJ=';
    public $CPF = 'CPF=';
}

?>