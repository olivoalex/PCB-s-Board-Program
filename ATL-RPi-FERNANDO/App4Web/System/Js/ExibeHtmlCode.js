/**
 * 
 */
 function ExibirCodigoFonte(aContainer, aCodigoHTML) {
   if (document.getElementById(aContainer).innerHTML == "") {
        
          codigo_fonte = aCodigoHTML;
          
          carac = new Array();
        
          for (i = 0; i <= codigo_fonte.length - 1; i++) {
            carac[i] = codigo_fonte.charAt(i);
            carac[i] = carac[i].replace("<", "&lt;");
            carac[i] = carac[i].replace(">", "&gt;");
            //carac[i] = carac[i].replace("n", "<br>");
            carac[i] = carac[i].replace(" ", "&nbsp;");
          }
          for (i = 0; i <= codigo_fonte.length - 1; i++) {
            document.getElementById(aContainer).innerHTML += carac[i];
          }
   }
 }
