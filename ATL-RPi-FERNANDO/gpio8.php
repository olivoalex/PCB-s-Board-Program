<html> <head> 
<meta http-equiv="refresh" content="300" />
<meta name="viewport" content="width=device-width" /> 
<title>ATLRPi - MAIO - 2017</title> </head> 
<body><h1>ATL - RPi - PHP - PCI - MAIO - 2017</h1>
<br>Interface de Controle e Testes - RELOAD 300 segundos
<br>versao: 7 - revisao: 15052017 <?php
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// MOSTRA DATA ATUAL DO LINUX
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$x= shell_exec ("date");
echo "<br>Data | HORA atual LINUX: " . $x;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// TEMPERATURA DA CPU LIDA DIRETAMENTE NO RPi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$tempCPU = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
echo "<br>Temperatura ATUAL da UCP: " . $tempCPU / 1000 . " *C";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// FORMULARIO EM HTML PARA CONTROLAR AS GPIO CONFIGURADAS COMO SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
?>
<form method="get" action="gpio8.php"> 
<br>GPIO_17 | BAT-OUT-CONTROL | - - - - - - 	|
<input type="submit" value=" BAT-OUT-CONTROL-ON " name="on17"> 
<!http://11.12.13.30/gpio.php?on=ON>
<input type="submit" value=" BAT-OUT-CONTROL-OFF " name="off17"> 
<!http://11.12.13.30/gpio.php?off=OFF>
<br>GPIO_18  |  FAN-OUT-NOPWM | - - - - - - - 	|
<input type="submit" value=" FAN-ON " name="on18">
<input type="submit" value=" FAN-OFF " name="off18">
<br>GPIO_27  |  EXTRA-OUT-ONOFF | - - - - - 	|
<input type="submit" value=" EXTRA-ON " name="on27">
<input type="submit" value=" EXTRA-OFF " name="off27">
<br>GPIO_23  |  ESTACOES-OUT-ONOFF | - - 	|
<input type="submit" value=" ESTACOES-ON " name="on23">
<input type="submit" value=" ESTACOES-OFF " name="off23">
<br>GPIO_15  |  LED-OUT-INTERNET | - - - - 	|
<input type="submit" value=" INTERNET-LED-ON " name="on15">
<input type="submit" value=" INTERNET-LED-OFF " name="off15">
<br>GPIO_14  |  PUMP-OUT-ONOFF | - - - - - - 	|
<input type="submit" value=" PUMP-ON " name="on14">
<input type="submit" value=" PUMP-OFF " name="off14">
<br>REBOOT - CUIDADO  |  RPi | - - - - - - - - 	|
<input type="submit" value=" REBOOT-REALY-NOW " name="REBOOT">
</form> 
<?php 
$setSPACER = "<br>| - - - - - - - - - - - - - - - - - - - |";
//echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* 	ENTRADAS 	ENTRADAS 	ENTRADAS 	ENTRADAS 	ENTRADAS
	GPIO_02 >--> REDE-IN-FALHA 		 >--> E
	GPIO_03 >--> EXTRA-IN			 >--> E
	GPIO_04 >--> LEVEL-IN			 >--> E
	SAIDAS 	SAIDAS 	SAIDAS 	SAIDAS 	SAIDAS  SAIDAS  SAIDAS 
	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S 
	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
	GPIO_18 >--> FAN-OUT-PWM		 >--> S
	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// DEFINICAO DE VARIAVEIS GERAIS - ENTRADA - SAIDA - DIRECAO - ESTADO
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// - - - - - - - ->	ENTRADAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio02SetIn = 		shell_exec("/usr/local/bin/gpio -g mode 2 in");
$gpio02Read = 		shell_exec("/usr/local/bin/gpio -g read 2");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio03SetIn = 		shell_exec("/usr/local/bin/gpio -g mode 3 in");
$gpio03Read = 		shell_exec("/usr/local/bin/gpio -g read 3");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio04SetIn = 		shell_exec("/usr/local/bin/gpio -g mode 4 in");
$gpio04Read = 		shell_exec("/usr/local/bin/gpio -g read 4");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// - - - - - - - ->	SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio14SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 14 out");
$gpio14on  = 		shell_exec("/usr/local/bin/gpio -g write 14 1");
$gpio14off  = 		shell_exec("/usr/local/bin/gpio -g write 14 0");
$gpio14Read = 		shell_exec("/usr/local/bin/gpio -g read 14"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio15SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 15 out");
$gpio15on  = 		shell_exec("/usr/local/bin/gpio -g write 15 1");
$gpio15off  = 		shell_exec("/usr/local/bin/gpio -g write 15 0");
$gpio15Read = 		shell_exec("/usr/local/bin/gpio -g read 15"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio17SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 17 out");
$gpio17on  = 		shell_exec("/usr/local/bin/gpio -g write 17 1");
$gpio17off  = 		shell_exec("/usr/local/bin/gpio -g write 17 0");
$gpio17Read = 		shell_exec("/usr/local/bin/gpio -g read 17"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio18SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 18 out");
$gpio18on  = 		shell_exec("/usr/local/bin/gpio -g write 18 1");
$gpio18off  = 		shell_exec("/usr/local/bin/gpio -g write 18 0");
$gpio18Read = 		shell_exec("/usr/local/bin/gpio -g read 18"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio23SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 23 out");
$gpio23on  = 		shell_exec("/usr/local/bin/gpio -g write 23 1");
$gpio23off  = 		shell_exec("/usr/local/bin/gpio -g write 23 0");
$gpio23Read = 		shell_exec("/usr/local/bin/gpio -g read 23"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio27SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 27 out");
$gpio27on  = 		shell_exec("/usr/local/bin/gpio -g write 27 1");
$gpio27off  = 		shell_exec("/usr/local/bin/gpio -g write 27 0");
$gpio27Read = 		shell_exec("/usr/local/bin/gpio -g read 27"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CONFIGURACAO DE TODOS OS GPIO COMO ENTRADAS OU SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "{$gpio02SetIn}";
echo "{$gpio03SetIn}";
echo "{$gpio04SetIn}";
echo "{$gpio14SetOut}";
echo "{$gpio15SetOut}";
echo "{$gpio17SetOut}";
echo "{$gpio18SetOut}";
echo "{$gpio23SetOut}";
echo "{$gpio27SetOut}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// LEITURA DE TODOS OS GPIO USADOS ENTRADAS E SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* $gpio02Read = shell_exec("/usr/local/bin/gpio -g read 2");
$gpio03Read = shell_exec("/usr/local/bin/gpio -g read 3");
$gpio04Read = shell_exec("/usr/local/bin/gpio -g read 4");
$gpio17Read = shell_exec("/usr/local/bin/gpio -g read 17");
$gpio27Read = shell_exec("/usr/local/bin/gpio -g read 27");
$gpio23Read = shell_exec("/usr/local/bin/gpio -g read 23");
$gpio18Read = shell_exec("/usr/local/bin/gpio -g read 18");
$gpio15Read = shell_exec("/usr/local/bin/gpio -g read 15");
$gpio14Read = shell_exec("/usr/local/bin/gpio -g read 14"); */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// MOSTRA EM HTML TODOS OS ESTADOS USADOS DE ENTRADAS E SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "<br>ESTADO GPIO_02 [ENTRADA]: == [ {$gpio02Read}"; 
echo "] == REDE-IN-FALHA\BARRADO| [0] = 220V = 0K!";
echo "<br>ESTADO GPIO_03 [ENTRADA]: == [ {$gpio03Read}"; 
echo "] == EXTRA-IN -- [1] = ON";
echo "<br>ESTADO GPIO_04 [ENTRADA]: == [ {$gpio04Read}"; 
echo "] == LEVEL-IN -- [1] = ON";
echo "<br>ESTADO GPIO_17 [SAIDA]: ===== [ {$gpio17Read}"; 
echo "] == BAT-OUT-CONTROL| [1] = BATERIA CONECTADA!";
echo "<br>ESTADO GPIO_27 [SAIDA]: ===== [ {$gpio27Read}"; 
echo "] == EXTRA-OUT-ONOFF";
echo "<br>ESTADO GPIO_23 [SAIDA]: ===== [ {$gpio23Read}"; 
echo "] == ESTACOES-OUT-ONOFF";
echo "<br>ESTADO GPIO_18 [SAIDA]: ===== [ {$gpio18Read}"; 
echo "] == FAN-OUT-PWM";
echo "<br>ESTADO GPIO_15 [SAIDA]: ===== [ {$gpio15Read}"; 
echo "] == LED-OUT-INTERNET";
echo "<br>ESTADO GPIO_14 [SAIDA]: ===== [ {$gpio14Read}"; 
echo "] == PUMP-OUT-ONOFF";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// LOGICA DE ACIONAMENTO PARA CADA UMA DAS --> SAIDAS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
if(isset($_GET['on14'])){$gpio_on = $gpio14on;}
else if(isset($_GET['off14'])){$gpio_off = $gpio14off;} 
if(isset($_GET['on15'])){$gpio_on = $gpio15on;}
else if(isset($_GET['off15'])){$gpio_off = $gpio15off;} 
if(isset($_GET['on17'])){$gpio_on = $gpio17on;}
else if(isset($_GET['off17'])){$gpio_off = $gpio17off;} 
if(isset($_GET['on18'])){$gpio_on = $gpio18on;}
else if(isset($_GET['off18'])){$gpio_off = $gpio18off;} 
if(isset($_GET['on23'])){$gpio_on = $gpio23on;}
else if(isset($_GET['off23'])){$gpio_off = $gpio23off;} 
if(isset($_GET['on27'])){$gpio_on = $gpio27on;}
else if(isset($_GET['off27'])){$gpio_off = $gpio27off;} 
// LOGICA PARA ENVIAR >--> SHUTDOWN ou REBOOT
if(isset($_GET['REBOOT'])){ 
$gpio_off = shell_exec("sudo -u root reboot");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* echo "<br>LOGICA INVERTIDA AQUI"; 
echo "<br>GPIO_02 -- REDE-IN-FALHA -- [0] = COM 220V";
echo "<br>GPIO_02 -- REDE-IN-FALHA -- [1] = SEM REDE";
echo "<br>ESTADO GPIO_03 [ENTRADA]: ===== {$gpio03Read}"; 
echo "<br>GPIO_03 -- EXTRA-IN -- [1] = ON";
echo "<br>GPIO_03 -- EXTRA-IN -- [0] = OFF";
echo "<br>ESTADO GPIO_04 [ENTRADA]: ===== {$gpio04Read}"; 
echo "<br>GPIO_04 -- LEVEL-IN -- [1] = ON";
echo "<br>GPIO_04 -- LEVEL-IN -- [0] = OFF";
echo "<br>ESTADO GPIO_17 [SAIDA]: ===== {$gpio17Read}"; 
echo "<br>GPIO_17 -- BAT-OUT-CONTROL";
echo "<br>ESTADO GPIO_27 [SAIDA]: ===== {$gpio27Read}"; 
echo "<br>GPIO_27 -- EXTRA-OUT-ONOFF";
echo "<br>ESTADO GPIO_23 [SAIDA]: ===== {$gpio23Read}"; 
echo "<br>GPIO_23 -- ESTACOES-OUT-ONOFF";
echo "<br>ESTADO GPIO_18 [SAIDA]: ===== {$gpio18Read}"; 
echo "<br>GPIO_18 -- FAN-OUT-PWM";
echo "<br>ESTADO GPIO_15 [SAIDA]: ===== {$gpio15Read}"; 
echo "<br>GPIO_15 -- LED-OUT-INTERNET";
echo "<br>ESTADO GPIO_14 [SAIDA]: ===== {$gpio14Read}"; 
echo "<br>GPIO_14 -- PUMP-OUT-ONOFF";
*/
?> </body> </html>