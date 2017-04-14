/**
 * comboBoxAJAX.js
 */
function executaAJAX(aAcao, aId) {

	//alert('executAJAX: '+ aAcao + ' c/ ID: '+aId);

	// Preparando Ajax
	var _dados = {
		"acao" : aAcao,
		"id" : aId
	};

	var aUrl = js_link + "profileAJAX/ajax/" + aAcao + "/acao/" + aAcao + "/id/"
			+ aId;

	//Acionando AJAX
	$.ajax({
		url : aUrl,
		type : "post",
		data : _dados,
		success : function(aResponse) {
			var aObjResponse = jQuery.parseJSON(aResponse);
			retornoAJAX(aAcao, aObjResponse);
		},
		error : function(x, textStatus, errorThrow) {
			console.log(textStatus, errorThrow);
		}
	});
}

function retornoAJAX(aAcao, aObjResponse) {

	var _msg = aObjResponse.message;

	var _rnd = aObjResponse.random;

	//alert('Ajax Message Retorno: ' + _msg + ' Random: '+ _rnd);

	document.getElementById('r').innerHTML = _rnd;

}

// Combobox controles: Continente ate CEP
//$(document).ready(function(){	
function Reset(aOnde){
	//$('#cmbContinente').empty().append('<option>Carregar Continentes</option>>');
	if ( aOnde == "continete" ) { 
	    $('#cmbPais').empty().append('<option>Carregar Paises</option>>'); 
		$('#cmbEstado').empty().append('<option>Carregar Estados</option>>'); 
		$('#cmbCidade').empty().append('<option>Carregar Cidades</option>');
		$('#cmbBairro').empty().append('<option>Carregar Bairros</option>'); 
		$('#cmbCep').empty().append('<option>Carregar CEPs</option>');	
	}
	if ( aOnde == "pais" ) { 
	    $('#cmbEstado').empty().append('<option>Carregar Estados</option>>'); 
		$('#cmbCidade').empty().append('<option>Carregar Cidades</option>');
		$('#cmbBairro').empty().append('<option>Carregar Bairros</option>'); 
		$('#cmbCep').empty().append('<option>Carregar CEPs</option>');	
	}
	if ( aOnde == "estado" ) { 
	    $('#cmbCidade').empty().append('<option>Carregar Cidades</option>');
		$('#cmbBairro').empty().append('<option>Carregar Bairros</option>'); 
	    $('#cmbCep').empty().append('<option>Carregar CEPs</option>');				
	}
	if ( aOnde == "cidade" ) { 
	    $('#cmbBairro').empty().append('<option>Carregar Bairros</option>'); 
		$('#cmbCep').empty().append('<option>Carregar CEPs</option>');	
	}
	if ( aOnde == "bairro" ) {  
	    $('#cmbCep').empty().append('<option>Carregar CEPs</option>');	
	}
	
	}
	
function resetID(aId) {
		_id = document.getElementById(aId);
		$(_id).empty();
		_id.className = "dropdown-menu control-label";
	}
	
//});