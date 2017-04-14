<?php  

   // Date in the past
   header('Content-Type: text/html; charset=UTF-8',true);
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache"); 


   //echo "<script>alert('---------> Url Completa: ". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."');</script>";	

   //echo "<br><br>O que estamso recebenfo...<br><br>";
   
   //print_r($_GET);   
   
   //echo "<br><br>Ok";

   //print_r($_SERVER);   

   //echo "<br><br>Ok";

   include("inicializar.php");

   //echo "<br>HOME {$HOME}";

  
   // Roda a aplicacao
   $start->execute();  

?>
