//
//
// OBS:
//
//     O PHP deixou tudo preparado para o JS fazer o merge, complementar, com os dados conforme  
//    a necessidade
//
//
function dlgEnderecoCarrega(aDados) {

	// codigo do Estoque DECLINADO
	var _produto = aDados.ITM.itm_cod + "&nbsp;\/&nbsp;"  
	             + aDados.DEC_01.dec_cod + "&nbsp;\/&nbsp;"
	             + aDados.DEC_02.dec_cod
	if (aDados.DEC_03 != null ) {
	   _produto += "&nbsp;\/&nbsp;"  
	             + aDados.DEC_03.dec_cod
	}
	$("#ede_prd_descr").html(_produto).show();
		
	// Armazem
	var _armazem   = aDados.SBA.sba_cod + "&nbsp;/&nbsp;"
	               + aDados.SBA.sba_scd + "&nbsp;-&nbsp;"
	               + aDados.SBA.sba_apelido;
	$("#ede_arm_descr").html(_armazem).show();
	
	// Qualidade
	var _qualidade  = aDados.QLD.qld_cod + "&nbsp;-&nbsp;"
	                + aDados.QLD.qld_descr;
	$("#ede_qld_descr").html(_qualidade).show();
	
	// Estoque Seguranca
	var _seguranca = aDados.ITM.itm_stqseg;          
	$("#ede_itm_stqseg").html(_seguranca).show();
	
	// Estoque Maximo
	var _maximo = aDados.ITM.itm_stqmax;          
	$("#ede_itm_stqmax").html(_maximo).show();
	
	// Estoque Minimo
	var _minimo = aDados.ITM.itm_stqmin;          
	$("#ede_itm_stqmin").html(_minimo).show();	
   
}