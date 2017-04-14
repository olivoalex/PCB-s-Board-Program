	//
	// Usa as variaveis:
	//   _portal_link
	//   _portal_ambiente
	//   _portal_dominio
    //	
	function jsMontaUrl(_controller, _task, _action, _params) {

	  var _ret = _portal_link + _portal_ambiente + _portal_dominio + _controller + "/" + _task + "/" + _action;

	  // Se tiver parametro, empilha :)
	  if ( _params ) {
		  _ret += _params;
	  }
	  
	  //alert("appMontaUrl: " + _ret);

	  return _ret;
	}
	
	function jsMontaHash(_hash, _params) {

	  var _ret = _portal_link + _portal_ambiente + _portal_dominio + _hash;

	  // Se tiver parametro, empilha :)
	  if ( _params ) {
		  _ret += _params;
	  }
	  
	  //alert("appMontaHash: " + _ret);

	  return _ret;
	}	

	function appExecutaHash(_hash) {
	   //alert("appExecutaHash : " + _hash);
	   window.location.hash = _hash;
	}

	function appExecutaUrl(_url) {
	   //alert("appExdecutaUrl: " + _url);
	   window.location = _url;
	}		   