<?php
function dlgSelCondicaoPagamento($_db, $_tab_titulo, $_tab_comple, $_tab_acao) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do CondicaoPagamento	
	$_html = dlgJanela(["id"=>"janSelCondicaoPagamento",
			            "modal"=>"modal70",
			            "titulo_id"=>"janSelCondicaoPagamentoTitulo",
		                "titulo"=>"Seleção de CondicaoPagamento",
						"titulo_css"=>"fa fa-bars",
						"btn_fechar_id"=>"btnSelCondicaoPagamentoFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1	
	$_html_body = "<div id='tab_cpg_loader' name='tab_cpg_loader' class='loader50'></div>" 
			.     "<div id='tab_cpg_loader_texto' name='tab_cpg_loader_texto' class='loader50_texto'>Carregando...</div>";
	
	$_html_body .= 
	          "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-sm-4'>{$_db->bsComboInformacao("natureza_condicao", "fld_ncp_id", "Natureza", "Aguarde")}</div>"
			.    "<div class='col-sm-4'>{$_db->bsComboInformacao("natureza_grupo", "fld_ncp_grupo", "Grupo", "Aguarde")}</div>"
			.    "<div class='col-sm-4'>{$_db->bsComboInformacao("condicao_parcelas", "fld_cpg_parcelas", "Parcelas", "Aguarde")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";

    // Montando a segunda coluna Main
    $_html_body .=
              "<!-- row#2 : Inicio-->"
            . "<div class='row'>"
       		.    "<div class='col-sm-9'><label>{$_tab_titulo}</label></div>"
       		.    "<div class='col-sm-3 text-right'>{$_tab_comple}</div>"    				
    		. "</div>"
    	    . "<!-- row#3 : Inicio-->"
    		. "<div class='row'>"
    		.    "<div class='col-sm-12'>"
            .       "<div onmouseout='tabResetColors(\"tab_dlgSelCondicaoPagamento\");' class='table-responsive'>"    
            .          "<!--  Container para a tabela dinamica -->"                        
            .          "<table id='tab_dlgSelCondicaoPagamento' name='tab_dlgSelCondicaoPagamento' class='table' ></table>"
            .       "</div>"
            .    "</div>"            		
            . "</div>"
       		. "<script>"
       		     // Monta a tabela para receber as condicoes para o criterio informado no dialog"
       		.    "dlgCondicaoPagamentoTabelaCriar('tab_dlgSelCondicaoPagamento','{$_tab_acao}');"
		   	. "</script>";
    
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
    return $_html;
}

function dlgSelCondicaoPagamentoBotoesPaginacao() {
    // Montando os botoes de nevegacao para passar para a janela
    $_botoes = "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Primeira Página'"
	     	 .        " onclick='dlgCondicaoPagamentoAcao(\"SEL_CONDICAO\", \"PRIMEIRA\");' id='bdCpgPrimeira'><i class='fa fa-step-backward'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Proxima Página'"
			 .        " onclick='dlgCondicaoPagamentoAcao(\"SEL_CONDICAO\", \"PROXIMA\");' id='bdCpgProxima'><i class='fa fa-play'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Página Anterior'"
			 .        " onclick='dlgCondicaoPagamentoAcao(\"SEL_CONDICAO\", \"ANTERIOR\");' id='bdCpgAnterior'><i class='fa fa-play fa-rotate-180'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Ultima Página'"
			 .	 	  " onclick='dlgCondicaoPagamentoAcao(\"SEL_CONDICAO\", \"ULTIMA\");' id='bdCpgUltima'><i class='fa fa-step-forward'></i>"
			 . "</button>&nbsp;&nbsp;"
			 . "<span class='badge badge-success disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Pagina Corrente' id='valDlgCpgPagina'></span>"
			 . "&nbsp;<span>/</span>&nbsp;"
			 . "<span class='badge badge-success disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Total de Página(s)' id='valDlgCpgUltimaPagina'></span>";
    
	return $_botoes;
    
}
?>