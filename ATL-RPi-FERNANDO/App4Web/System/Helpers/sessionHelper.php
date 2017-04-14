<?php
class sessionHelper{
   	  public function createSession( $name, $value) {
   	  	$_SESSION[$name] = $value;
   	  	return $this;
   	  }
	  
	  public function changeSession( $name, $value) {
   	  	$_SESSION[$name] = $value;
   	  	return $this;
   	  }
   	  
   	  public function selectSession( $name ){
   	  	if ( !isset($_SESSION[$name]) ) {
   	  	   return false;
   	  	} else {
   	  		return $_SESSION[$name];
   	  	}
   	  }
   	  
   	  public function deleteSession( $name ) {
   	  	  unset($_SESSION[$name]);
   	  	  return $this;
   	  }
   	  
   	  public function checkSession($name) {
        if ( isset ($_SESSION[$name]) ) {
        	//echo "Ok {$name}";
           return true;
        } else {
        	//echo "NOk {$name}";
           return false;
        }
   	  }
}
?>