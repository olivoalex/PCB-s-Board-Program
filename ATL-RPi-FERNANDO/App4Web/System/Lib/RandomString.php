<?php
   // Pode ser usada para gerar CAPTCHA tambem :)
class RandomString {
   	 private $_caracteres;
   	 private $_random_string;
   	 private $_length;
   	 
   	 public function __construct($_length) {
   	 	
   	 	$this->_length = $_length;
		$this->_caracteres = "1234567890abcdefghijklmnpqrstuvwxyz";
		$this->setRandomString($_length);
		return $this;
	 }

     function setRandomString($_length=null) {
     	if ( $_length == null || $_length == 0 ) {
     		$_length = $this->_length;
     	}
     	
     	$_str = "";
     	
   	    for ($i=0; $i<$_length; $i++) {
          $_str .= $this->_caracteres{rand(0,strlen($this->_caracteres) - 1)};
	    }
	    // Atribuindo ao objeto
	    $this->_random_string = $_str;
     }

     public function setLength($_valor) {
     	$this->_length = $_valor;
     }
     
     public function getRandomString() {
     	return $this->_random_string;
     }
     
     public function getLength() {
	   return $this->_length;
     }

}
?>