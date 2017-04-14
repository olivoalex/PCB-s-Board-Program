function menuOpcao(_obj_menu, _referencia) {
	     
   var obj_1 = document.createElement("li");
   
   var obj_2 = document.createElement("a");
   obj_2.href= _referencia["HASH"];
   var obj_3 = document.createElement("i");
   obj_3.className = _referencia["CSS"] + " menu-item-icon";
   var obj_4 = document.createElement("span");
   obj_4.className = "inner-text";
   obj_4.innerHTML = _referencia["DESCR"];
   obj_2.appendChild(obj_3);
   obj_2.appendChild(obj_4);		 
   obj_1.appendChild(obj_2);   
		 
   // Colocando a opcao no Noh do menu
   _obj_menu.appendChild(obj_1);
   
   //alert("Opcao: " + _obj_menu.innerHTML);
	  
   return _obj_menu;

}   
  
function menu(_obj_container, _referencia) {
							  
   var obj_1 = document.createElement("li");
   var obj_2 = document.createElement("a");
   obj_2.href= _referencia["HASH"];
   obj_2.innerHTML = _referencia["DESCR"];

   obj_1.appendChild(obj_2);

   var obj_5 = document.createElement("ul");

   obj_1.appendChild(obj_5);

   _obj_container.appendChild(obj_1);

   //alert("Menu: " + obj_1.innerHTML);

   // retorna esse novo Noh como referencia
   return obj_5;	  
   
}   	  

function raiz(_obj_container, _referencia) {
							  
   var obj_1 = document.createElement("li");
   var obj_2 = document.createElement("a");
   obj_2.href="#";
   var obj_3 = document.createElement("i");
   obj_3.className = _referencia["CSS"] + " menu-item-icon";
   var obj_4 = document.createElement("span");
   obj_4.className = "inner-text";
   obj_4.innerHTML = _referencia["DESCR"];
   obj_2.appendChild(obj_3);
   obj_2.appendChild(obj_4);		 
   obj_1.appendChild(obj_2);

   var obj_5 = document.createElement("ul");
	
   obj_1.appendChild(obj_5);
	
   _obj_container.appendChild(obj_1);

   // retorna esse novo Noh como referencia
   return obj_5;
		 
}   	  

function menuMonta(_container, _jsonMenu) {
   
   var _obj_container = document.getElementById(_container);
   
   // Comecando pala RAIZ
   _dados = _jsonMenu["DADOS"]["RAIZ"];
   	  
    for ( var i=0; i < _dados.length; i++ ) {
	   
	  // Identificando o MENU
      mnu = _dados[i]["OPC"];
	  
	  // Tem nivel abaixo esse ponto do Menu  
      _dados_nv01 = _dados[i]["NV01"];
	  
	  if ( _dados_nv01.length > 0 ) {
		  
         _obj_menu = raiz(_obj_container, mnu);	  

		 for ( var n1=0; n1 < _dados_nv01.length; n1++ ) {		 
		    
		   var opc_mnu_1 = _dados_nv01[n1]["OPC"];
           var prg_mnu_1 = _dados_nv01[n1]["PRG"];
		
           // Tem nivel abaixo esse ponto do Menu  
           _dados_nv02 = _dados_nv01[n1]["NV02"];
						   
           if ( _dados_nv02.length > 0 ) {			   
              
			  // Cria com sub-itens
			  _obj_menu_nv1 = menu(_obj_menu, opc_mnu_1);
		   
			  for ( var n2=0; n2 < _dados_nv02.length; n2++ ) {
			    
				 var opc = _dados_nv02[n2]["OPC"];
				 var prg = _dados_nv02[n2]["PRG"];
		   
		         opc["HASH"] = menuMontaHash(_dados_nv02[n2])
			   
		         //alert("hash: "+ opc["HASH"]);
			
	             _obj_menu_nv2 = menuOpcao(_obj_menu_nv1, opc);		   
			  }  
		   } else {
			 // Cria o Menu como Uma opcao - sem sub-itens
			 opc_mnu_1["HASH"] = menuMontaHash(_dados_nv01[n1]);
		     _obj_menu_nv1 = menuOpcao(_obj_menu, opc_mnu_1);	   		   
		   }		
	     }
	  } else {
		  
		// Cria o Menu como Uma opcao
		// Pega o HASH do Programa
		mnu["HASH"] = menuMontaHash(_dados[i]);
		_obj_menu = menuOpcao(_obj_container, mnu);	    	  
      }
   }
		 
}

function menuMontaHash(aMenuOpcaoPrograma) {
  var _hash = "";
   
  _hash = "#" + aMenuOpcaoPrograma["PRG"]["COD"].toLowerCase()
        + "/mnu/" + aMenuOpcaoPrograma["MNU"]["ID"]
		+ "/opc/" + aMenuOpcaoPrograma["OPC"]["ID"]
		+ "/prg/" + aMenuOpcaoPrograma["PRG"]["ID"];
  
  return _hash;
}

function menuDesmonta(_menu) {
  //Elimina TODOS os elementos filhos do Objeto indicado
  var list = document.getElementById(_menu);  

  // Enquanto tiver um Node, Apaga
  var tem = list.hasChildNodes();
  while (list.hasChildNodes()) {   
    list.removeChild(list.lastChild);
  }  
}   