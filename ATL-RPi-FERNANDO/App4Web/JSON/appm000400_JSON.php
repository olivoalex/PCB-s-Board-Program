<?php
    // Previsto receber:
    //   
	//
	// Previsto Retornar
	//    
	//     Dados do Raspberry
	//
	// 
	

    // Tratando acentuacao :(
    header('Content-Type: text/html; charset=UTF-8',true);
    
    include("Include/conectarRPI3Include.php");
	include "Include/debianRaspBerryPi3.php";
       
    $_errors = array();
	$_return = array();
	$_acao   = "NONE";
	$_id     = 0;
	$_status = true;
	$_mensagem = "";
	$_error  = "";

	$_CURRENT_DATE     = date('Y-m-d');
	
	$_CURRENT_TIME     = date('H:i:s');
	
	$_CURRENT_DATETIME = date('Y-m-d H:i:s');
	
	// Procurando os controles para poder serem executados
	// Tarefa
	if (isset($_POST['acao'])) {
		$_acao = $_POST['acao'];
		if (isset($_POST['id'])) {
			$_id = $_POST['id'];
		}
	} else {
		if (isset($_GET['acao'])) {
			$_acao = $_GET['acao'];
			if (isset($_GET['id'])) {
				$_id = $_GET['id'];
			}
		}
	}

	//Recebe os parâmetros enviados via GET
	$_acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
	$_complemento = (isset($_GET['complemento'])) ? $_GET['complemento'] : 'NONE';
	$_parametro = (isset($_GET['parametro'])) ? $_GET['parametro'] : '';
	
	// Preparando comunicacao com DB
	$_db = new Model();
	
	$_empresa        = isset($_GET['empresa']) ? $_GET['empresa'] : '';
	$_filial         = isset($_GET['filial']) ? $_GET['filial'] : '';
	$_usuario        = isset($_GET['usuario']) ? $_GET['usuario'] : '';

	//echo "<br>Acao: {$_acao}";
	//echo "<br>Complemento  : {$_complemento}";
	//echo "<br>Parametro    : {$_parametro}";
	//echo "<br>Empresa      : {$_empresa}";
	//echo "<br>Filial       : {$_filial}";
	//echo "<br>Usuario      : {$_usuario}";
	//echo "<br><br>Data     : {$_CURRENT_DATE}";
	//echo "<br>Hora         : {$_CURRENT_TIME}";
	//echo "<br>TimeStamp    : {$_CURRENT_DATETIME}";
	
	// Associativo para receber dados 	
	$_dados = array();
	// Lendo dados do RaspBerryPI3
	// Ethernet
	$_ip = debianIp("rede");
	$_mac = debianMAC($GLOBALS['interface_tipo']);
	$_dados["eth_ip"]  = $_ip;
	$_dados["eth_mac"] = $_mac;
	$_dados["eth_interface"] = $GLOBALS['interface'];
	$_dados["eth_status"] = ( $_ip != "NONE" ? "Ativa" : "Desativa");
	// Wireless
	$_ip = debianIp("wifi");
	$_mac = debianMAC($GLOBALS['interface_tipo']);
    $_dados["wlan_ip"]  = $_ip;
	$_dados["wlan_mac"] = $_mac;
	$_dados["wlan_interface"] = $GLOBALS['interface'];
	$_dados["wlan_status"] = ( $_ip != "NONE" ? "Ativa" : "Desativa");
	// Sem Rede / Localhost
	$_ip = debianIp("localhost");
	$_mac = debianMAC($GLOBALS['interface_tipo']);
    $_dados["local_ip"]  = $_ip;
	$_dados["local_mac"] = $_mac;
	$_dados["local_interface"] = $GLOBALS['interface'];
	$_dados["local_status"] = ( $_ip != "NONE" ? "Ativa" : "Desativa");	
	// Situacao Atual/Interface Ativa
	$_ip = debianInterfaceAtiva();
	$_mac = debianMAC($GLOBALS['interface_tipo']);	
	$_dados["interface_ativa_ip"] = $_ip;
	$_dados["interface_ativa_mac"] = $_mac;
	$_dados["interface_ativa"] = $GLOBALS['interface'];
	$_dados["interface_status"] = ( $_ip != "NONE" ? "Ativa" : "Desativa");			
	// Data e Hora Corrente da Maquina RaspBerryPI3
	$_now = debianDateTime();
	$_hoje = $_now[0];
	$_as = $_now[1];
	$_dados["rpi_data"] = $_hoje;
	$_dados["rpi_hora"] = $_as;
	// STATUS para o BOOT sempre sera 1 com REDE ou 0 SEM REDE
	$_status = debianStatusRede();
	$_dados["rpi_rede"] = ( $_status == 0 ? "Desligada" : "Ligada" );
	// Internet
	$_internet = debianInternet();
	$_dados["rpi_internet"] = ( $_internet  == 1 ? "Conectado" : "Sem Sinal" );
	// Energia esta lendo o pino 2
	$_energia =  debianReadEnergia();
	$_dados["rpi_energia"] = ( $_energia  == 0 ? "Desligada" : "Ligada" );
	// dados de temperatura CPU e Ventilador como esta
    // Limite Ventilador
	$_limite = debianVentiladorLimiteTemperatura();
	$_dados["rpi_limite"] = $_limite;
    // Maximo para Shutdown
	$_maximo = debianCpuMaxTemperatura();
	$_dados["rpi_maximo"] = $_maximo;
    // Tenperatur CPU
	$_cpu = debianCpuTemperatura();
	$_dados["rpi_cpu"] = $_cpu;
    // Status Ventilador    
	$_ventilador = debianVentiladorStatus();
	$_dados["rpi_ventilador"] = ( $_ventilador == 0 ? "Desligado" : "Ligado" );
	
	// Retornar Status
	$_return['acao']     = $_acao;
	$_return['status']   = $_status;
	$_return['mensagem'] = $_mensagem;
	$_return['error']    = $_error;
	$_return['dados']    = $_dados;
	
	// Retornando JSON
	echo json_encode($_return);	
?>
