<?php
function dlgEstoque($_db) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do Estoque	
	$_html = dlgJanela(["id"=>"janEstoqueInfo",
			            "modal"=>"modal80",
			            "titulo_id"=>"janStqTitulo",
		                "titulo"=>"Informações do Estoque",
						"titulo_css"=>"fa fa-bars",
						"btn_fechar_id"=>"btnStqFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1
	$_html_body = "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_arm_descr",  "Armazêm", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_prd_descr",  "Produto", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_qld_descr",  "Qualidade", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_itm_stqseg", "Estq.Segurança", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_itm_stqmax", "Estq.Máximo", "")}</div>"
 			.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_itm_stqmin", "Estq.Mínimo", "")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";
	
	// Linha #2	
	$_html_body .="<!-- row#2 : Inicio-->"
                . "<div class='row'>"
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtddis", "Disponível", "")}</div>"                		
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdres", "Reservado", "")}</div>"
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdtot", "Total (Dis+Res)", "")}</div>"
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdbko", "Backorder", "")}</div>"
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdlit", "Litígio", "")}</div>"
                .    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdqua", "Quarentena", "")}</div>"
                . "</div>"
                . "<!-- row#2 : Fim-->";

    // Linha #3
    $_html_body .="<!-- row#3 : Inicio-->"
           		. "<div class='row'>"
           		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdfat", "a Faturar", "")}</div>"
          		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdtrn", "Trânsito", "")}</div>"
           		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdemr", "em Recebimento", "")}</div>"
           		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdrec", "Recebido", "")}</div>"
           		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdmet", "Meta", "")}</div>"
           		.    "<div class='col-md-2'>{$_db->bsDivTexto("stq_stq_qtdorf", "Ord.Fabicação", "")}</div>"
           		. "</div>"
         		. "<!-- row#3 : Fim-->";
           		
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
	return $_html;
}
?>