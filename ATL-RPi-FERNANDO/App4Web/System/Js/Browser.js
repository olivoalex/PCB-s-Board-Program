function Browser() {

     //alert("JS-Browser: " + navigator.appName + " Ver: " + navigator.appVersion+" Agent: "+ navigator.userAgent) ;
     //var str = navigator.appVersion ;
     var str = navigator.userAgent ;

     var b = "Other";

     var n = str.match(/MSIE/g); 
     if (n instanceof Array) {
         b = "IE"; //n[0];
     }
     if ( b == "Other") {
          var n = str.match(/Opera/g); 
          if (n instanceof Array) { b = n[0]; }
     } 
     if ( b == "Other") {
          var n = str.match(/Firefox/g); 
          if (n instanceof Array) { b = n[0]; }
     } 
     if ( b == "Other") {
          var n = str.match(/Chrome/g); 
          if (n instanceof Array) { b = n[0]; }
     } 
     if ( b == "Other") {
          var n = str.match(/Safari/g); 
          if (n instanceof Array) { b = n[0]; }
     }

     return  b; 
}
  