<?php

function GuiaContainer($_id) {
   $_html = "";
   
   // Montando as estruturas HTML para receber a guia Tab e Page
   $_html  = "<!-- Nav tabs -->"
           . "<ul class='nav nav-tabs' role='tablist' id='{$_id}' name='$_id'>"
           . "</ul>

   // Montando a DIV/pagina para receber a aba da guia
   $_html .= "<div class='tab-content'>"
          .  "<div role='tabpanel' class='tab-pane fade in active' id='{$_id_tab}'>"
          .  "<div onmouseout='tabResetColors(\"tabEstoque\");' class='table-responsive'>"
          .  "<!--  Container para a tabela dinamica -->"
          .  "<table id='tabEstoque' name='tabEstoque' class='table'></table>
          .  "</div>
          .  "</div>	<!-- tab consulta -->
          
   return $_html;
}



           . "   <li onclick="setGuia('guiConsulta');" role="presentation" class="active"><a href="#consulta" aria-controls="consulta" role="tab" data-toggle="tab">Consulta</a></li>
           . "   <li onclick="setGuia('guiComplemento');" role="presentation"><a href="#complemento" aria-controls="complemento" role="tab" data-toggle="tab">Complemento</a></li>




<div role="tabpanel" class="tab-pane fade" id="complemento">
<div onmouseout="tabResetColors('tabEstoqueComple');" class="table-responsive">
<!--  Container para a tabela dinamica -->
<table id="tabEstoqueComple" name="tabEstoqueComple" class="table" ></table>
</div>
</div>	<!-- tab Complemento -->
</div> <!--  tab content -->
?>