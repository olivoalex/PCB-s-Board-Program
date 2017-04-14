       // INICIO# Condicao Pagamento Dialog Button -->
       var natureza_condicao    = $('#fld_ncp_id').val();
 	   var natureza_grupo       = $('#fld_ncp_grupo').val();
 	   var natureza_parcelas    = $('#fld_cpg_parcelas').val();
 	   
       // Carregando os comboboxes para a janela de Dialog Condicao Pagamento
       // Natureza Condicao Pagamento
       var url = js_link + "dlgCondicaoPagamento_AJAX/ajax/index/acao/natureza_condicao/id/0/parametro/0/empresa/" + empresa +
		         "/filial/" + filial + 
		         "/usuario/" + usuario +
		         "/natureza_condicao/" + natureza_condicao + 
		         "/natureza_grupo/" + natureza_grupo + 
		         "/natureza_parcelas/" + natureza_parcelas + "/";
	   $.getJSON(url, function (aDados){		
		 if (aDados.dados.length > 0){ 	
		    var option = '<option value="0">Selecione</option>';
			$.each(aDados.dados, function(i, obj){
			    option += '<option value="'+obj.sigla+'">'+obj.nome+'</option>';
			}) 
			$('#fld_ncp_id').html(option).show();
		 }else{
			 dlgCondicaoPagamentoReset("natureza_condicao");
		 }
	   });
	   // Grupo de Natureza Condicao Pagamento
	   var url = js_link + "dlgCondicaoPagamento_AJAX/ajax/index/acao/natureza_grupo/id/0/parametro/0/empresa/" + empresa +
		         "/filial/" + filial + 
		         "/usuario/" + usuario +
		         "/natureza_condicao/" + natureza_condicao + 
		         "/natureza_grupo/" + natureza_grupo + 
		         "/natureza_parcelas/" + natureza_parcelas + "/";
	   $.getJSON(url, function (aDados){		
		 if (aDados.dados.length > 0){ 	
		    var option = '<option value="0">Selecione</option>';
			$.each(aDados.dados, function(i, obj){
			    option += '<option value="'+obj.sigla+'">'+obj.nome+'</option>';
			}) 
			$('#fld_ncp_grupo').html(option).show();
		 }else{
			 dlgCondicaoPagamentoReset("natureza_grupo");
		 }
	   });
	   // Condicao Pagamento Parcelas
	   var url = js_link + "dlgCondicaoPagamento_AJAX/ajax/index/acao/natureza_parcelas/id/0/parametro/0/empresa/" + empresa +
		         "/filial/" + filial + 
		         "/usuario/" + usuario +
		         "/natureza_condicao/" + natureza_condicao + 
		         "/natureza_grupo/" + natureza_grupo + 
		         "/natureza_parcelas/" + natureza_parcelas + "/";
	   $.getJSON(url, function (aDados){		
		 if (aDados.dados.length > 0){ 	
		    var option = '<option value="0">Selecione</option>';
			$.each(aDados.dados, function(i, obj){
			    option += '<option value="'+obj.sigla+'">'+obj.nome+'</option>';
			}) 
			$('#fld_cpg_parcelas').html(option).show();
		 }else{
			 dlgCondicaoPagamentoReset("natureza_parcelas");
		 }
	   });

	   // Mudou alguma coisa, deve solicitar nova pesquisa
	   $('#fld_ncp_id').change(function(e){
		  // Atualizando janela com dados conforme os filtros usados
		  dlgCondicaoPagamentoProcessarDados("SEL_CONDICAO", "FILTRA");
	   });

       // Mudou alguma coisa, deve solicitar nova pesquisa
	   $('#fld_ncp_grupo').change(function(e){
		  // Atualizando janela com dados conforme os filtros usados
		  dlgCondicaoPagamentoProcessarDados("SEL_CONDICAO", "FILTRA");
	   });

       // Mudou alguma coisa, deve solicitar nova pesquisa
	   $('#fld_cpg_parcelas').change(function(e){
		  // Atualizando janela com dados conforme os filtros usados
		  dlgCondicaoPagamentoProcessarDados("SEL_CONDICAO", "FILTRA");
	   });
	   
	   $('#btnSelCondicaoPagamentoFilter').click(function(e){		   	
		   // Acionando Javascript para carregar os dados na janela antes de abrir
   	       // Passar apenas a referencia da linha que sempre estara na primeira coluna da tabela para
	       // cada linha carregada
		   
	       // Forca carregar dados de uma pesquisa
	       dlgCondicaoPagamentoProcessarDados('SEL_CONDICAO', 'CARREGA');
	          
		   $('#janSelCondicaoPagamento').modal({ 
			            'backdrop'  : 'static',
			            'keyboard'  : true,
			            'show'      : true  
		   });
	   });

       //-- Atribuindo acao ao botao CANCEL da janela de Produto
       $('#btnSelCondicaoPagamentoFechar').click(function () {    
           $('#janSelCondicaoPagamento').modal('hide');
       });       
	   // FIM# Condicao Pagamento Dialog Button	   