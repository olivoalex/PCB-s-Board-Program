//
// App4WEB: controle de navegacoao e botoes da tela
//
  var nav_modo = "NAV";
  var nav_modos = ["NAV", "INC", "ALT", "EXC", "APP"];	  
  var nav_modo_botoes = [[]];
	  nav_modo_botoes["NAV"] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];  // Mostra todos mesnos o NAVEGAR 
	  nav_modo_botoes["INC"] = [0, 2, 3, 20, 21, 22, 23, 25];                                                // Nao Exibe o Incluir
	  nav_modo_botoes["EXC"] = [0, 1, 2, 8, 9, 10, 20, 21, 22, 25];                                          // Nao Exibe o Navegar e botoes de navegacao
	  nav_modo_botoes["ALT"] = [0, 1, 3, 20, 21, 22, 24, 25];                                                // Nao exibe o Alterar
	  nav_modo_botoes["APP"] = [4, 5, 6, 7, 18, 19, 20, 23, 25, 26];                                                // Nao exibe o Alterar
  var nav_botoes = [
			 // 0               1                2               3
				"btn_navegar",  "btn_incluir",   "btn_alterar",  "btn_excluir",  // Acao
			 // 4               5                6               7
				"btn_primeiro", "btn_proximo",   "btn_anterior", "btn_ultimo",   // Navegacao
			 // 8               9                10         
				"btn_marcar",   "btn_desmarcar", "btn_inverter",                 // Acao linhas
			 // 11              12                
				"btn_bloquear", "btn_desbloquear",
			 // 13              14
				"btn_ativar",   "btn_desativar",
			 // 15              16
				"btn_validar",  "btn_invalidar",
			 // 17              
				"btn_guia",                                                     // Guia
			 // 18                     19
				"btn_pagina_corrente", "btn_pagina_total",                      // Rodape/Paginacao
			 // 20
				"btn_sair" ,                                                    // Sair
			 // 21           22
				"btn_ok"   , "btn_cancelar" ,                                   // Confirma e Cancela Operaoes
			 // 23            24              25           26  
				"btn_limpar", "btn_default", "btn_voltar", "btn_pesquisar"     				 
			   ];
  
  function modo_descricao(aModo) {
	 var _retorno = aModo + " - Não Identificado";		 
	 
	 switch ( aModo ) {
		case "NAV" :
		case "NAV" :
		case "PSQ" :
		case "SEL" :
		case "QRY" :
		   _retorno = "Nagevar/Pesquisar";
		   break;
		case "EXC" :
		case "DEL" :
		   _retorno = "Excluir";
		  break;
		case "ALT" :
		case "UPD" :
		   _retorno = "Alterar";
		  break;
		case "INS" :
		case "INC" :
		   _retorno = "Incluir";
	 }
	 
	 return _retorno;
  }
  
  function modo_seleciona(aModo) {
	 
	 // Ativa e desativas as Div´s para cada MODO
	 $("#div_navegar").show();			   
	 switch ( aModo ){
		case "NAV" :
		case "PSQ" :
		case "SEL" :
		case "QRY" :
		   nav_modo = "NAV";
		   break;
		case "INS" :
		case "INC" :
		   nav_modo = "INC";
		   break;
		case "EXC" :
		case "DEL" :
		   nav_modo = "EXC";
		   break;
		case "ALT" :
		case "UPD" :
		   nav_modo = "ALT";
		   break;
	 }
	 
	 // Determinando os botoes ativos e desativos para o MODO;
	 modo_seleciona_botoes(nav_modo);
	 
  }
  
  // o Modo aqui ja tem que estar em um dos 4 definidos como PADRAO que sao:
  // NAV, INC, EXC e ALT qq outra coisa da erro :)
  function modo_seleciona_botoes(aModo) {
	 // Varre o array de botoes por modo e ativa os botoes do MODO e desativas os outros
	 // 1o. Desativa todos		 
	 for (var i=0; i < nav_botoes.length; i++ ) {
		var _botao = "#" + nav_botoes[i];
		$(_botao).hide();
	 }
	 
	 // 2o. Ativa apenas os do MODO
	 var _verifica = false;
	 $("#pag_titulo").hide();
	 $("#pag_de").hide();

	 for ( var i=0; i < nav_modo_botoes[aModo].length; i++ )  {
		 var _pos = nav_modo_botoes[aModo][i];
		 var _botao = "#" + nav_botoes[_pos];			 
		 $(_botao).show();
		 // Se exibiu os botoes Pagina_total e Pagina_corrente, exibe os titulos no rodape: Pagina/De 
		 if ( _pos == 18 || _pos == 19 ) {
			 _verifica = true;
		 }
		 //alert("modo: "+ aModo + " Verifica: "+ _verifica + " posicao: " + _pos + " Botao: " + _botao + " Indice: "+ i);
	 }
	 
	 // Mostra os indicadores de pagina ?
	 if ( _verifica == true ) {
		$("#pag_titulo").show();
		$("#pag_de").show();
		// Verifica como ficam os botoes de navegacao
		navegacao_botoes(aModo);
	 }
  }
  
  function navegacao_botoes(aModo) {
	 
	 // Apenas de estiver em um modo com os botoes: 4, 5, 6 ou 7
	 var _verifica = false;
	 for ( var i=0; i < nav_modo_botoes[aModo].length; i++ )  {
		 var _pos = nav_modo_botoes[aModo][i];
		 // Se exibiu os botoes : 4,5 6 ou 7
		 if ( _pos == 4 || _pos == 5 || _pos == 6 || _pos == 7 ) {
			 _verifica = true;
			 break;
		 }
	 }
	 
	 // Foi exibido algum botao ?
	 if ( _verifica == true ) {
		// Mostra todos
		$("#btn_primeiro").removeAttr('disabled');
		$("#btn_proximo").removeAttr('disabled');
		$("#btn_anterior").removeAttr('disabled');
		$("#btn_ultimo").removeAttr('disabled');
		if ( pagina_total <= 1 )  {
			// Nao exibe os botoes
			$("#btn_primeiro").attr('disabled','disabled');
			$("#btn_proximo").attr('disabled','disabled');
			$("#btn_anterior").attr('disabled','disabled');
			$("#btn_ultimo").attr('disabled','disabled');
		} else {
			// Temos mais de 1 pagina
			if ( pagina_corrente == 1 ) {
			   // Nao mostra o proprio primeiro e o anterior
			   $("#btn_primeiro").attr('disabled','disabled');
			   $("#btn_anterior").attr('disabled','disabled');
			} else {
			   if ( pagina_corrente == pagina_total ) {
				   // nao exibe o Proximo nem o ultimo
				   $("#btn_proximo").attr('disabled','disabled');
				   $("#btn_ultimo").attr('disabled','disabled');
			   }
			}
		}
	 }
  }
  
  function montaParans(aModo) {
	 var _params = "";
	 
	 _params = "nav_modo/"+aModo
			 + "/guia_corrente/"+guia
			 + "/guia_locked/"+guia_lock
			 + "/pagina/"+pagina
			 + "/pagina_corrente/"+pagina_corrente
			 + "/pagina_total/"+pagina_total;
	 
	 return _params;
  }
  
  function modo_programa_titulo(aIcone, aDescricao, aHint) {	 
     var _html = "";
	 
	 if ( aIcone == "NONE" ) {
		aIcone = "fa fa-minus";
	 }
	 	 
	 _html = "&nbsp;<i class='" + aIcone + "' data-toggle='tooltip' data-placement='right' data-html='true' title='" + aHint + "'></i>&nbsp;";		
	          	
	 _html += aDescricao;
	 
	 $("#prg_titulo").html(_html);
	 
	 return _html;
  }
  
  function modo_titulo(aIcone, aDescricao, aHint) {	 
     var _html = "";
	 
	 if ( aIcone != "NONE" ) {
		aIcone = "fa fa-minus";
	 }
	 
	 _html = "<i class='" + aIcone + "' data-toggle='tooltip' data-placement='right' data-html='true' title='" + aHint + "'></i>&nbsp;";		
	 
	 _html = "&nbsp;";
	 
	 _html += aDescricao;
	 
	 $("#modo_titulo").html(_html);
	 
	 return _html;
  }  
  
  //alert("Essa tabela,<?php echo $view_tabela_nome;?> tem Telefone ? <?php echo $view_tem_telefone;?> e Endereco: <?php echo $view_tem_endereco;?>" );    	    
  function setGuia(aGuia) {
	 //alert("Nova Guia Selecionada: "+ aGuia);
	 guia = aGuia;	 
  }
  
  function mudaGuia(aGuia) {
	 // Varrendo a lista
	 if ( guia != aGuia ) {
		
		// Mudando para a guia solicitada :)
		// Jquery with Bootstrap :)
		var _guia_solicitada = "#"+aGuia.toLowerCase();
		var _guia_class_to_find = '.nav-tabs a[href="' + _guia_solicitada + '"]';
		$(_guia_class_to_find).tab('show');
	 
		// Muda a quia atual
		setGuia(aGuia);

	 }
  }

  function lockGuia(aGuia){
	//window.location.assign(aUrl);
	
	// Varrendo a lista
	var _lista = document.getElementById('dpd_guias');
	
	var _items = _lista.getElementsByTagName('LI');

	var _id_guia = "lst_"+aGuia;
	
	for(i=0; i<_items.length; i++) {
	   _items[i].className = "";
	   var _id = _items[i].id;
	   var t = _id.length - 4;
	   var _lck_image = "lck_" + _id.substr(4,t);
	   document.getElementById(_lck_image).className = "";		   
	   if ( _items[i].id == _id_guia ) {
		  document.getElementById(_lck_image).className = "fa fa-thumb-tack";
		  _items[i].className = "active";   
		  // Determina guia com lock, pode nao ser a atual
		  guia_lock = aGuia;
		  // titulo spn_lock_guia
	   }          
	}

	// Muda a guia automaticamente apenas se for diferent da ativa :)
	if ( guia != aGuia ) {
	   mudaGuia(aGuia);	
	}

  }
  
  function pageDateTime() {
	 var d = jsRetDate();
	 var h = jsRetTime();
	  
	 $("#page_data").text(d);
	 $("#page_hora").text(h);
   }