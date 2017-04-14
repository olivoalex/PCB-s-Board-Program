<?php
  // Parametros do Portal
  $_SESSION["DOM_SKIN"]          = "Visitante";
  
  // Path Local na maquina Servidor caminho absoluto - Producao
  $_SESSION["PORTAL_PATH_HOME"]  = $_SERVER['DOCUMENT_ROOT'].'/App4Web';
  
  $_SESSION["PORTAL_HTTP"]       = 'http://11.12.13.30/App4Web';
  
  
  // DB
  $_SESSION["PORTAL_DB_USER"]    = "root";
  $_SESSION["PORTAL_DB_PASSWD"]  = "rpi";
  $_SESSION["PORTAL_DB_SERVER"]  = "11.12.13.30";
  $_SESSION["PORTAL_DB_NAME"]    = "app4web_rpi"; 
  
  // FTP
  $_SESSION["PORTAL_FTP_SERVER"] = "";
  $_SESSION["PORTAL_FTP_USER"]   = "";
  $_SESSION["PORTAL_FTP_PASSWD"] = "";
  $_SESSION["PORTAL_FTP_PORT"]   = "";
  
  //EMAIL
  $_SESSION["PORTAL_EML_SERVER"] = "11.12.13.30"; // Server
  $_SESSION["PORTAL_EML_USER"]   = "portal@clog.com.br"; // Conta de Email 
  $_SESSION["PORTAL_EML_PASSWD"] = "it#prtl2014";
  
  // Parametros parao Dominio Identificado
  // DB
  $_SESSION["DOM_DB_USER"]       = "root";
  $_SESSION["DOM_DB_PASSWD"]     = "rpi";
  $_SESSION["DOM_DB_SERVER"]     = "11.12.13.30";
  $_SESSION["DOM_DB_NAME"]       = "app4web_rpi";
  
  // FTP
  $_SESSION["DOM_FTP_SERVER"]    = "";
  $_SESSION["DOM_FTP_LOGIN"]     = "";
  $_SESSION["DOM_FTP_PASSWD"]    = "";
  $_SESSION["DOM_FTP_PORT"]      = "";
  
  //EMAIL
  $_SESSION["DOM_EMAL_SERVER"]   = "11.12.13.30"; // Server
  $_SESSION["DOM_EMAL_LOGIN"]    = "portal@clog.com.br"; // Conta de Email
  $_SESSION["DOM_EMAL_PASSWD"]   = "it#prtl2014";
  
  // Parametros parao PI
  // DB
  $_SESSION["RPI3_DB_USER"]       = "root";
  $_SESSION["RPI3_DB_PASSWD"]     = "rpi";
  //$_SESSION["RPI3_DB_SERVER"]     = "localhost";
  $_SESSION["RPI3_DB_SERVER"]     = "11.12.13.30";
  $_SESSION["RPI3_DB_NAME"]       = "agrotech_intel";  
?>
