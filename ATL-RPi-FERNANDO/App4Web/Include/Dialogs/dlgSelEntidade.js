var dlgEntidadePaginacao = [];
//
//
// OBS:
//
//     O PHP deixou tudo preparado para o JS fazer o merge, complementar, com os dados conforme  
//    a necessidade
//
//
function dlgEntidadeCarrega(aDados) {

	// Exibe os valores
	var _produto_descr = aDados.ITM.itm_descr;
	$("#txt_prd_cod").html(_produto).show();
	$("#txt_prd_descr").html(_produto_descr).show();
	
}

function dlgEntidadeTabelaCriar(aTabela, aAcaoSelecao) {
	//
	// Montando a tabela da Janela de Pesquisa de Condicao de Pagamento: tab_dlgSelEntidade
	//
	var tab_dlgEstrutura = [];
	tab_dlgEstrutura[0]  = new tabColuna('cAcao',       'string' , new tabColunaConteudo('t_0', '0',  '0', 'NNN', 'Acao',     "", null));
	tab_dlgEstrutura[1]  = new tabColuna('cEntDescr',   'string' , new tabColunaConteudo('t_1', '1',  '1', 'NNN', 'Condição', "", null));
	tab_dlgEstrutura[2]  = new tabColuna('cEntCod',     'string' , new tabColunaConteudo('t_2', '2',  '2', 'NNN', 'Codigo'  , "", null));
	tab_dlgEstrutura[3]  = new tabColuna('cEntParcelas','string' , new tabColunaConteudo('t_3', '3',  '3', 'NNN', 'Parcelas', "", null));
	tab_dlgEstrutura[4]  = new tabColuna('cNcpGrupo',   'string' , new tabColunaConteudo('t_4', '4',  '4', 'NNN', 'Grupo'   , "", null));
	tab_dlgEstrutura[5]  = new tabColuna('cNcpDescr',   'string' , new tabColunaConteudo('t_5', '5',  '5', 'NNN', 'Natureza', "", null));
	 
	// Atribui o mesmo css para todas
	// Muda o tipo para a primeira coluna
	tab_dlgEstrutura[0].changeCSSByTipo('TITULO'   ,'col40L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[0].changeCSSByTipo('LINHA'    ,'col40L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[0].changeCSSByTipo('RODAPE'   ,'col40R', 'rodapeAntes', 'rodape', 'rodapeSelected');
	// CPG_DESCR
	tab_dlgEstrutura[1].changeCSSByTipo('TITULO'   ,'col210L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[1].changeCSSByTipo('LINHA'    ,'col210L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[1].changeCSSByTipo('RODAPE'   ,'col210R', 'rodapeAntes', 'rodape', 'rodapeSelected');
    // CPG_COD
	tab_dlgEstrutura[2].changeCSSByTipo('TITULO'   ,'col40L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[2].changeCSSByTipo('LINHA'    ,'col40L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[2].changeCSSByTipo('RODAPE'   ,'col40R', 'rodapeAntes', 'rodape', 'rodapeSelected');
	// CPG_PARCELAS
	tab_dlgEstrutura[3].changeCSSByTipo('TITULO'   ,'col60L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[3].changeCSSByTipo('LINHA'    ,'col60L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[3].changeCSSByTipo('RODAPE'   ,'col60R', 'rodapeAntes', 'rodape', 'rodapeSelected');
	// NCP_GRUPO
	tab_dlgEstrutura[4].changeCSSByTipo('TITULO'   ,'col60L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[4].changeCSSByTipo('LINHA'    ,'col60L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[4].changeCSSByTipo('RODAPE'   ,'col60R', 'rodapeAntes', 'rodape', 'rodapeSelected');
    // NCP_DESCR
	tab_dlgEstrutura[5].changeCSSByTipo('TITULO'   ,'col210L', 'tituloAntes', 'titulo', 'tituloSelected');
	tab_dlgEstrutura[5].changeCSSByTipo('LINHA'    ,'col210L', 'antes', 'corrente', 'selected');
	tab_dlgEstrutura[5].changeCSSByTipo('RODAPE'   ,'col210R', 'rodapeAntes', 'rodape', 'rodapeSelected');
	
	
	// Montando a Tabela
	iniTabParans(aTabela, tab_dlgEstrutura);

	// Definindo botoes da linha da tabela
	var aCustomD = [];
	aCustomD[0] = new tabBotoesCustom("CUS", aAcaoSelecao, "btnEntSelect", "Seleciona", "BTN", "Seleciona Cond.Pagameto", "verde fa fa-thumb-tack cssHand");

	// Variavel global no Javascript
	aTabRowButtons[aTabela]["CUS"] = aCustomD;

	// Exibindo a pagina corrente da pesquisa no Dialog
	dlgEntidadeTabelaMostraPagina();

}

function dlgEntidadeTabelaMostraPagina() {
	   var pagina = 0;
	   var pagina_ultima = 0;
	   if ( array_key_exists("pagina",dlgEntidadePaginacao) == true ) {
		  pagina = dlgEntidadePaginacao["pagina"];
		  pagina_ultima = dlgEntidadePaginacao["pagina_ultima"];
	     
    } 
	   $("#valDlgEntPagina").html(pagina).show();
	   $("#valDlgEntUltimaPagina").html(pagina_ultima).show();
}

function dlgEntidadeShowLoader() {
	  document.getElementById("tab_ent_loader").style.display = "block";
	  document.getElementById("tab_ent_loader_texto").style.display = "block";
 }
 
 function dlgEntidadeHideLoader() {
	  document.getElementById("tab_ent_loader").style.display = "none";
	  document.getElementById("tab_ent_loader_texto").style.display = "none";
 }       
	   
 function dlgEntidadeAcao(aAcao,aComple) {
 	   
     // Processar, Paginacao e Dialog conforme solicitado via combobox
     aAcao = aAcao.toUpperCase();
     aComple = aComple.toUpperCase();
     dlgEntidadeProcessarDados(aAcao, aComple);

 }       

 function dlgEntidadeReset(aOnde) {
   
	 if ( aOnde == "natureza_condicao") {
	    $('#fld_ncp_id').empty().append('<option>Carregar</option>');	 
	 }
	 if ( aOnde == "natureza_grupo") {
	    $('#fld_ncp_grupo').empty().append('<option>Carregar</option>');
	 }
	 if ( aOnde == "natureza_parcelas") {
	    $('#fld_ent_parcelas').empty().append('<option>Carregar</option>');    
	 }
}
 
 function dlgEntidadeLimpar() {

	 dlgEntidadeLimpar("");
	 
     // Inicializa os Criterios
     $("#fld_ncp_id").val(0).change();
     $("#fld_ncp_grupo").val(0).change();
     $("#fld_ent_parcelas").val(0).change();

     // poe focus na Natureza Condicao
     $( "#fld_ncp_id" ).focus();

 }
 
 function dlgEntidadeProcessarDados(aAcao, aComple) {
	  
	 //Aciona o AJAX e indica como receber o JSON
	  var natureza_condicao    = $('#fld_ncp_id').val();
	  var natureza_grupo       = $('#fld_ncp_grupo').val();
	  var natureza_parcelas    = $('#fld_ent_parcelas').val();

	  // Janela dlgSelCondicao
      if ( aAcao == "SEL_CONDICAO" ) {
          // Exibe o loader de dados para carregar a tabela
    	  dlgEntidadeShowLoader();

    	  dlgEntidadeDesativaBotoesPaginacao();  		    
      }
	  
      var url = js_link + "dlgEntidade_AJAX/ajax/index/acao/" + aAcao
                        + "/complemento/" + aComple
                        + "/empresa/" + empresa
                        + "/filial/" + filial 
                        + "/usuario/" + usuario
                        + "/natureza_condicao/" + natureza_condicao
                        + "/natureza_grupo/" + natureza_grupo 
                        + "/natureza_parcelas/" + natureza_parcelas;
      // Se tiver carregada
      if ( array_key_exists("pagina", dlgEntidadePaginacao ) == true ) {
   		 url += "/pagina/" + dlgEntidadePaginacao["pagina"]
              + "/pagina_corrente/" + dlgEntidadePaginacao["pagina_corrente"]
   	          + "/pagina_primeira/" + dlgEntidadePaginacao["pagina_primeira"]
   	          + "/pagina_anterior/" +  dlgEntidadePaginacao["pagina_anterior"]
              + "/pagina_proxima/" + dlgEntidadePaginacao["pagina_proxima"] 
              + "/pagina_ultima/" + dlgEntidadePaginacao["pagina_ultima"]  
              + "/pagina_linhas/" + dlgEntidadePaginacao["pagina_linhas"]  
              + "/pagina_offset/" + dlgEntidadePaginacao["pagina_offset"]
   	          + "/pagina_total_linhas/" + dlgEntidadePaginacao["pagina_total_linhas"]    
              + "/pagina_acao/" + aComple;                  
    }

      dlgEntidadeDesativaBotoesPaginacao();

      //alert("Url: " + url);
      
	  $.getJSON(url, function ( dados_retorno ){ 
	     // Aciona funcao para processar o retorno do AJAX em formato JSON
	     dlgEntidadeProcessarRetornoAJAX( dados_retorno);   			   
	  } ); 
	              
 }   

 function dlgEntidadeProcessarRetornoAJAX( aDados ) {
	 var aAcao  = aDados.acao;
     var aTabId = "tab_dlgSelEntidade";

     var rId = null;

     // Exibe o loader de dados para carregar a tabela
     dlgEntidadeShowLoader();
     
     aTabAtivaUserCellFunction[aTabId] = false;           
       
 	 if (aDados.dados.CPG != null ){	

 		     //aTabAtivaUserCellFunction = true;
 		      		   
	   		 // 1. Limpar tabela atual
	   		 tabDrop(aTabId);
	   		 
	   		 // 2. Criando o Header e Footer da tabela
	         tabHeadInsert(aTabId);
	         tabFootInsert(aTabId);
         
	   		 // 3. Carregar Dados do Enderecos 
	   		 var lEntidade = aDados.dados.CPG.length;

	   		 // Array com as colunas da linha
	   		 var linha = [];

	   		 // Determinando a situacao da Celula/Coluna (Estoque)
	   		 var sitLinha = "LINHA";

	   		 // Varrendo as linhas da consulta recebidos
	   		 for ( var l=0; l < lEntidade; l++) {		   	   

	   		    // Indice para colunas
	            var c=0;

	   		    // Zerando o array
		   	    linha = [];
		   	    
		   	    // Iniciando a Linha como LIVRE
		   	    sitLinha = "LIVRE";
		   	   
		   	    // Variaveis de controle 
		   	    _ent_id = aDados.dados.CPG[l].referencia.CPG.ent_id;
		   	    rId = _ent_id;

                // Determinando dados da linha como referencia.
                // Sempre vou colocalo apenas na 1a coluna de cada linha pois a referencia eh de toda a linha
		        var aReferencia = [];
			    aReferencia = aDados.dados.CPG[l].referencia; 				    
		   	   
	   		    // Iniciando as colunas da tabela  	   		    		 	    		   	  
		   	    // Mantem as primeiras colunas ate a declinacao
                // Coluna 0 - botoes
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NNN", "", sitLinha, aReferencia);

	   	        // Coluna 1 - Descricao da Condicao Pagamento
	   	
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NONE", aDados.dados.CPG[l].referencia.CPG.ent_descr, sitLinha, null);
	   		    
	   	        // Coluna 2 - Codigo
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NONE", aDados.dados.CPG[l].referencia.CPG.ent_cod, sitLinha, null);
   		    
	   	        // Coluna 3- Quantidade de Parcelas
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NONE", aDados.dados.CPG[l].referencia.CPG.ent_parcelas, sitLinha, null);
	   		    
	   	        // Coluna 4 - Grupo Natureza
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NONE", aDados.dados.CPG[l].referencia.NCP.ncp_grupo, sitLinha, null);

	   	        // Coluna 5 - Descricao da natureza Condicao
	   		    cId = rId + "_e"+c;
	   		    linha[c++] = new tabColunaConteudo(cId, "", "", "NONE", aDados.dados.CPG[l].referencia.NCP.ncp_descr, sitLinha, null);
	   		    
			    // Com a linha montada, vamos adicionala na tabela :) 			   				
		   	    setTabRowAction(aTabId, "ADD");	 
		   	    exeTabRowAction(aTabId, rId, linha);  		   	    
	   		 }				   
	   		 // Pegando dados de Paginacao
	   		 dlgEntidadePaginacao["pagina"]              = aDados.paginacao.pagina; // Pagina corrente
	         dlgEntidadePaginacao["pagina_corrente"]     = aDados.paginacao.pagina_corrente;
	         dlgEntidadePaginacao["pagina_primeira"]     = aDados.paginacao.pagina_primeira;
	         dlgEntidadePaginacao["pagina_anterior"]     = aDados.paginacao.pagina_anterior;
	         dlgEntidadePaginacao["pagina_proxima"]      = aDados.paginacao.pagina_proxima;
	         dlgEntidadePaginacao["pagina_ultima"]       = aDados.paginacao.pagina_ultima;
	         dlgEntidadePaginacao["pagina_linhas"]       = aDados.paginacao.pagina_linhas;
	         dlgEntidadePaginacao["pagina_offset"]       = aDados.paginacao.pagina_offset;
	         dlgEntidadePaginacao["pagina_total_linhas"] = aDados.paginacao.pagina_total_linhas;
	         dlgEntidadePaginacao["pagina_acao"]         = aDados.paginacao.pagina_acao;

	 	     // Mostrando a pagina como ficou, se achou ou nao
	     	 dlgEntidadeTabelaMostraPagina();
	     	 
	     	 // Verifica se precisa ativar botoes
	         dlgEntidadeAtivaBotoesPaginacao();
	          	         
		  } else {
			  dlgEntidadeDesativaBotoesPaginacao();
			 
			 dlgEntidadePaginacao = []; // inicializa array
			 
			 tabDrop(aTabId);
			 
	 	     // Mostrando a pagina como ficou, se achou ou nao
			 dlgEntidadeTabelaMostraPagina();
			 alert("Nao foi identificado nehuma Condicao para esse Criterio Informado");
	      }    

 	  // Esconde o Loader para a tabela
 	  // Espera 1/2 segundo apos montada a tabela
 	  var out = setTimeout(dlgEntidadeHideLoader,500);
 	         	  
  }
 
 function dlgEntidadeAtivaBotoesPaginacao() {

	   // Apenas ativa os botoes se tiver mais de uma pagina
	   _tot = parseInt($("#valDlgEntUltimaPagina").html());
	   if ( isNaN(_tot) ) { _tot = 0;}
	   
	   if ( _tot > 1 ) {
		   $("#bdEntPrimeira").prop("disabled", false);
		   $("#bdEntProxima").prop("disabled", false);
		   $("#bdEntAnterior").prop("disabled", false);
		   $("#bdEntUltima").prop("disabled", false);
		   
		   $("#bdEntPrimeira").removeClass("disabled");
		   $("#bdEntProxima").removeClass("disabled");
		   $("#bdEntAnterior").removeClass("disabled");
		   $("#bdEntUltima").removeClass("disabled");
	   }
 }
 
 function dlgEntidadeDesativaBotoesPaginacao() {
	   $("#bdEntPrimeira").prop("disabled", true);
	   $("#bdEntProxima").prop("disabled", true);
	   $("#bdEntAnterior").prop("disabled", true);
	   $("#bdEntUltima").prop("disabled", true);

	   $("#bdEntPrimeira").addClass("disabled");
	   $("#bdEntProxima").addClass("disabled");
	   $("#bdEntAnterior").addClass("disabled");
	   $("#bdEntUltima").addClass("disabled");
 }