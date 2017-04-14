/**
* 
*/

   function jsRetDateTime() {
	   var dt = new Date();
	   var dia = dt.getDate();
	   var mes = dt.getMonth();
	   var ano = dt.getFullYear();
	   var hor = dt.getHours();
	   var min = dt.getMinutes();
	   var seg = dt.getSeconds();

	   var t = "";
	   
	   mes++;
	   t = ano + "-";
	   if ( mes < 10 ) {
		  t += "0" + mes;
	   } else {
		  t += mes;
	   }
	   if ( dia < 10 ) {
		   t += "-0" + dia;
	   } else {
		   t += "-" + dia;
	   }

	   hor++;
	   min++;
	   seg++;
	   
	   if ( hor < 10 ) {
		   t += " 0" + hor;
	   } else {
		   t += " " + hor;
	   }
	   if ( min < 10 ) {
		   t += ":0" + min;
	   } else {
		   t += ":" + min;
	   }        	   
	   if ( seg < 10 ) {
		   t += ":0" + seg;
	   } else {
		   t += ":" + seg;
	   }
	   return t;
   }
			   
   function jsRetToday() {
	   var dt = new Date();
	   var dia = dt.getDate();
	   var mes = dt.getMonth();
	   var ano = dt.getFullYear();

	   var t = "";
	   
	   mes++;
	   t = ano + "-";
	   if ( mes < 10 ) {
		  t += "0" + mes;
	   } else {
		  t += mes;
	   }
	   if ( dia < 10 ) {
		   t += "-0" + dia;
	   } else {
		   t += "-" + dia;
	   }
	   return t;        	                 
   }

   function jsRetNow() {
	   // Sera a Hora
	   var dt = new Date();
	   var hor = dt.getHours();
	   var min = dt.getMinutes();
	   var seg = dt.getSeconds();

	   var t = "";
	   hor++;
	   min++;
	   seg++;
	   
	   if ( hor < 10 ) {
		   t += " 0" + hor;
	   } else {
		   t += " " + hor;
	   }
	   if ( min < 10 ) {
		   t += ":0" + min;
	   } else {
		   t += ":" + min;
	   }        	   
	   if ( seg < 10 ) {
		   t += ":0" + seg;
	   } else {
		   t += ":" + seg;
	   }
	   return t;
   }
   
  function jsRetDate() {
	  var agora = new Date();
	  var d = agora.getDate();
	  var m = agora.getMonth()+1;
	  var y = agora.getFullYear();
	  
	  var ret = "00/00/0000";
	  
	  if ( d < 10 ) {
		 ret = "0" + d;
	  } else {
		 ret = d;
	  }
	  
	  if ( m < 10 ) {
		ret += "/0" + m;
	  } else {
		ret += "/" + m;
	  }
	  
	  if ( y < 10 ) {
		ret += "/0" + y;
	  } else {
		ret += "/" + y;
	  }		  
	  
	  return ret;
   }	

  function jsRetTime(){
	  var agora = new Date();
	  var h = agora.getHours();
	  var m = agora.getMinutes();
	  var s = agora.getSeconds();
	  
	  var ret = "00:00:00";
	  
	  if ( h < 10 ) {
		 ret = "0" + h;
	  } else {
		 ret = h;
	  }
	  
	  if ( m < 10 ) {
		ret += ":0" + m;
	  } else {
		ret += ":" + m;
	  }
	  
	  if ( s < 10 ) {
		ret += ":0" + s;
	  } else {
		ret += ":" + s;
	  }		  
	  
	  return ret;
  }