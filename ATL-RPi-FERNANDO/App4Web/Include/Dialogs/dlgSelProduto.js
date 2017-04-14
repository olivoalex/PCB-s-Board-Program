//
//
// OBS:
//
//     O PHP deixou tudo preparado para o JS fazer o merge, complementar, com os dados conforme  
//    a necessidade
//
//
function dlgSelProdutoCarrega(aDados) {

	// codigo do Produto DECLINADO
	var _produto = aDados.ITM.itm_cod + "&nbsp;\/&nbsp;"  
	             + aDados.DEC_01.dec_cod + "&nbsp;\/&nbsp;"
	             + aDados.DEC_02.dec_cod
	if (aDados.DEC_03 != null ) {
	   _produto += "&nbsp;\/&nbsp;"  
	             + aDados.DEC_03.dec_cod
	}
	var _produto_descr = aDados.ITM.itm_descr;
	$("#txt_prd_cod").html(_produto).show();
	$("#txt_prd_descr").html(_produto_descr).show();
		
	// Tipo Item
	var _tipo_item = aDados.TPI.tpi_cod + "&nbsp;-&nbsp;"
	               + aDados.TPI.tpi_descr;
	$("#txt_tpi_descr").html(_tipo_item).show();
	
	// tipo Produto
	var _tipo_produto = aDados.TPP.tpp_cod + "&nbsp;-&nbsp;"
	                  + aDados.TPP.tpp_descr;
	$("#txt_tpp_descr").html(_tipo_produto).show();
	
	// Familia
	var _familia = aDados.FAM.fam_cod + "&nbsp;-&nbsp;"
	             + aDados.FAM.fam_descr;           
	$("#txt_fam_descr").html(_familia).show();
	
	// Sub/Familia
	var _sub_familia = aDados.SFA.sfa_cod + "&nbsp;-&nbsp;"
	                 + aDados.SFA.sfa_descr;	               	
    $("#txt_sfa_descr").html(_sub_familia).show();	
    
    // Declinacoes
	var _tdc_01 = aDados.TDC_01.tdc_cod + "&nbsp;-&nbsp;"
                + aDados.TDC_01.tdc_descr;	               	
	$("#txt_tdc_01_descr").html(_tdc_01).show();
	
	var _tdc_02 = aDados.TDC_02.tdc_cod + "&nbsp;-&nbsp;"
                + aDados.TDC_02.tdc_descr;
	$("#txt_tdc_02_descr").html(_tdc_02).show();
	
	if ( aDados.TDC_03 != null ) {
	   var _tdc_03 = aDados.TDC_03.tdc_cod + "&nbsp;-&nbsp;"
                   + aDados.TDC_03.tdc_descr;
	   $("#txt_tdc_03_descr").html(_tdc_03).show();
	} else {
	   $("#txt_tdc_03_descr").html("NÃO Usada").show();
	}
	
	var _dec_01 = aDados.DEC_01.dec_cod + "&nbsp;-&nbsp;"
                + aDados.DEC_01.dec_descr;	
	$("#txt_dec_01_descr").html(_dec_01).show();
	
	var _dec_02 = aDados.DEC_02.dec_cod + "&nbsp;-&nbsp;"
                + aDados.DEC_02.dec_descr;
	$("#txt_dec_02_descr").html(_dec_02).show();

	if ( aDados.DEC_03 != null ) {
	   var _dec_03 = aDados.DEC_03.dec_cod + "&nbsp;-&nbsp;"
                   + aDados.DEC_03.dec_descr;	
	   $("#txt_dec_03_descr").html(_dec_03).show();
    } else {
	   $("#txt_dec_03_descr").html("NÃO Usada").show();
	}
	
	// Natureza Item
	if ( aDados.NAT != null ) {
	   var _nat_descr = aDados.NAT.nat_cod + "&nbsp;-&nbsp;"
                      + aDados.NAT.nat_descr;
       $("#txt_nat_descr").html(_nat_descr).show();	
	} else {
	   $("#txt_nat_descr").html("Sem Natureza, rever").show();
	}
	
    // Origem Item
	if (aDados.ORI != null ) {
       var _ori_descr = aDados.ORI.ori_cod + "&nbsp;-&nbsp;"
                      + aDados.ORI.ori_descr;
       $("#txt_ori_descr").html(_ori_descr).show();
    } else {
       $("#txt_ori_descr").html("Sem Origem, rever").show();
    }
	
    // Grupo Item
    if ( aDados.GRU != null ) {
       var _gru_descr = aDados.GRU.gru_cod + "&nbsp;-&nbsp;"
                      + aDados.GRU.gru_descr;
       $("#txt_gru_descr").html(_gru_descr).show();
    } else {
       $("#txt_gru_descr").html("Sem Gruop, rever").show();
    }
    
    // Categoria item
    if ( aDados.CTG != null ) {
	   var _ctg_descr = aDados.CTG.ctg_cod + "&nbsp;-&nbsp;"
                      + aDados.CTG.ctg_descr;
       $("#txt_ctg_descr").html(_ctg_descr).show();
    } else {
       $("#txt_ctg_descr").html("Sem Categoria, rever").show();
    }
    
}