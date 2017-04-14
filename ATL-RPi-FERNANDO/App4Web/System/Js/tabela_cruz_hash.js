    var aTabEstrutura = [];
    
    var aTabFootRegras = [];
    
    var aTabRowAction = [];
    
	var aTabRowActionBefore = [];
    
	var aTabCurrentRow = [];
	
    var aTabCurrentRowCell = [];
	
	var aTabBeforeRow = [];
	
    var aTabBeforeRowCell = [];
    
    var aTabRowCellSituacao = [];
	
	var aTabTotCells = [];      //Fixa total de colunas da tabela devido a primeira linha cel uma collspan :0)	

	var aTabRowCount = []; // Total de linhas de dados da tabela
	
	var aTabDados = [];
	
	var aTabAtivaUserCellFunction = [];
	
	var aTabFunction = [];
	
	var aTabPagina = [];         // pagina corrente
	
	var aTabPaginas = [];        // total de Paginas ? nao sei se precisamos saber
	
	var aTabRowPerPaginas = []; // Linhas por pagina

	var aTabPaginaOffset = []; // Linha inicial de cada pagina ou da corrente
	
	var aTabRowButtons = [];
	
	var aTabFunction = [];
	
	var jsBrowser = Browser(); // Carregnado o Browser do Usuario no Javascript
	
	function array_key_exists(aKey, aArray) {
	   var aRet = false;
	   for ( k in aArray ) {
		  if ( k == aKey ) {
		     aRet = true;
		     break;
		  }
	   }		
	   return aRet;
	}
	

	function tabBotoes(aMode, aAcao, aId, aBaseHint, aClassName) {
		this.mode = aMode; // ADD, DEL, UPD, CUS, BTN
		this.id = aId;
		this.name = aId;
		this.hint = "";
		this.acao = aAcao;
		this.baseHint = aBaseHint;
		this.className = aClassName;
	}
	
	function tabBotoesCustom(aMode, aAcao, aId, aDescr, aTipo, aBaseHint, aClassName) {
		this.mode = aMode; // ADD, DEL, UPD, CUS, BTN
		this.id = aId;
		this.name = aId;
		this.text = aDescr;
		this.type = aTipo;
		this.hint = "";
		this.acao = aAcao;
		this.baseHint = aBaseHint;
		this.className = aClassName;
	}
	
	function tabFootColunaRegra(aColunaTabelaPosicao, aOperacao, aColAOperar, aValorFixo ) {
		this.coluna = aColunaTabelaPosicao; // Para facilitar no futuro :)
		this.operacao = aOperacao; // SOMA, DIVIDE, MULTIPLICA, SUBTRAI, MEDIA, PERCENTUAL
		this.coluna_a_operar = aColAOperar;
		this.valorfixo = aValorFixo;
	}
	
	function tabColunaCSS(aConteudo, aOut, aOver, aSelected) {
		this.conteudo = aConteudo;
		this.out = aOut;
		this.over = aOver;
		this.selected = aSelected;
	}
	
	function tabColunaConteudo(aId, aCod, aNo, aControle, aConteudo, aSituacao, aReferencia) {
		this.id = aId;
		this.cod = aCod;
		this.no = aNo;
		this.controle = aControle;
		this.descr = aConteudo;		
		this.innerHTML = aConteudo;
		this.situacao  = aSituacao;
		this.referencia = aReferencia;		
	}
		
	function tabColuna(aId, aTipo, aConteudo) {
		this.id = aId;
		this.name = aId;
		this.tipo = aTipo;
		this.conteudo = aConteudo;
		this.innerHTML = aConteudo.innerHTML;
		
		this.tituloCSS             = new tabColunaCSS("","","","");
		
		this.dadosCSS              = [];
		this.dadosCSS["LIVRE"]     = new tabColunaCSS("","","","");
		this.dadosCSS["USADO"]     = new tabColunaCSS("","","","");
		this.dadosCSS["EMUSO"]     = new tabColunaCSS("","","","");
		this.dadosCSS["VAZIO"]     = new tabColunaCSS("","","","");
		this.dadosCSS["CHEIO"]     = new tabColunaCSS("","","","");
		this.dadosCSS["BLOQUEADO"] = new tabColunaCSS("","","","");
		this.dadosCSS["DESATIVO"]  = new tabColunaCSS("","","","");
		
		this.rodapeCSS             = new tabColunaCSS("","","","");
		
		this.changeCSSByTipo = function(aTipo, aConteudo, aOut, aOver, aSelected) {			
			aOpcao = aTipo.toUpperCase();
			switch ( aOpcao ) {
				case "TITULO":
				   this.tituloCSS.conteudo = aConteudo;
				   this.tituloCSS.out = aOut;
				   this.tituloCSS.over = aOver;
				   this.tituloCSS.selected = aSelected;
				   break;
				case "RODAPE":
				   this.rodapeCSS.conteudo = aConteudo;
				   this.rodapeCSS.out = aOut;
				   this.rodapeCSS.over = aOver;
				   this.rodapeCSS.selected = aSelected;
				   break;				
				case "USADO":
				   this.dadosCSS["USADO"].conteudo = aConteudo;
				   this.dadosCSS["USADO"].out = aOut;
				   this.dadosCSS["USADO"].over = aOver;
				   this.dadosCSS["USADO"].selected = aSelected;
				   break;
				case "EMUSO":
					   this.dadosCSS["EMUSO"].conteudo = aConteudo;
					   this.dadosCSS["EMUSO"].out = aOut;
					   this.dadosCSS["EMUSO"].over = aOver;
					   this.dadosCSS["EMUSO"].selected = aSelected;
					   break;
				case "VAZIO":
					   this.dadosCSS["VAZIO"].conteudo = aConteudo;
					   this.dadosCSS["VAZIO"].out = aOut;
					   this.dadosCSS["VAZIO"].over = aOver;
					   this.dadosCSS["VAZIO"].selected = aSelected;
					   break;
				case "CHEIO":
					   this.dadosCSS["CHEIO"].conteudo = aConteudo;
					   this.dadosCSS["CHEIO"].out = aOut;
					   this.dadosCSS["CHEIO"].over = aOver;
					   this.dadosCSS["CHEIO"].selected = aSelected;
					   break;					   
				case "BLOQUEADO":
				   this.dadosCSS["BLOQUEADO"].conteudo = aConteudo;
				   this.dadosCSS["BLOQUEADO"].out = aOut;
				   this.dadosCSS["BLOQUEADO"].over = aOver;
				   this.dadosCSS["BLOQUEADO"].selected = aSelected;
				   break;
				case "DESATIVO":
					   this.dadosCSS["DESATIVO"].conteudo = aConteudo;
					   this.dadosCSS["DESATIVO"].out = aOut;
					   this.dadosCSS["DESATIVO"].over = aOver;
					   this.dadosCSS["DESATIVO"].selected = aSelected;
					   break;					   
				case "LIVRE":									
				default: // sera dados
				   this.dadosCSS["LIVRE"].conteudo = aConteudo;
				   this.dadosCSS["LIVRE"].out = aOut;
				   this.dadosCSS["LIVRE"].over = aOver;
				   this.dadosCSS["LIVRE"].selected = aSelected;
			}
		} 
	}
			
    function setTabRowAction(aTabId, aAction,aRowCell) {
      aTabRowAction[aTabId] = aAction;
	  
	  //alert("Acao esta como: "+ aTabRowAction);
    }
	
    function getTabRowBeforeAction(aTabId) {
      
	  //alert("Acao esta como: "+ aTabRowActionBefore);
	  
	  return aTabRowActionBefore[aTabId];
	  	  
    }	
	
	function getTabRowAction(aTabId) {
      return aTabRowAction[aTabId];
    }

	function setTabCurrentRow(aTabId, aRow){
      aTabCurrentRow[aTabId] = aRow.rowIndex;
	  //alert("Current Row: "+ aTabCurrentRow);
	}
	
	function getTabCurrentRow(aTabId) {
	   return aTabCurrentRow[aTabId];
	}
	
	function tabResetColors(aTabId) {
		
		var aTab = document.getElementById(aTabId);
		
		var iTit = 0;                  // Index da linha de titulo
		var iFot = aTab.rows.length-1; // Index da linha de rodape
			   	   
	    var rCurrent  = 0;	   
		var idCurrent = "";
		var sitCurrent = "LIVRE";
		   
		for ( var r=0; r < aTab.rows.length; r++) {
			
		   for ( var c=0; c<= aTabTotCells[aTabId]; c++) {
	          
			  // Titulo
			  if ( r==iTit ) {
			     aTab.rows[r].cells[c].className = aTabEstrutura[aTabId][c].tituloCSS.conteudo + " " + aTabEstrutura[aTabId][c].tituloCSS.out + " cssHand";  
			  } else {
				// Rodape
				if ( r==iFot ) {
				   aTab.rows[r].cells[c].className = aTabEstrutura[aTabId][c].rodapeCSS.conteudo + " " + aTabEstrutura[aTabId][c].rodapeCSS.out + " cssHand";  
				} else {
				   rCurrent  = aTab.rows[r];	   
				   idCurrent = rCurrent.id;
				   sitCurrent = "LIVRE";
			       // Pegando Situacao da Coluna CURRENT
			       if ( array_key_exists(idCurrent, aTabDados) == true ) {
			          sitCurrent = aTabDados[aTabId][idCurrent][c].situacao;  
			       }
			       // Dados
				   aTab.rows[r].cells[c].className = aTabEstrutura[aTabId][c].dadosCSS[sitCurrent].conteudo + " " + aTabEstrutura[aTabId][c].dadosCSS[sitCurrent].out + " cssHand";  
				}
			  }
		   }
		}		
	}
	
	function setTabRowCellSelected(aTabId, aCell) {
       
	   // Determina linhas a serem tratadas
	   setTabCurrentRowCell(aTabId, aCell);
	   
	   //Tabela
	   var aTab = aCell.offsetParent;	   	  
	   var iTit = 0;                  // Index da linha de titulo
	   var iFot = aTab.rows.length-1; // Index da linha de rodape

	   // Pegando as Linha
	   var rBefore    = aTabBeforeRow[aTabId];
	   var rTabBefore = aTab.rows[rBefore];	   
	   var idBefore   = rTabBefore.id;
	   var cBefore    = aTabBeforeRowCell[aTabId];
	   var sitBefore  = "LIVRE";
	   if ( array_key_exists(idBefore, aTabDados[aTabId]) ) {
	      sitBefore  = aTabDados[aTabId][idBefore][cBefore].situacao;     
	   }	   
	   
	   var rCurrent    = aTabCurrentRow[aTabId];
	   var rTabCurrent = aTab.rows[rCurrent];	   
	   var idCurrent   = rTabCurrent.id;
	   var cCurrent    = aTabCurrentRowCell[aTabId];
	   var sitCurrent  = "LIVRE";
	   if ( array_key_exists(idCurrent, aTabDados[aTabId]) ) {
	      sitCurrent = aTabDados[aTabId][idCurrent][cCurrent].situacao;     
	   }	   
	   
	   // Marcar Colunas Corrente e Before com o devido CSS conforme a situacao e a linha Header/Footer e Dados
  	   // Percorre todas as linhas da tabela
	   var cssConteudo = "";
	   var cssEvento   = "";
	   var sitRowCell  = "LIVRE";
	   for ( var r=0; r < aTab.rows.length; r++) {
		  // Percorre todas as colunas da tabela
          for ( var c=0; c <= aTabTotCells[aTabId]; c++) {
              
        	  // Pegando situacao da linhaXcoluna
        	  sitRowCell = "LIVRE";
        	  idRowCell = aTab.rows[r].id
        	  if (array_key_exists(idRowCell, aTabDados[aTabId]) ){
        		  sitRowCell = aTabDados[aTabId][idRowCell][c].situacao;
        	  }
        	  
        	  if ( r != rCurrent ) {
        		  switch (r) {
        	         case iTit :
        		         cssConteudo = aTabEstrutura[aTabId][c].tituloCSS.conteudo;
        		         cssEvento   = aTabEstrutura[aTabId][c].tituloCSS.out;
        		         break;
        	         case iFot :
        		         cssConteudo = aTabEstrutura[aTabId][c].rodapeCSS.conteudo;
        		         cssEvento   = aTabEstrutura[aTabId][c].rodapeCSS.out;
        		         break;
        	         default :
        		         cssConteudo = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].conteudo;
        	             cssEvento   = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].out;
        	      }        		  
        	  } else {
	              switch (r) {
	           	      case iTit :
	           		      cssConteudo = aTabEstrutura[aTabId][c].tituloCSS.conteudo;
	           		      cssEvento   = aTabEstrutura[aTabId][c].tituloCSS.selected;
	           		      break;
	           	      case iFot :
	           		      cssConteudo = aTabEstrutura[aTabId][c].rodapeCSS.conteudo;
	           		      cssEvento   = aTabEstrutura[aTabId][c].rodapeCSS.selected;
	           		      break;
	           	      default :
	           		      cssConteudo = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].conteudo;
	           	          cssEvento   = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].over;
	           	  }        		  
              }
          
              if ( c == cCurrent ) {
                 switch (r) {
              	    case iTit :
              	       cssConteudo = aTabEstrutura[aTabId][c].tituloCSS.conteudo;
              		   cssEvento   = aTabEstrutura[aTabId][c].tituloCSS.over;
              		   break;
              	    case iFot :
              		   cssConteudo = aTabEstrutura[aTabId][c].rodapeCSS.conteudo;
              		   cssEvento   = aTabEstrutura[aTabId][c].rodapeCSS.over;
              		   break;
              	    default :
              		   cssConteudo = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].conteudo;
              	       cssEvento   = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].over;
                 }
              }   
              
              if ( r == rCurrent && c == cCurrent ) {
                  switch (r) {
            	    case iTit :
            	       cssConteudo = aTabEstrutura[aTabId][c].tituloCSS.conteudo;
            		   cssEvento   = aTabEstrutura[aTabId][c].tituloCSS.selected;
            		   break;
            	    case iFot :
            		   cssConteudo = aTabEstrutura[aTabId][c].rodapeCSS.conteudo;
            		   cssEvento   = aTabEstrutura[aTabId][c].rodapeCSS.selected;
            		   break;
            	    default :
            		   cssConteudo = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].conteudo;
            	       cssEvento   = aTabEstrutura[aTabId][c].dadosCSS[sitRowCell].selected;
               }            	  
              }
              
              aTab.rows[r].cells[c].className = cssConteudo + " " + cssEvento + " cssHand";
          }
	   }
	}
	
	function setTabCurrentRowCell(aTabId, aCell) {
	   
	   // Guarda posicao como anterior
	   aTabBeforeRow[aTabId] = aTabCurrentRow[aTabId];
	   aTabBeforeRowCell[aTabId] = aTabCurrentRowCell[aTabId];
	   
	   // Determina a nova posicao
	   aTabCurrentRowCell[aTabId] = aCell.cellIndex;
	   aTabCurrentRow[aTabId] = aCell.parentNode.rowIndex;	   	  
	}

	function setTabCurrentRowCellClick(aTabId, aCell) {
	   setTabCurrentRowCell(aTabId, aCell);
	   if ( aTabAtivaUserCellFunction[aTabId] == true ) {
		   //alert("Cell Anterior index is: " + aTabBeforeRowCell[aTabId] + "\nRow Anterior : "+ aTabBeforeRow[aTabId] + "\nCurrent Cell index is: " + aTabCurrentRowCell[aTabId] + "\nRow: "+ aTabCurrentRow[aTabId]);		   
		   tabRowCellOperacao(aTabId, "CLICK", aTabCurrentRow[aTabId], aTabCurrentRowCell[aTabId]);
	   }
	}
	
	function setTabCurrentRowButton(aTabId, aAcao, aButton) {
		setTabRowAction(aTabId, aAcao);
		
		// Pai do Botao eh a celula q ele esta :)
		//aCell = aButton.offsetParent;
		aCell = aButton.parentElement;
		
		setTabCurrentRowCell(aTabId, aCell);
		
		tabRowCellOperacao(aTabId, aAcao, aTabCurrentRow[aTabId], aTabCurrentRowCell[aTabId]);
	}
	
	function getTabCurrentRowCell(aTabId) {
	   return aTabCurrentRowCell[aTabId];
	}	
	
	function exeTabRowAction(aTabId, aRowId, aDados) {
      
	  aTabela = document.getElementById(aTabId);
      
	  aTabRowActionBefore[aTabId] = aTabRowAction[aTabId];
			   
	  if ( aTabRowAction[aTabId] == "INSERT" ) {
         if ( window.confirm("Adiciona Nova Linha ?") ) {
			   tabRowInsert(aTabId, aRowId, aDados);
               aTabRowAction[aTabId]="None";               
			   return true;
         }         
	  }   
	  
	  if ( aTabRowAction[aTabId] == "NEW" || aTabRowAction[aTabId] == "ADD" ) {     
	     tabRowInsert(aTabId, aRowId, aDados);
         aTabRowAction[aTabId]="None";
		 return true;
	  }

      //alert("Acao: "+ aTabRowAction+ " setTabCurrentRow: "+aTabCurrentRow+" Tabe Id/Name: "+aTabId + "tabela id "+ aTabela.id+" Cell index is: " + aTabCurrentRowCell+" Row: "+aTabCurrentRow+ " aRow :"+aRow.innerHTML);    
	  
      if ( aTabRowAction[aTabId] == "DELETE" ) {
         if (aTabCurrentRow[aTabId] >= 0) {
            if ( window.confirm("Ecluir Linha ?") ) {
			   //alert("Current Cell index is: " + aTabCurrentRowCell + " Row: "+ aTabCurrentRow);
               tabRowDelete(aTabId, aTabCurrentRow[aTabId]);
               aTabRowAction[aTabId]="None"; 
			   return true;
            }
         }      
      }      
	  
      if ( aTabRowAction[aTabId] == "UPDATE" ) {
         if (aTabCurrentRow[aTabId] >= 0) {
            if ( window.confirm("Alterar Linha ?") ) {
			   //alert("Current Cell index is: " + aTabCurrentRowCell + " Row: "+ aTabCurrentRow);
               tabRowUpdate(aTabId, aTabCurrentRow[aTabId], aRowId, aDados);
               aTabRowAction[aTabId]="None"; 				
			   return true;
            }
         }      
      }
	  
	  return false;
	  
    }
	
	function iniTabParans(aTabId, aEstrutura) {
	   // Determinando a estrutura da Tabela
	   aTabEstrutura[aTabId] = aEstrutura;
	   
	   //Fixa total de colunas da tabela devido a primeira linha cel uma collspan :0)	
	   aTabTotCells[aTabId] = aTabEstrutura[aTabId].length-1;
	   
	   // Acao
       aTabRowAction[aTabId] = "None";
	   
	   //Acao Anterior
	   aTabRowActionBefore[aTabId] = aTabRowAction[aTabId];
	   
	   // Linha Corrente
       aTabCurrentRow[aTabId] = 0;
       
	   // Celula Corrente ou Coluna Corrente
	   aTabCurrentRowCell[aTabId] = 0;
	   
	   // Total de linhas de dados da tabela, iniciando do ZERO
	   aTabRowCount[aTabId] = -1; 
	   
	   // Linha Anterior
	   aTabBeforeRow[aTabId] = 0;
	   
	   //Celula ou Coluna Anterior
       aTabBeforeRowCell[aTabId] = 0;
       
       // Nao chama funcao externa so quando atribuir true para o controle
       aTabAtivaUserCellFunction[aTabId] = false;
       
       // Funcao 
       aTabFunction[aTabId] = [];
       
       // Botoes
       aTabRowButtons[aTabId] = [];
       
       //Paginacao
	   aTabPagina[aTabId] = 0;         // pagina corrente
	
	   aTabPaginas[aTabId] = 0;        // total de Paginas ? nao sei se precisamos saber
	
	   aTabRowPerPaginas[aTabId] = 10; // Linhas por pagina
	   
	   aTabPaginaOffset[aTabId] = 0; // Linha inicial de cada pagina
	   	   
	}
	
	function setTabFunction(aTabId, aFunc) {
		aTabFunction[aTabId] = aFunc;
	}
	
	function tabDrop(aTabId) {	   
		var aTab = document.getElementById(aTabId);

		if ( aTab != null ) {
		   while(aTab.hasChildNodes()) {
		      aTab.removeChild(aTab.firstChild);
		   }
		}
		// Inicializa array de dados
		aTabDados[aTabId] = [];
	}
	
	function tabRowUpdate(aTabId, aRowNumber, aRowId, aDados) {
       var aTab = document.getElementById(aTabId);
       
	   if(!aTab && !aTab.rows)
          return;
	  
       var rTab = aTab.rows; 

       //alert("Id: "+aTabId+" Linhas -> "+ rTab.length+" Alterar-> "+aRowNumber);
	  
       if( aRowNumber > rTab.length)
          return;
	  
	   for ( var c=0; c <= aTabTotCells[aTabId]; c++ ) {
		  if ( c > 0 ) {
	         aTab.rows[aRowNumber].cells[c].innerHTML = (c * (aRowNumber+1) );
		  }
	   }	   
    }
	
	function tabRowDelete(aTabId, aRowNumber) {
       var aTab = document.getElementById(aTabId);
       
	   if(!aTab && !aTab.rows)
          return;
	  
       var rTab = aTab.rows; 

       //alert("Id: "+aTabId+" Linhas -> "+ rTab.length+" Apagar-> "+aRowNumber);
	  
       if( aRowNumber > rTab.length)
          return;
	 
       aTab.deleteRow(aRowNumber);  

	   // Faz Linha corrente a anterior
       aTabCurrentRow[aTabId]--;  	
	   if ( aTabCurrentRow[aTabId] < 0 ) {
		  aTabCurrentRow[aTabId]=0;
	   }

	   // Linhas da tabela
       aTabRowCount[aTabId]--;	   
       
       tabResetColors(aTabId);          	   
    }    
	
	function tabHeadInsert(aTabId) {
	  var aTab = document.getElementById(aTabId);
	  
	  var rTab = aTab.insertRow(0); // Linha para titulo
	  
	  // Atribuido dados para busca em pesquisas
	  rTab.id = "rHead";
	  rTab.name = rTab.id;
	  
	  // Apenas colocando algo na celula 
      for (var i = 0; i <= aTabTotCells[aTabId]; i++) { 

  	     var cTab = rTab.insertCell(i);
		 
         cTab.onclick=function(){setTabCurrentRowCell(aTabId, this);};
		 cTab.onmouseover=function(){setTabRowCellSelected(aTabId, this);};
         
		 cTab.className = aTabEstrutura[aTabId][i].tituloCSS.conteudo + " " + aTabEstrutura[aTabId][i].tituloCSS.out + " cssHand";  	  
		 
		 cTab.innerHTML = aTabEstrutura[aTabId][i].innerHTML;
	  }
	  
	}

	function tabFootInsert(aTabId) {
	  var aTab = document.getElementById(aTabId);
	  
	  var lTab = aTab.rows.length
	  	 
	  var rTab = aTab.insertRow(aTab.rows.length); // Linha para titulo
	  
	  // Atribuido dados para busca em pesquisas
	  rTab.id = "rFoot";
	  rTab.name = rTab.id;
	  
	  // Apenas colocando algo na celula 
      for (var i = 0; i <= aTabTotCells[aTabId]; i++) { 

  	     var cTab = rTab.insertCell(i);
		 
         cTab.onclick=function(){setTabCurrentRowCell(aTabId, this);};
		 cTab.onmouseover=function(){setTabRowCellSelected(aTabId, this);};
         
		 cTab.className = aTabEstrutura[aTabId][i].rodapeCSS.conteudo + " " + aTabEstrutura[aTabId][i].rodapeCSS.out + " cssHand";  	  
		 
		 cTab.innerHTML = "&nbsp;";
	  }
	  
	}
	
	function tabFootAtualizaCalculos(aTabId) {
	   // Deve percorrer a tabela e conforme as regras de RODAPE deve aplicar a cada uma das
	   // colunas
	   var _tem_regra = aTabFootRegras[aTabId];
	   
	   if ( _tem_regra != null ) {
		   var aTab = document.getElementById(aTabId);
		   var rTab = aTab.rows;
		   var _linha_header = 0;
 		   var _linha_footer = rTab.length-1;
		   
	       // Aplicar as regras apenas se existirem regras :)
	       for ( var i in aTabFootRegras[aTabId] ) {
	    	  _coluna_id = i;
	    	  for ( var c=0; c < aTabFootRegras[aTabId][i].length; c++) {
	    		  
	    		  var pos = aTabFootRegras[aTabId][i][c]; 
	    		  // Percorrer a Tabela nas colunas indicadas e OPERAR os valores
	    		  // alert("\n ID: " + i + " Operacao: " + pos.operacao + " Coluna: " + pos.coluna_a_operar;
	  
	    		  var _coluna = pos.coluna; // indice da coluna na tabela
	    		  var _coluna_valor = 0;
	    		  var _coluna_valor_operacao = 0;
	    		  
	    		  // Apenas coloar um valor fixo no rodape
                  if ( pos.operacao == "FIXED" ) {                	                  	 
                      rTab[_linha_footer].cells[_coluna].innerHTML = pos.valorfixo;
                      continue;
                   }

	    		  if( rTab.length > 0) {
	    	    	 // Sempre desconsidero a primeira e ultima pois sao HEADER e FOOTER da coluna, espero :)
                     for ( var r = 1; r < rTab.length-1; r++ ) {
                        
                    	_coluna_valor = parseFloat(rTab[r].cells[_coluna].innerHTML);
                    	if ( isNaN(_coluna_valor) || _coluna_valor == null) {
                    		_coluna_valor = 0;
                    	}
                        
                        if ( pos.operacao == "SOMA" ) {
                           _coluna_valor_operacao = _coluna_valor_operacao + _coluna_valor;
                        }
                     }
                     // Atruibuindo resultado na celula calculada
                     rTab[_linha_footer].cells[_coluna].innerHTML = _coluna_valor_operacao.toFixed(4).toString();
	    	      }	    		  	    		  
	    	  }
	      }
	   }
	}
	
	function tabRowInsert(aTabId, aRowId, aDados) {
	  
	  // Gerenciando uma tabela
	  //alert("Id Tabela: " + aTabId);
	
      var aTab = document.getElementById(aTabId);
	  
	  // Pegando o tbody
	  //bTab = aTab.tBodies[0];	  
	  
      //var rTab = aTab.insertRow(aTab.rows.length);1,2,3,4
	  var rTab = aTab.insertRow(1); // da ultima para a primeira

	  // Atribuido dados para busca em pesquisas
	  rTab.id = aRowId;
	  rTab.name = aRowId;
	  
      //rTab.onclick=function(){setTabCurrentRow(aTabId, this); exeTabRowAction(aTabId, this)};   	  
      rTab.onclick=function(){setTabCurrentRow(aTabId, this);};

      setTabCurrentRow(aTabId, rTab);
      
	  aTabRowCount[aTabId]++;
	  
      // Busca celulas da linha
	  // document.getElementById(id).rows[0].cells.length;
      var nCols = aTabTotCells[aTabId]; 
	  
      // Definindo e inicializando ARRAY
      var vCols = [];
      var iCols = [];
      var sCols = [];
      
	  // Apenas colocando algo na celula 
      if ( aTabRowAction[aTabId] == "NEW" ) {
    	 
    	 aTabDados[aTabId][aTabCurrentRow[aTabId]] = null;
    	 
         for (var i = 0; i <= nCols; i++) {  
    	   var box = i * 10;
		   vCols[i] = "015." + aTabCurrentRow[aTabId] + "." + box;
		   iCols[i] = "id_"+i;
		   sCols[i] = "LIVRE";
	     }
      } else {
    	 // Pegar dados da aDados recebida como parametro
    	 aTabDados[aTabId][aRowId] = aDados;
         for (var i = 0; i <= nCols; i++) {  
  		   vCols[i] = aTabDados[aTabId][aRowId][i].innerHTML;
  		   iCols[i] = aTabDados[aTabId][aRowId][i].id;
  		   sCols[i] = aTabDados[aTabId][aRowId][i].situacao;
  	     }    	 
      }
      
      // manipulando os botoes da linha
      var eleBotoes = [];
      var eleInd = -1; // Lista Vazia      
      for ( var i in aTabRowButtons[aTabId]) {
    	  
    	  for ( var b=0; b < aTabRowButtons[aTabId][i].length; b++ ) {
    	      
    		 var eleCUS = null;
			 
    		 var aClass = aTabRowButtons[aTabId][i][b].className;
			 var aHint = aTabRowButtons[aTabId][i][b].baseHint;
			 var aAcao = aTabRowButtons[aTabId][i][b].acao;
			 
			 var aTipoBotao = "NORMAL";
			 var aDescricao = "";
			 if ( !!aTabRowButtons[aTabId][i][b].type ) {
				 aTipoBotao = aTabRowButtons[aTabId][i][b].type;
				 aDescricao = aTabRowButtons[aTabId][i][b].text;
			 }
			 aDescricao += " - " + jsBrowser;
			 
    		 if (jsBrowser == "Chrome" || jsBrowser == "Firefox") {    		 
      	        // Crhome e Firefox

     	        switch (aTipoBotao) {
     	           case "BTN":
     	           case "BUTTON":
     	        	  // Botao
     	        	  eleCUS = "&nbsp;"
     	        		     + "<button class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='bottom' title='"+ aHint + " " + aRowId + "' "
     	 	                 +    " onclick='setTabCurrentRowButton(\"" + aTabId + "\", \"" + aAcao + "\",this);'>"     	   	                 
     	   	                 +    " <i class='" + aClass +"'>&nbsp;"      	 	                
     	 	                 +    aDescricao 
     	 	                 +    "</i>&nbsp;";     	        	   
     	   	                 + "</button>";
     	   	          break;
     	           default:
     	        	   // Normal
     	              eleCUS = "&nbsp;" +
     	    	          "<i class='" + aClass +"' data-toggle='tooltip' data-placement='bottom' " +
     	                  "title='" + aHint + "'" + aRowId + 
     	                  "' onclick='setTabCurrentRowButton(\"" + aTabId + "\", \"" + aAcao + "\",this);'></i>&nbsp;";
     	        }  // tipo de botao     	  
     	        
    		 } else {
     	        eleCUS = document.createElement('i');
    	      
    		    eleCUS.className = aTabRowButtons[aTabId][i][b].className;
    	      
    	        switch (i) {
  	               case "ADD" :
	                eleCUS.onclick=function(){setTabCurrentRowButton(aTabId, 'INSERT',this)};
	                break;
	               case "DEL" :
	                eleCUS.onclick=function(){setTabCurrentRowButton(aTabId, 'DELETE',this)};
	                break;
    	           case "UPD" :
    	            eleCUS.onclick=function(){setTabCurrentRowButton(aTabId, 'UPDATE',this)};
    	            break;
    	           default :
    	        	// Customizado
    	        	eleCUS.onclick=function(){setTabCurrentRowButton(aTabId, 'CUSTOM',this)};
    	        	break;
    	        }
    	     }
    	     eleInd++; // Adicionando na lista
    	     eleBotoes[eleInd] = eleCUS;
    	  }
      }
      
      for (var i = 0; i <= nCols; i++) { 
         var cTab = rTab.insertCell(i);

         //  Para celula de ACAO nao poe evento click
         if ( i != 0 ) {
            cTab.onclick=function(){setTabCurrentRowCellClick(aTabId, this);};
         }
		 cTab.onmouseover=function(){setTabRowCellSelected(aTabId, this);};
		 cTab.id = iCols[i];
		 cTab.name = iCols[i];
         
		 var sitColuna = sCols[i];
		 cTab.className = aTabEstrutura[aTabId][i].dadosCSS[sitColuna].conteudo + " " + aTabEstrutura[aTabId][i].dadosCSS[sitColuna].out + " cssHand";
		 
		 if (i == 0) {
			// Exibindo os botoes 
		    if ( eleBotoes.length != 0 ) {
			   for ( var b=0; b < eleBotoes.length; b++ ) {
			      var eleCUS = eleBotoes[b]; 
			      if (jsBrowser == "Chrome" || jsBrowser == "Firefox") {
			         cTab.innerHTML += eleCUS; 
			      } else {
				     cTab.appendChild(eleCUS);
			      }
			   }
		    } else {
		       // Nao tem botao exibe o texto da coluna se tiver :)
		       cTab.innerHTML = vCols[i];
		    }
		 } else {     
		   cTab.innerHTML = vCols[i];
		 }
      }
	                      	        
    } 
	
	function tabRowInsertBlank(aTabId, aRowId) {
		
		  // Em desenvolvimento bagunca a cruz no efeito com as colunas
		
		  // Gerenciando uma tabela
		  //alert("Id Tabela: " + aTabId);
		
	      var aTab = document.getElementById(aTabId);
	      
	      var lTab = aTab.rows.length;
		  	  
		  var rTab = aTab.insertRow(lTab); //a partir da ultima

		  // Atribuido dados para busca em pesquisas
		  rTab.id = aRowId;
		  rTab.name = aRowId;
		     	  
	      rTab.onclick=function(){setTabCurrentRow(aTabId, this);};

	      setTabCurrentRow(aTabId, rTab);
	      
		  aTabRowCount[aTabId]++;
		  
	      // Busca celulas da linha
	      var nCols = aTabTotCells[aTabId]; 
		  
	      // Definindo e inicializando ARRAY
	      var vCols = [];
	      var iCols = [];
	      var sCols = [];
	      
		  // Apenas colocando algo na celula 
    	  aTabDados[aTabId][aTabCurrentRow[aTabId]] = null;
	    	 
	      // Colanco Brancos nas celulas da linha
    	  for (var i = 0; i <= nCols; i++) {  
	    	var box = i * 10;
			vCols[i] = "&nbsp;";
			iCols[i] = "id_blk_" + aRowId + "_" + i;
			sCols[i] = "LIVRE";
	      } 
	      	      
	      for (var i = 0; i <= nCols; i++) { 
	         var cTab = rTab.insertCell(i);
	        
 	         // Nao tem botao exibe o texto da coluna se tiver :)
			 cTab.innerHTML = vCols[i];
	      }
	    } 