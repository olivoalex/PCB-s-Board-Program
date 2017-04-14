<?php
class debugHelper{

   	public function is_assoc($_aArray)
	{
		
	   if ( ! is_array( $_aArray) ) {
		   return false;
	   }
	   
       // Keys of the array
       $keys = array_keys($_aArray);
	   
	   //print_r($keys);	   
	   //echo "<br>Esse:";
	   //print_r(array_keys($keys));
	   

       // If the array keys of the keys match the keys, then the array must
       // not be associative (e.g. the keys array looked like {0:0, 1:1...}).
       return array_keys($keys) !== $keys;
    }
   
    public function debug($_aValor) {
   
  		
  		if ( $this->is_assoc( $_aValor) ) {
    	   echo "Eh um Hash/Array Associativo:<br>";
    	   foreach($_aValor as $_key => $_value) {
    		 echo "   Chave[{$_key}]: ". $_value."<br>";
    	   }
    	} else {
    	   if ( is_array($_aValor) ) {
    		  // Eh um array
    		  echo "Eh um array:<br>";
    		  for ( $i=0; $i<count($_aValor); $i++ ) {
    			 echo "   Pos[{$i}]: ". $_aValor[$i]."<br>";
    		  }
    	   } else {		
              if ( is_object($_aValor) ) {				
				$novo = (array)$_aValor;
                foreach ($novo as $k => $v) {
					$txt = "   Object[{$k}]: ". $v;
					$mais_novo = (array)$novo;
					$c=0;
					foreach ( $mais_novo as $kk => $vv) {
					   $msg = $txt."   --> [{$kk}]: ". $vv."<br>";
					   echo $msg;
					   $c++;
					}
					if ( $c==0 ) {
						echo $txt."<br>";
					}
				}				
			  }	else {   
    		     echo "Eh uma Variavel: " . $_aValor."<br>";
			  }
    	   }
    	}    	
    }
    
}
?>