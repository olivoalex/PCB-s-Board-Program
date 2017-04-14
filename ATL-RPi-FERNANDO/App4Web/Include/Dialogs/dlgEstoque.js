//
//
// OBS:
//
//     O PHP deixou tudo preparado para o JS fazer o merge, complementar, com os dados conforme  
//    a necessidade
//
//
function dlgEstoqueCarrega(aDados) {

	// codigo do Estoque DECLINADO
	var _produto = aDados.ITM.itm_cod + "&nbsp;\/&nbsp;"  
	             + aDados.DEC_01.dec_cod + "&nbsp;\/&nbsp;"
	             + aDados.DEC_02.dec_cod
	if (aDados.DEC_03 != null ) {
	   _produto += "&nbsp;\/&nbsp;"  
	             + aDados.DEC_03.dec_cod
	}
	$("#stq_prd_descr").html(_produto).show();
		
	// Armazem
	var _armazem   = aDados.SBA.sba_cod + "&nbsp;/&nbsp;"
	               + aDados.SBA.sba_scd + "&nbsp;-&nbsp;"
	               + aDados.SBA.sba_apelido;
	$("#stq_arm_descr").html(_armazem).show();
	
	// Qualidade
	var _qualidade  = aDados.QLD.qld_cod + "&nbsp;-&nbsp;"
	                + aDados.QLD.qld_descr;
	$("#stq_qld_descr").html(_qualidade).show();
	
	// Estoque Seguranca
	var _seguranca = aDados.ITM.itm_stqseg;          
	$("#stq_itm_stqseg").html(_seguranca).show();
	
	// Estoque Maximo
	var _maximo = aDados.ITM.itm_stqmax;          
	$("#stq_itm_stqmax").html(_maximo).show();
	
	// Estoque Minimo
	var _minimo = aDados.ITM.itm_stqmin;          
	$("#stq_itm_stqmin").html(_minimo).show();
	
	// Estoque             	
    $("#stq_stq_qtddis").html(aDados.STQ.stq_qtddis).show();  // Disponivel
    $("#stq_stq_qtdres").html(aDados.STQ.stq_qtdres).show();  // Resevado
    
    // total: DIS+RES
    var _dis = parseFloat(aDados.STQ.stq_qtddis);
    if ( isNaN(_dis) ) { _dis = 0;}
    var _res = parseFloat(aDados.STQ.stq_qtdres);
    if ( isNaN(_res) ) { _res = 0;}
    var _tot = _dis + _res;
    
    $("#stq_stq_qtdtot").html(_tot.toFixed(4).toString()).show();  // Total
    
    $("#stq_stq_qtdbko").html(aDados.STQ.stq_qtdbko).show();  // Backorder
    $("#stq_stq_qtdlit").html(aDados.STQ.stq_qtdlit).show();  // Litigio
    $("#stq_stq_qtdqua").html(aDados.STQ.stq_qtdqua).show();  // Quarentena
    $("#stq_stq_qtdfat").html(aDados.STQ.stq_qtdfat).show();  // a Faturar
    $("#stq_stq_qtdtrn").html(aDados.STQ.stq_qtdtrn).show();  // Transito
    $("#stq_stq_qtdemr").html(aDados.STQ.stq_qtdemr).show();  // em Recebimento
    $("#stq_stq_qtdrec").html(aDados.STQ.stq_qtdrec).show();  // Recebido
    $("#stq_stq_qtdmet").html(aDados.STQ.stq_qtdmet).show();  // Meta
    $("#stq_stq_qtdorf").html(aDados.STQ.stq_qtdorf).show();  // Ordem fabricacao    
   
}