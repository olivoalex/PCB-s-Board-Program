<?php
function dlgEndereco($_db) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do Produto	
	$_html = dlgJanela(["id"=>"janEnderecoInfo",
			            "modal"=>"modal80",
			            "titulo_id"=>"janEdeTitulo",
		                "titulo"=>"Informações do Endereço",
						"titulo_css"=>"fa fa-list-ol",
						"btn_fechar_id"=>"btnEdeFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1
	$_html_body = "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_arm_descr", "Armazem", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_prd_descr", "Produto", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_qld_descr", "Qualidade", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_itm_stqseg", "Estq.Seguranca", "")}</div>"
			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_itm_stqmax", "Estq.Maximo", "")}</div>"
 			.    "<div class='col-md-2'>{$_db->bsDivTexto("ede_itm_stqmin", "Estq.Minimo", "")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";
	
	// Linha #2	
	$_html_body .="<!-- row#2 : Inicio-->"
                . "<div class='row'>"
                .    "<div class='col-md-12'>"
                .       "<div onmouseout='tabResetColors(\"tabJanEndereco\");' class='table-responsive'> "
    	        .          "<!--  Container para a tabela dinamica --> "
	            .          "<table id='tabJanEndereco' name='tabJanEndereco' class='table' ></table> "
	            .       "</div> "
                .    "</div>"                		
                . "</div>"
                . "<!-- row#2 : Fim-->";
                
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
	return $_html;
}
?>