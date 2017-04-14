<?php
function dlgJanela($aParans) {
	$_html = "";
	
	$_janela_id = ( array_key_exists("id", $aParans) ? $aParans["id"] : "_none_id");
	$_janela_tipo = ( array_key_exists("modal", $aParans) ? $aParans["modal"] : "modal50");
	$_titulo_id = ( array_key_exists("titulo_id", $aParans) ? $aParans["titulo_id"] : $_id."_tit");
	$_titulo = ( array_key_exists("titulo", $aParans) ? $aParans["titulo"] : "Dialog Sem titulo");
	$_titulo_css = ( array_key_exists("titulo_css", $aParans) ? $aParans["titulo_css"] : "fa fa-list amarelo");	
	$_btn_fechar_id = ( array_key_exists("btn_fechar_id", $aParans) ? $aParans["btn_fechar_id"] : $_id."btnFechar");
	$_btn_fechar_css = ( array_key_exists("btn_fechar_css", $aParans) ? $aParans["btn_fechar_css"] : "btn btn-primary");
	$_btn_fechar_titulo = ( array_key_exists("btn_fechar_titulo", $aParans) ? $aParans["btn_fechar_titulo"] : "Fechar");
	$_janela_token = ( array_key_exists("janela_token", $aParans) ? $aParans["janela_token"] : "JANELA-BODY-CODE");
	
	$_html = " <!-- Montando uma DIALOG WINDOW Modal - Part#1 --> "
	       . " <div id='{$_janela_id}' class='modal fade {$_janela_tipo}'> " 
	       . " <div class='modal-dialog'> "
	       . " <div class='modal-content'> "
	       . " <!-- dialog Header --> "
	       . " <div class='modal-header'> "
	       . " <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button> "
	       . " <h3 class='modal-title'><a class='{$_titulo_css}'></a>&nbsp;<span id='{$_titulo_id}'>{$_titulo}</span></h3> "
	       . " </div> "
	       . " <!-- dialog body --> "
	       . " <div class='modal-body'> "
	       . " {$_janela_token} "	
	       . " </div> "
	       . " <!-- dialog buttons --> "
	       . " <div class='modal-footer'> "
	       . " <button id='{$_btn_fechar_id}' name='{$_btn_fechar_id}' type='button' class='{$_btn_fechar_css}'>{$_btn_fechar_titulo}</button> "
	       . " </div> "
	       . " </div> "
	       . " </div> "
	       . " </div> ";
	
	return $_html;
}
?>