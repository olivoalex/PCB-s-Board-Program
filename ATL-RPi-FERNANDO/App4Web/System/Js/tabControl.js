/**
 * tabControl
 */
String.prototype.replaceAll = function(target, replacement) {
  return this.split(target).join(replacement);
};

           function oi(val) {
                alert("Oi --> "+val);
           }

           function Linha(aFlag, aVal) {
               if (aFlag == true) { 
                   marca(aVal);
               } else {
                   desmarca(aVal);
               }
           }

           function isArray(o){
        		return(typeof(o.length)=="undefined")?false:true;
       	   }

           function marca(aVal) {
        	   percorreTab('MARCAR','tabBas', 1, aVal);
        	   percorreTab('MARCAR','tabSeg', 0, aVal);
        	   percorreTab('MARCAR','tabRef', 0, aVal);
        	   percorreTab('MARCAR','tabEnd', 0, aVal);
        	   percorreTab('MARCAR','tabTel', 0, aVal);
        	   percorreTab('MARCAR','tabEml', 0, aVal);
        	   percorreTab('MARCAR','tabMai', 0, aVal);
        	   percorreTab('MARCAR','tabFtp', 0, aVal);
        	   percorreTab('MARCAR','tabDb' , 0, aVal);
        	   percorreTab('MARCAR','tabCfg', 0, aVal);
        	   percorreTab('MARCAR','tabCnt', 0, aVal);
        	   percorreTab('MARCAR','tabCom', 0, aVal);
        	   percorreTab('MARCAR','tabAud', 0, aVal);
           }

           function desmarca(aVal) {
        	   percorreTab('DESMARCAR','tabBas', 1, aVal);
        	   percorreTab('DESMARCAR','tabSeg', 0, aVal);
        	   percorreTab('DESMARCAR','tabRef', 0, aVal);
        	   percorreTab('DESMARCAR','tabEnd', 0, aVal);
        	   percorreTab('DESMARCAR','tabTel', 0, aVal);
        	   percorreTab('DEsMARCAR','tabEml', 0, aVal);
        	   percorreTab('DEsMARCAR','tabMai', 0, aVal);
        	   percorreTab('DEsMARCAR','tabFtp', 0, aVal);
        	   percorreTab('DEsMARCAR','tabDb' , 0, aVal);
        	   percorreTab('DESMARCAR','tabCfg', 0, aVal);
        	   percorreTab('DESMARCAR','tabCnt', 0, aVal);
        	   percorreTab('DESMARCAR','tabCom', 0, aVal);
        	   percorreTab('DESMARCAR','tabAud', 0, aVal);
           }
                      
           function inverte(aVal) {
        	   percorreTab('INVERTER','tabBas', 1, aVal);
        	   percorreTab('INVERTER','tabSeg', 0, aVal);
        	   percorreTab('INVERTER','tabRef', 0, aVal);
        	   percorreTab('INVERTER','tabEnd', 0, aVal);
        	   percorreTab('INVERTER','tabTel', 0, aVal);
        	   percorreTab('INVERTER','tabEml', 0, aVal);
        	   percorreTab('INVERTER','tabMai', 0, aVal);
        	   percorreTab('INVERTER','tabFtp', 0, aVal);
        	   percorreTab('INVERTER','tabDb' , 0, aVal);
        	   percorreTab('INVERTER','tabCfg', 0, aVal);
        	   percorreTab('INVERTER','tabCnt', 0, aVal);
        	   percorreTab('INVERTER','tabCom', 0, aVal);
        	   percorreTab('INVERTER','tabAud', 0, aVal);
           }
                      
           function marcaTodos() {
        	   percorreTab('MARCAR-TODOS','tabBas', 1, -1);
        	   percorreTab('MARCAR-TODOS','tabSeg', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabRef', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabEnd', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabTel', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabEml', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabMai', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabFtp', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabDb' , 0, -1);
        	   percorreTab('MARCAR-TODOS','tabCfg', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabCnt', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabCom', 0, -1);
        	   percorreTab('MARCAR-TODOS','tabAud', 0, -1);
           }

           function desmarcaTodos() {
        	   percorreTab('DESMARCAR-TODOS','tabBas', 1, -1);
        	   percorreTab('DESMARCAR-TODOS','tabSeg', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabRef', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabEnd', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabTel', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabEml', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabMai', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabFtp', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabDb' , 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabCfg', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabCnt', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabCom', 0, -1);
        	   percorreTab('DESMARCAR-TODOS','tabAud', 0, -1);
           }

           function inverteTodos() {
        	   percorreTab('INVERTER-TODOS','tabBas', 1, -1);
        	   percorreTab('INVERTER-TODOS','tabSeg', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabRef', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabEnd', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabTel', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabEml', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabMai', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabFtp', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabDb', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabCfg', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabCnt', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabCom', 0, -1);
        	   percorreTab('INVERTER-TODOS','tabAud', 0, -1);
           }

           function alteraEsse(aId) {
               //alert("Alterar ID#"+aId);
               inverte(aId);
           }

           function excluiEsse(aId) {

        	   var msg = "Excluir esse ID#"+aId+" ?";

               if (confirm(msg) == true ) {
        	      excluir(aId);
               } else {
            	  desmarca(aId);
               }
           }
           
           function ajaxExecutaAcao(aAcao, aTabela, aIdColuna, aId) {

        	   var values = { 
        			          "acao"   : aAcao,
        			          "tabela" : aTabela,
        			          "coluna" : aIdColuna,
        			          "id"     : aId 
        			        };
    	       //var aUrl = "/erp4web/Portal/cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
        	   //"www.clog.com.br/erp4web/Portal/cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
			   var aUrl = js_link + "cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
			   
			   //alert("URL-TabControl: " + aUrl);
			   
    	       if ( Array.isArray(aId)== true ) {
    	    	   for (var i=0; i < aId.length; i++) {
    	    		   aUrl += "/id/"+aId[i];
    	    	   }
    	       } else {
    	    	   aUrl += "/id/"+aId;
    	       }
    	       
    	       //alert("URL2: "+ aUrl);
    	       
        	   aRet = tabAjax(aUrl, 'post', aAcao, values);

           }
           
           function ajaxExecutaDados(aAcao, aBaseName, aTabela, aIdColuna, aId) {

		       //alert("ajaxExecutaDados: Acao: "+ aAcao + " Base: "+ aBaseName + " Tab: " + aTabela+ " Col: " + aIdColuna + " Id: " +aId);
			   
        	   // Array para eliminar duplicacao de dados para os casos das quias onde repatorios o IR/COd e Descricao qdo tem
        	   var aLista = new Array();
        	   var cLista = -1; // Contador
        	   
        	   //Identificando colunas a serem adicionadas para o NOVO/ALTERAR
        	   // Basename e aparte fixa do nome para identificar os inputs e selects referente aos atributos da tabela que estamos 
        	   // manipulando :)
				var eleInput = document.getElementsByTagName("INPUT");				
				
				// Controle de identificacao dos campos a serem usados pela funcao
				var tam = aBaseName.length;
				var sim = false;
				
				// Determinando parametros para o PHP 	
				var aParams = "";

				// Varrendo INPUT´s  verificando os nossos :)
				for ( var c=0; c < eleInput.length; c++ ) {
					// Para identificar se esse campo nao esta na lista, desconsidera ou nao
					sim = false;					
					// Pegando o campo do Formulario
					var aux = eleInput[c].name
					if ( aux.length <= 0 ) {
						aux = eleInput[c].id;
					}
					//Identificando se tem o mesmo basename
					if ( aux.substr(0,tam) == aBaseName) {
						var id_tam = aux.length - tam;
						var col = aux.substr(tam, id_tam);
						var val = eleInput[c].value;						
						// verificando se ja esta na lista
						sim = true;
						for ( var ctd=0; ctd < aLista.length; ctd++ ) {
							if ( col == aLista[ctd] ) {
								// Ja esta empilhada, desconsiderar
								sim = false;
								break;
							}
						}
						// Adicionar na lista e considerar como parametro
						if ( sim == true ) {
							cLista++;							
							if (val.length == 0) { val='0'; }
							aLista[cLista] = col;
							
							// Verificando se valor contem uma BARRA e converte para @BARRA@							
							val = val.replaceAll("\/","@BARRA@");
							val = val.replaceAll("#","@JVELHA@");
						
							aParams += "/"+col + "/" + encodeURIComponent(val);	
						}						
					}					
				}
				
				// Identificando os SELECTs
				var eleSelect = document.getElementsByTagName("SELECT");
				// Varrendo SELECT´s  verificando os nossos :)
				for ( var c=0; c < eleSelect.length; c++ ) {
					// Para identificar se esse campo nao esta na lista, desconsidera ou nao
					sim = false;					
					// Pegando o campo do Formulario
					var aux = eleSelect[c].name
					if ( aux.length <= 0 ) {
						aux = eleSelect[c].id;
					}
					//Identificando se tem o mesmo basename
					if ( aux.substr(0,tam) == aBaseName) {						
						var id_tam = aux.length - tam;
						var col = aux.substr(tam, id_tam);
						var val = eleSelect[c].value;
						// verificando se ja esta na lista
						sim = true;
						for ( var ctd=0; ctd < aLista.length; ctd++ ) {
							if ( col == aLista[ctd] ) {
								// Ja esta empilhada, desconsiderar
								sim = false;
								break;
							}
						}
						// Adicionar na lista e considerar como parametro
						if ( sim == true ) {
							cLista++;							
							if (val.length == 0) { val='0'; }
							aLista[cLista] = col;
							aParams += "/"+col + "/" + encodeURIComponent(val);	
						}
					}					
				}

//					var y = eleSelect[c].selectedIndex;
//					var o = eleSelect[c];
//  			        txt += eleSelect[c].id + " Value: "+ o.options[y].value+  " Text -> "+ o.options[y].text+ "\n";				
				//alert ("Parametros: "+ aParams);
        	   
        	   
        	   // Preparando Ajax
        	   var values = { 
        			          "acao"   : aAcao,
        			          "tabela" : aTabela,
        			          "coluna" : aIdColuna,
        			          "id"     : aId 
        			        };
    	       //var aUrl = "/erp4web/Portal/cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
			   //var aUrl = "www.clog.com.br/erp4web/Portal/cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
			   var aUrl = js_link + "cadastroAJAX/ajax/"+aAcao+"/acao/"+aAcao+"/tabela/"+aTabela+"/coluna/"+aIdColuna;
			   
   	    	   aUrl += aParams;
   	    	   
   	    	   //document.write(aUrl);

    	       //alert("URL1: "+ aUrl);
    	       
        	   aRet = tabAjax(aUrl, 'post', aAcao, values);

           }           

           function reverteClick(aObj) {
               if (aObj.checked == false ) {
                  aObj.checked = true;
               } else {
                   aObj.checked = false;
               }
           }

           function acaoNovoEditar(aAcao, aTabela, aIdColuna, aId) {
               // Fez alguma selecao             
               var txt_ask = "?";
               if (aAcao.toUpperCase() == "NOVO" || aAcao.toUpperCase() == "INCLUIR") {
       		      txt_ask = "Deseja realmente Incluir esses dados ?";
       		   } else {
       			  txt_ask = "Deseja realmente Alterar esses dados ?";    	  
       		   }
       		   bootbox.confirm(txt_ask, function(result) {
  				    lRes = "Operacao Sendo Executada, Aguarde !!!";
					lTip = "info";
       				if (result == false) {  			           
					   lTip = "error";
  			           lRes = "Operacao CANCELADA !!!";
  				    } 
					
                    // Exibe uma mensagem no canto inferior direito 
                    Lobibox.notify(lTip, {
                            position: 'top right',
                            msg: lRes
                    });

  				    if (result == true) {
  				        ajaxExecutaDados(aAcao, "fld_", aTabela, aIdColuna, aId) 
  				    }
  			   });             	   
           }           
           
		   function verificaMarcados(aAcao, aTabela, aIdColuna, aTab, aCol) {
               var tab = document.getElementById(aTab);
               var tabLin = tab.rows; 
               var linTot = tabLin.length;
               var linCol = tabLin[0].cells;
               var colTot = linCol.length;
               var listaSel = [];
			   var lRes="";
			   var lTip="";
			   
               var sLista = "";
               var sel = 0;
               for (var l=1; l < linTot; l++) {
                  var colunas = tabLin[l].cells;
                  var valColuna = colunas[aCol].innerHTML;
                  // Tratando o checkbox
                  var obj = tabLin[l].getElementsByTagName("INPUT");
                  if (obj.length > 0) {
                      if ( obj[0].checked == true ) {
                          sLista += "\nID# "+valColuna;
                          listaSel[sel] = valColuna;
                          sel++;
                      }
                  }
               }
			   
               // Fez alguma selecao ?
			   var _ret = [];
			   _ret["OK"] = sel;
			   _ret["LISTA"] = listaSel;
			   
			   return _ret;
			   
		   }	   
		   
           function acaoMarcados(aAcao, aTabela, aIdColuna, aTab, aCol) {
               var tab = document.getElementById(aTab);
               var tabLin = tab.rows; 
               var linTot = tabLin.length;
               var linCol = tabLin[0].cells;
               var colTot = linCol.length;
               var listaSel = [];
			   var lRes="";
			   var lTip="";
			   
               var sLista = "";
               var sel = 0;
               for (var l=1; l < linTot; l++) {
                  var colunas = tabLin[l].cells;
                  var valColuna = colunas[aCol].innerHTML;
                  // Tratando o checkbox
                  var obj = tabLin[l].getElementsByTagName("INPUT");
                  if (obj.length > 0) {
                      if ( obj[0].checked == true ) {
                          sLista += "\nID# "+valColuna;
                          listaSel[sel] = valColuna;
                          sel++;
                      }
                  }
               }
			   
               // Fez alguma selecao
               if ( sel != 0 ) {
				  
				  //alert("acaoMarcados(" + aAcao + ", " +  aTabela + ", " +  aIdColuna + ", " + aTab + ", " +  aCol + ")" );
				  
       			  bootbox.confirm("Deseja realmente "+aAcao.toLowerCase()+" essa lista de ID&#180;s ?", function(result) {
  				    lRes = "Operacao Sendo Executada, Aguarde !!!";
					lTip = "warning";
       				if (result == false) {
  			           lRes = "Operacao CANCELADA !!!";
					   lTip = "error"
  				    } 
  				    
					// Exibe uma mensagem no canto inferior direito 
					
                    Lobibox.notify(lTip, {
                            position: 'top right',
							title: " Atenção : ",
                            msg: lRes
                    });
					
  				    if (result == true) {
  				    	  ajaxExecutaAcao(aAcao, aTabela, aIdColuna, listaSel);
  				    }
  				  });             	   
               } else {
                  //alert("Selecione os ID's a serem  excluidos");

				  // Exibe uma mensagem no canto inferior direito 
                  Lobibox.notify('info', {
					position: 'top right',
					title: " Informação :",
                    msg: "Selecione a informação ANTES da operação.<br>Primeiro marque os ID&#180;s a serem manipulados."
                  });        			  
               }
           }
           
           function tabAcao(aAcao, aObjResponse) {

        	  var lMsg= "";
			  var lTip = "success";
        	  var ok = 0;
			  
			  var _refresh = false;
        	  
        	  if ( aObjResponse.id_ok.length > 0 ) {
        		 var msg = aObjResponse.message; 
        		 for ( var i=0; i< aObjResponse.id_ok.length; i++ ) {
        	        percorreTab(aAcao, 'tabBas', 1, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabSeg', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabRef', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabEnd', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabTel', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabEml', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabMai', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabFtp', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabDb' , 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabCfg', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabCnt', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabCom', 0, aObjResponse.id_ok[i]);
        	        percorreTab(aAcao, 'tabAud', 0, aObjResponse.id_ok[i]);      	        
        		 }
				 lTip = "success";
        		 lMsg = "Operacao Executada com Sucesso para ID&#180;s:<br>"+msg;
        		 ok++;
				 
				 Lobibox.notify(lTip, {
					position: 'top right',
					title: " Sucesso :",
                    msg: lMsg
                  });   
				  
				  //alert("Acao: "+aAcao);
				  
				  // Determina se a acao precisa de refresh
				  if ( aAcao == "EXCLUIR" || aAcao == "INCLUIR" || aAcao == "ALTERAR" ) {
					  _refresh = true;
				  }
        	  } 
        	  if ( aObjResponse.id_nok.length > 0 ) {
        		 var msg = aObjResponse.error; 
        		 for ( var i=0; i< aObjResponse.id_nok.length; i++ ) {
        	        desmarca(aObjResponse.id_nok[i]);
        	        //desmarca(aObjResponse.id_nok[i]);
        	        //desmarca(aObjResponse.id_nok[i]);
        	        //desmarca(aObjResponse.id_nok[i]);
        	        //desmarca(aObjResponse.id_nok[i]);
        	        //desmarca(aObjResponse.id_nok[i]);
        		 }
        		 lMsg = "*** ATENCAO: ID&#180;s com PROBLEMAS:<br>"+msg;				
        		 if ( ok > 0 ) {
				   lTip = "error";
        		 } else {
				   lTip = "error";
        		 }        		 
				 
    			 Lobibox.notify(lTip, {
					position: 'top right',
					title: " Erro :",
                    msg: lMsg
                  });   		    	 

        	  }
			  
			  // Precisa de refreah
			  if ( _refresh == true ) {
			     // Determina o que esse botao tem que fazer
		         var _lobiAdmin = $('body').data('lobiAdmin');
		        _params = montaParans("NAV");
		        _lobiAdmin.loadController(_controller, 'index', _action,  _params);
			  }
 		      
           }

           function tabAjax(aUrl, aType, aAcao, aDados) {
              $.ajax( {
               		   url : aUrl,
               		   type : aType,
               		   data : aDados,
               		   success : function (aResponse) {
               			   var aObjResponse = jQuery.parseJSON(aResponse);               			  
                   		   tabAcao(aAcao,aObjResponse);
               		   },
               		   error : function (x, textStatus, errorThrow) {               			   
                   		   console.log(textStatus, errorThrow);
               		   }
           		   }
           		   )
          }

		function alteraTabValor(aTab, aLinha, aColuna, aValor) {
		   var tab;
		   tab = document.getElementById(aTab);
		   var tabLin = tab.rows;
		   var linha = tabLin[aLinha];
		   var coluna = linha.cells;
		   coluna[aColuna].innerHTML = aValor;
		}

function percorreTab(aAcao, aTab, aCol, aVal) {
        	   
   try {
        	      var tab;
                  tab = document.getElementById(aTab);
               var tabLin = tab.rows; 
               var linTot = tabLin.length;
               var linCol = tabLin[0].cells;
               var colTot = linCol.length;

               for (var l=1; l < linTot; l++) {
            	   
                  var colunas = tabLin[l].cells;
                  var valColuna = colunas[aCol].innerHTML;
                  
                  // alert( "Passado: "+aVal+" Coluna: "+valColuna);
                  // Pega o valor absoluto pois pode estar bloqueado
				  
				  // tratando os negativos
				  // valColuna e aVal serao os valores PUROS, como estao
				  // _valor_coluna e _valor_argumento eh o valor sem sinal
				  var _valor_coluna = valColuna;
				  if ( _valor_coluna < 0 ) { _valor_coluna = _valor_coluna * -1;}
				  var _valor_argumento = aVal;
				  if ( _valor_argumento < 0 ) { _valor_argumento = _valor_argumento * -1;}
				  
				  //alert("Valores:\Coluna: "+ _valor_coluna + "\nArgumento: "+ _valor_argumento);
				  //alert("Colunas: " + colunas);
                  //if ( Math.abs(valColuna) == Math.abs(aVal) ) {
				  if ( Math.abs(_valor_coluna) == Math.abs(_valor_argumento) ) {
					  					
                	 if ( aAcao == 'EXCLUIR' ) {
                		//alert("Ecluindo .... "+aVal); 
                		//tab.deleteRow(l);
                		//l--;
                		//continue;
                		valColuna = "-"+valColuna;
                		aVal = "-"+aVal;
                		colunas[aCol].innerHTML = aVal;
                	 }
                	 if ( aAcao == 'BLOQUEAR' ) {
                 		//alert("Ecluindo .... "+aVal); 
                 		//tab.deleteRow(l);
                 		//l--;
                 		//continue;
                 		valColuna = "-"+valColuna;
                 		aVal = "-"+aVal;
                 		colunas[aCol].innerHTML = aVal;
                 	 }                	 
                	 if ( aAcao == 'RESTAURAR' ) {
                 		//alert("Ecluindo .... "+aVal); 
                 		//tab.deleteRow(l);
                 		//l--;
                 		//continue;
                		var aux = parseInt(valColuna)
                		aux = aux * -1;
                 		valColuna = aux.toString();
                 		aVal = valColuna;
                 		colunas[aCol].innerHTML = aVal;
                 	 }                	 
                  }

                  // Tratando o checkbox
				  // Vamos usr a nova coluna e nao a com valores PUROS conforme definido acima
				  //alert("Acao " + aAcao + "\nValores:\Coluna: "+ _valor_coluna + "\nArgumento: "+ _valor_argumento + "\nPuro: Coluna: " + valColuna + "\nArgumento: " +aVal);
                  var obj = tabLin[l].getElementsByTagName("INPUT");
                  if (obj.length > 0) {
                      if ( aAcao == "MARCAR-TODOS" ) { 
                         if ( _valor_coluna > 0 ) {
                        	 obj[0].checked = true;
                         } else {
                        	 obj[0].checked = true;
                         }
                      } else if ( aAcao == "DESMARCAR-TODOS" ) { 
                          obj[0].checked = false;
                      } else if (aAcao == "INVERTER-TODOS") {
                          if ( obj[0].checked == true ) {
                             obj[0].checked = false;
                          } else {
                             obj[0].checked = true;                        	                               
                          }
                      } else if ( aAcao == "MARCAR" ) { 
                          //if ( (_valor_coluna == _valor_argumento) && (_valor_coluna > 0)  ){
                        	  obj[0].checked = true; 
                          //}                             
                      } else if ( aAcao == "EXCLUIR" ) {
                    	  obj[0].checked = false; 
                      } else if ( aAcao == "BLOQUEAR" ) {
                    	  obj[0].checked = false;                     	  
                      } else if ( aAcao == "RESTAURAR" ) {
                    	  obj[0].checked = false;
                      } else if ( aAcao == "ATIVAR" ) {
                    	  obj[0].checked = false;
                      } else if ( aAcao == "DESATIVAR" ) {
                    	  obj[0].checked = false;
                      } else if ( aAcao == "VALIDAR" ) {
                    	  obj[0].checked = false;
                      } else if ( aAcao == "INVALIDAR" ) {
                    	  obj[0].checked = false;                    	  
                      } else if ( aAcao == "DESMARCAR" ) { 
                          if (_valor_coluna == _valor_argumento) {
                             obj[0].checked = false;
                          }
                      } else if ( aAcao == "INVERTER" ) { 
                          if (_valor_coluna == _valor_argumento) {
                              if ( obj[0].checked == true ) {
                                 obj[0].checked = false;
                              } else {
                            	  //if ( _valor_coluna > 0 ) {
                                     obj[0].checked = true;
                                  //}
                              }
                          }
                      }
                  }

                  var row_estilo = tabLin[l].className;
                  var estilo =  "normalPar";
                  var resto = l % 2;
                  if (  resto > 0 ) {

                      estilo =  "normalImpar";

                      if (aAcao == "MARCAR" || aAcao == "MARCAR-TODOS") { 
                         if ( parseInt(valColuna) > 0 ) {
                        	 estilo = "selecionadoImpar";
                         } else {
                        	 estilo = "excluidoImpar";
                         }
                      } else if ( aAcao == "DESMARCAR" || aAcao == "DESMARCAR-TODOS" ) {
                         if ( parseInt(valColuna) > 0 ) {
                        	 estilo = "normalImpar";
                         } else {
                        	 estilo = "excluidoImpar";
                         } 
                      } else if ( aAcao == "INVERTER" || aAcao == "INVERTER-TODOS") {
                         // Inerter a acao
                         if ( row_estilo == "normalImpar") {
                             if (parseInt(valColuna) > 0 ) {
                            	 estilo = "selecionadoImpar"; 
                             } else {
                            	 estilo = "excluidoImpar";
                             }
                         } else {
                        	 if (parseInt(valColuna) > 0) {
                        		 estilo = "normalImpar";	 
                        	 } else {
                        		 estilo = "excluidoImpar";
                        	 }
                             
                         }
                      } else  {
                    	if (parseInt(valColuna) > 0 ) {
                    		estilo = "normalImpar";
                    	} else {
                    		estilo = "excluidoImpar";
                    	}
                      }
                      
                  } else {

                    estilo =  "normalPar";

                    if (aAcao == "MARCAR" || aAcao == "MARCAR-TODOS") {
                       if (parseInt(valColuna) > 0 ) {
                    	   estilo = "selecionadoPar";
                       } else {
                    	   estilo = "excluidoPar";
                       }
                    } else if ( aAcao == "DESMARCAR" || aAcao == "DESMARCAR-TODOS" ) {
                       if (parseInt(valColuna) > 0 ) {
                    	   estilo = "normalPar";
                       } else {
                    	   estilo = "excluidoPar";
                       }                    	
                    } else  if ( aAcao == "INVERTER" || aAcao == "INVERTER-TODOS" ) {
                       // Inerter a acao
                       if ( row_estilo == "normalPar") {
                           if (parseInt(valColuna) > 0 ) {
                        	   estilo = "selecionadoPar"; 
                           } else {
                        	   estilo = "excluidoPar";
                           }
                       } else {
                    	   if ( parseInt(valColuna) > 0) {
                    		   estilo = "normalPar";   
                    	   } else {
                    		   estilo = "excluidoPar";
                    	   }                           
                       }                    
                    } else {
                      if (parseInt(valColuna) > 0 ) {
                   	   estilo = "normalPar";   
                      } else {
                   	   estilo = "excluidoPar";
                      }
                    }
                  }

                  if ( ( aAcao != "MARCAR-TODOS") && ( aAcao != "DESMARCAR-TODOS" ) && ( aAcao != "INVERTER-TODOS" ) ) {
                	  
                     if (valColuna == aVal) {
                         // Aplicao acao no indicado
                         tabLin[l].className = estilo;
                         break;
                     }
                  } else {                	  
                     // Aplicao acao em todos
                     tabLin[l].className = estilo;
                  }
               
               }// For

   } catch (err) {
     // Se nao achou tabela retorna
     return; 
   }
}