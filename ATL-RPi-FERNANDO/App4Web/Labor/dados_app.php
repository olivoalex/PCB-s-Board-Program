<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=ISO-8859-1");
  
  $_ret = array();
  $_dados = array();
  
  $_dados["id"] = "001";  $_dados["nome"] = "Joao";
  $_ret[] = $_dados;
  $_dados["id"] = "002";  $_dados["nome"] = "Maria";
  $_ret[] = $_dados;
  $_dados["id"] = "003";  $_dados["nome"] = "Raquel";
  $_ret[] = $_dados;
  $_dados["id"] = "004";  $_dados["nome"] = "Roberta";
  $_ret[] = $_dados;
  $_dados["id"] = "005";  $_dados["nome"] = "Carmen";
  $_ret[] = $_dados;
  $_dados["id"] = "006";  $_dados["nome"] = "Heloisa";
  $_ret[] = $_dados;
  
  $_customer["customers"] = $_ret;
  
  echo json_encode($_customer);
?>