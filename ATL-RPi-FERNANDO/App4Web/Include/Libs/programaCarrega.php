<?php
function programaCarrega($aPrograma, $aVersao) {
   $_programa = new programaModel();
   $_programa->init();
   $_where = "prg_cod = '{$aPrograma}'";   
   $_prg = $_programa->read($_where, 1, 0, 0);
   
   $_dados = null;
   
   // Programa nao existe
   if ( ! $_prg ) {
	   $_prg[0]["prg_versao"] = "Indefinido";
	   $_prg[0]['prg_id']     = 0;
       $_prg[0]['prg_cod']    = $aPrograma;
       $_prg[0]['prg_descr']  = "Não Identificado";		
       $_prg[0]['prg_csstexto'] = "NONE";		
       $_prg[0]['prg_view']   = "NONE";		  
       $_prg[0]['prg_icone']  = "NONE";		   
   }

   // Versao FISICA controlada pelo programador e fica hardcode no Script indicando quando foi a ultima revisao e versao desse script
   $_dados['prg_versao_fisica']  = $aVersao;

   // Versao do Banco eh a q esta no cadastro para o programa solicitado.
   $_dados['prg_versao']         = $_prg[0]['prg_versao'];

   $_programa_versao_mensagem = "Versão {$aVersao}";
   if ( $aVersao != $_prg[0]['prg_versao'] ) {
      $_programa_versao_mensagem = "Versionamento dessincronizado. Versão em Uso {$aVersao}. Versão no Banco {$_prg[0]['prg_versao']}"; 
   }
   $_dados['controle_de_versao'] = $_programa_versao_mensagem;

   $_dados['prg_id']             = $_prg[0]['prg_id'];
   $_dados['prg_cod']            = $_prg[0]['prg_cod'];
   $_dados['prg_descr']          = $_prg[0]['prg_descr'];		
   $_dados['prg_csstexto']       = $_prg[0]['prg_csstexto'];		
   $_dados['prg_view']           = $_prg[0]['prg_view'];	

   // O Script deve mostrar a versao que esta no SCRIPT podem indicar que o banco esta desatualizado ou vice e versa
   $_dados['page_content']       = $_prg[0]['prg_descr'];
   if ( $_prg[0]['prg_icone'] == "NONE" ) {
   	$_prg[0]['prg_icone'] = "fa fa-bug"; // Default
   }
   $_dados['prg_icone']          = $_prg[0]['prg_icone'];		
   $_dados['page_icone']         = $_prg[0]['prg_icone'];
 
   $_dados['page_data']          = date("d/m/Y");
   $_dados['page_hora']          = date("h:i:s");
   
   // Dados para o controller
   //-- Identificacao do Tela a ser processada
   $_dados['tel_nome']           = $_prg[0]['prg_descr'];		
   $_dados['tel_titulo']         = $_prg[0]['prg_cod'] . " - " . $_prg[0]['prg_descr'] . " - Id# " . $_prg[0]['prg_id'];
   $_dados['tel_id']             = $_prg[0]['prg_id'];		
   $_dados['tel_cod']            = $_prg[0]['prg_cod'];		

   // Sobrepondo os valores que foram alimentados pelo include para essa pagina
   $_dados['page_header']              = $_dados['tel_nome'];
   $_dados['page_header_description']  = $_dados['tel_titulo'];   

   // Controles de Paginacao de resultado
   $_dados['pagina_corrente']    = 0;
   $_dados['pagina_total']       = 0;
   
   return $_dados;
}
?>