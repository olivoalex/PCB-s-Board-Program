<?php
function dlgProduto($_db) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do Produto	
	$_html = dlgJanela(["id"=>"janProdutoInfo",
			            "modal"=>"modal50",
			            "titulo_id"=>"janPrdTitulo",
		                "titulo"=>"Informações do Produto",
						"titulo_css"=>"fa fa-bars",
						"btn_fechar_id"=>"btnPrdFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1
	$_html_body = "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_prd_cod"  , "Produto", "")}</div>"
			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_prd_descr", "Descrição", "")}</div>"
			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_tpi_descr", "Tp.Item", "")}</div>"
			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_tpp_descr", "Tp.Produto", "")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";
	
	// Linha #2	
	$_html_body .="<!-- row#2 : Inicio-->"
                . "<div class='row'>"
			    .    "<div class='col-md-3'>{$_db->bsDivTexto("txt_fam_descr", "Familia", "")}</div>"
 			    .    "<div class='col-md-3'>{$_db->bsDivTexto("txt_sfa_descr", "Sub/Familia","")}</div>"
                .    "<div class='col-md-3'>{$_db->bsDivTexto("txt_tdc_01_descr", "Tp.1a Declinação","")}</div>"
                .    "<div class='col-md-3'>{$_db->bsDivTexto("txt_dec_01_descr", "1a.Declinação","")}</div>"
                . "</div>"
                . "<!-- row#2 : Fim-->";

    $_html_body .="<!-- row#3 : Inicio-->"
           		. "<div class='row'>"
           		.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_tdc_02_descr", "Tp.2a Declinação","")}</div>"
           		.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_dec_02_descr", "2a.Declinação","")}</div>"
           		.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_tdc_03_descr", "Tp.3a Declinação","")}</div>"
           		.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_dec_03_descr", "3a.Declinação","")}</div>"
           		. "</div>"
       			. "<!-- row#3 : Fim-->";
           		
    $_html_body .="<!-- row#4 : Inicio-->"
    			. "<div class='row'>"
       			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_nat_descr", "Natureza","")}</div>"
       			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_ori_descr", "Origem","")}</div>"
       			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_gru_descr", "Grupo","")}</div>"
       			.    "<div class='col-md-3'>{$_db->bsDivTexto("txt_ctg_descr", "Categoria","")}</div>"
       			. "</div>"
       			. "<!-- row#4 : Fim-->";
       					
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
	return $_html;
}
?>