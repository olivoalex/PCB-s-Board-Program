<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=ISO-8859-1");
  
  $_ret = array();
  $_dados = array();
  
  $_dados["id"] = "001";  $_dados["nome"] = "Antonio";
  $_ret[] = $_dados;
  $_dados["id"] = "002";  $_dados["nome"] = "Fernando";
  $_ret[] = $_dados;
  $_dados["id"] = "003";  $_dados["nome"] = "Viana";
  $_ret[] = $_dados;
  $_dados["id"] = "004";  $_dados["nome"] = "Joaquim";
  $_ret[] = $_dados;
  $_dados["id"] = "005";  $_dados["nome"] = "Carlos";
  $_ret[] = $_dados;
  $_dados["id"] = "006";  $_dados["nome"] = "Marcus";
  $_ret[] = $_dados;
  
  $_customer["customers"] = $_ret;
  
  echo json_encode($_customer);
?>