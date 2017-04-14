<?php
function dlgSelEntidade($_db, $_tab_titulo, $_tab_comple, $_tab_acao) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do Entidade	
	$_html = dlgJanela(["id"=>"janSelEntidade",
			            "modal"=>"modal70",
			            "titulo_id"=>"janSelEntidadeTitulo",
		                "titulo"=>"Seleção de Entidade",
						"titulo_css"=>"fa fa-bars",
						"btn_fechar_id"=>"btnSelEntidadeFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1	
	$_html_body = "<div id='tab_ent_loader' name='tab_ent_loader' class='loader50'></div>" 
			.     "<div id='tab_ent_loader_texto' name='tab_ent_loader_texto' class='loader50_texto'>Carregando...</div>";
	
	$_html_body .= 
	          "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-3'>{$_db->bsComboInformacao("estado", "dlg_est_id", "Estado", "Aguarde")}</div>"
			.    "<div class='col-md-3'>{$_db->bsComboInformacao("cidade", "dlg_cid_id", "Cidade", "Aguarde")}</div>"
			.    "<div class='col-md-3'>{$_db->bsComboInformacao("bairro", "dlg_bai_id", "Bairro", "Aguarde")}</div>"
			.    "<div class='col-md-3'>{$_db->bsComboInformacao("cep"   , "dlg_cep_id", "CEP", "Aguarde")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";

    // Montando a segunda coluna Main
    $_html_body .=
              "<!-- row#2 : Inicio-->"
            . "<div class='row'>"
       		.    "<div class='col-md-9'><label>{$_tab_titulo}</label></div>"
       		.    "<div class='col-md-3 text-right'>{$_tab_comple}</div>"    				
    		. "</div>"
    	    . "<!-- row#3 : Inicio-->"
    		. "<div class='row'>"
    		.    "<div class='col-md-12'>"
            .       "<div onmouseout='tabResetColors(\"tab_dlgSelEntidade\");' class='table-responsive'>"    
            .          "<!--  Container para a tabela dinamica -->"                        
            .          "<table id='tab_dlgSelEntidade' name='tab_dlgSelEntidade' class='table' ></table>"
            .       "</div>"
            .    "</div>"            		
            . "</div>"
       		. "<script>"
       		     // Monta a tabela para receber as condicoes para o criterio informado no dialog"
       		.    "dlgEntidadeTabelaCriar('tab_dlgSelEntidade','{$_tab_acao}');"
		   	. "</script>";
    
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
    return $_html;
}

function dlgSelEntidadeBotoesPaginacao() {
    // Montando os botoes de nevegacao para passar para a janela
    $_botoes = "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Primeira Página'"
	     	 .        " onclick='dlgEntidadeAcao(\"SEL_ENTIDADE\", \"PRIMEIRA\");' id='bdEntPrimeira'><i class='fa fa-step-backward'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Proxima Página'"
			 .        " onclick='dlgEntidadeAcao(\"SEL_ENTIDADE\", \"PROXIMA\");' id='bdEntProxima'><i class='fa fa-play'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Página Anterior'"
			 .        " onclick='dlgEntidadeAcao(\"SEL_ENTIDADE\", \"ANTERIOR\");' id='bdEntAnterior'><i class='fa fa-play fa-rotate-180'></i>"
			 . "</button>&nbsp;"
			 . "<button class='btn btn-primary btn-xs disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Ultima Página'"
			 .	 	  " onclick='dlgEntidadeAcao(\"SEL_ENTIDADE\", \"ULTIMA\");' id='bdEntUltima'><i class='fa fa-step-forward'></i>"
			 . "</button>&nbsp;&nbsp;"
			 . "<span class='badge badge-success disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Pagina Corrente' id='valDlgEntPagina'></span>"
			 . "&nbsp;<span>/</span>&nbsp;"
			 . "<span class='badge badge-success disabled' disabled data-toggle='tooltip' data-placement='bottom' title='Total de Página(s)' id='valDlgEntUltimaPagina'></span>";
    
	return $_botoes;
    
}
?>