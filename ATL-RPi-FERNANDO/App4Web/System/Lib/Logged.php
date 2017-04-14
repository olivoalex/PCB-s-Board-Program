<?php
function Logged() {
   $_logged = ( !isset($_SESSION["LOGGED"]) ? "N" : $_SESSION["LOGGED"] );
      
   //echo "<br>---- Loged esta : {$_logged}";
   
   if ( strtoupper($_logged) == "B" ) {
	   return "bloqueado";
   } else {
      if ( strtoupper($_logged) == "S" ) {
		return "on-line";
	  } else {
	    return "off-line";
	  }
   }
}
?>