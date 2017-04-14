<?php
function dlgSelProduto($_db, $_tab_titulo, $_tab_comple) {
    //
	// OBS:
	//
	//     O PHP vai deixar preparado para o JavaScript Colocar as informacoes na Janela, ok 
	//

	// Montando o HTML com a Janela de Dialogo para os dados do Produto	
	$_html = dlgJanela(["id"=>"janSelProduto",
			            "modal"=>"modal70",
			            "titulo_id"=>"janSelProdutoTitulo",
		                "titulo"=>"Seleção de Produto",
						"titulo_css"=>"fa fa-bars",
						"btn_fechar_id"=>"btnSelProdutoFechar",
						"btn_fechar_css"=>"btn btn-primary",
						"btn_fechar_titulo"=>"Fechar"]);

	// Montar o Body dessa Janela	
	// Linha #1	
	$_html_body = "<div id='tab_loader' name='tab_loader' class='loader'></div>" 
			.     "<div id='tab_loader_texto' name='tab_loader_texto' class='loader_texto'>Carregando...</div>"
	        . "<!-- row#Main : Inicio-->"
			. "<div class='row'>"
            .    "<div class='col-md-4'>";
            							
	$_html_body .= "<!-- row#1 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("tipo_item", "fld_tpi_id", "Tp.Item", "Aguarde")}</div>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("tipo_produto", "fld_tpp_id", "Tp.Produto", "Aguarde")}</div>"
			. "</div>"
			. "<!-- row#1 : Fim-->";

	// Linha #2
	$_html_body .= "<!-- row#2 : Inicio-->"
			. "<div class='row'>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("natureza", "fld_nat_id", "Natureza", "Aguarde")}</div>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("origem", "fld_ori_id", "Origem", "Aguarde")}</div>"					
			. "</div>"
				. "<!-- row#2 : Fim-->";			
	// Linha #3	
	$_html_body .="<!-- row#3 : Inicio-->"
            . "<div class='row'>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("familia", "fld_fam_id", "Familia", "Aguarde")}</div>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("sub_familia", "fld_sfa_id", "Sub/Familia", "Aguarde")}</div>"
			. "</div>"
            . "<!-- row#3 : Fim-->";
    // Linha #4
    $_html_body .="<!-- row#4 : Inicio-->"
           	. "<div class='row'>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("linha", "fld_lin_id", "Linha", "Aguarde")}</div>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("categoria", "fld_ctg_id", "Categoria", "Aguarde")}</div>"            		
			. "</div>"
       		. "<!-- row#4 : Fim-->";
	// Linha #5
    $_html_body .="<!-- row#5 : Inicio-->"
    		. "<div class='row'>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("divisao", "fld_div_id", "Div.Negócio", "Aguarde")}</div>"
			.    "<div class='col-md-6'>{$_db->bsComboInformacao("marca", "fld_mar_id", "Marca", "Aguarde")}</div>"    				
			. "</div>"
       		. "<!-- row#5 : Fim-->";

    // Linha #6
    $_html_body .="<!-- row#6 : Inicio-->"
       		. "<div class='row'>"
       		.    "<div class='col-md-6'>{$_db->bsComboInformacao("porte", "fld_por_id", "Porte", "Aguarde")}</div>"
       		.    "<div class='col-md-6'>{$_db->bsComboInformacao("grupo_item", "fld_gru_id", "Grupo", "Aguarde")}</div>"       				
			. "</div>"
       		. "<!-- row#6 : Fim-->";

    // Linha #7
    $_html_body .="<!-- row#7 : Inicio-->"
    		. "<div class='row'>"
       		.    "<div class='col-md-6'>{$_db->bsComboInformacao("seguimento", "fld_seg_id", "Seguimento", "Aguarde")}</div>"
       		.    "<div class='col-md-6'>{$_db->bsComboInformacao("clasfis", "fld_clf_id", "Clas.Fiscal", "Aguarde")}</div>"    				
    		. "</div>"
       		. "<!-- row#7 : Fim-->";       		

    // Finalizando Coluna Main 1
    $_html_body .= "</div>";
    
    // Montando a segunda coluna Main
    $_html_body .= "<div class='col-md-8'>"
    		. "<div class='row'>"
       		.    "<div class='col-md-9'><label>{$_tab_titulo}</label></div>"
       		.    "<div class='col-md-3 text-right'>{$_tab_comple}</div>"    				
    		. "</div>"
    		. "<div class='row'>"
    		.    "<div class='col-md-12'>"
            .       "<div onmouseout='tabResetColors(\"tab_dlgSelProduto\");' class='table-responsive'>"    
            .          "<!--  Container para a tabela dinamica -->"                        
            .          "<table id='tab_dlgSelProduto' name='tab_dlgSelProduto' class='table' ></table>"
            .       "</div>"
            .    "</div>"            		
            . "</div>";
            
    // Finalizando Coluna Main 2
    $_html_body .= "</div>";
    
    // Linha Main
    $_html_body .= "</div>"
       		. "<!-- row#Main : Fim-->";
       				 
	$_html = str_replace("JANELA-BODY-CODE", $_html_body, $_html);
	
	return $_html;
}
?>