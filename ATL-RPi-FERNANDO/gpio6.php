<html> <head> 
<meta http-equiv="refresh" content="90" />
<meta name="viewport" content="width=device-width" /> 
<title>ATLRPi - MAIO17</title> </head> 
<body> 
<h1>ATL - RPi - PHP - PCI - MAIO17</h1>
<br>Interface de Controle e Testes - RELOAD 90 segundos
<br>versao: 6 - revisao: 13052017
<form method="get" action="gpio6.php"> 
<br>GPIO_17 >--> BAT-OUT-CONTROL<br>
<input type="submit" value=" BAT-OUT-CONTROL-ON " name="on17"> 
<!http://11.12.13.30/gpio.php?on=ON>
<input type="submit" value=" BAT-OUT-CONTROL-OFF " name="off17"> 
<!http://11.12.13.30/gpio.php?off=OFF>
<br>GPIO_18 >--> FAN-OUT-NOPWM <br>
<input type="submit" value=" FAN-ON " name="on18">
<input type="submit" value=" FAN-OFF " name="off18">
<br>GPIO_27 >--> EXTRA-OUT-ONOFF<br>
<input type="submit" value=" EXTRA-ON " name="on27">
<input type="submit" value=" EXTRA-OFF " name="off27">
<br>GPIO_23 >--> ESTACOES-OUT-ONOFF<br>
<input type="submit" value=" ESTACOES-ON " name="on23">
<input type="submit" value=" ESTACOES-OFF " name="off23">
<br>GPIO_15 >--> LED-OUT-INTERNET<br>
<input type="submit" value=" INTERNET-LED-ON " name="on15">
<input type="submit" value=" INTERNET-LED-OFF " name="off15">
<br>GPIO_14 >--> PUMP-OUT-ONOFF<br>
<input type="submit" value=" PUMP-ON " name="on14">
<input type="submit" value=" PUMP-OFF " name="off14">
<br>REBOOT - CUIDADO >--> RPi<br>
<input type="submit" value=" REBOOT-REALY-NOW " name="REBOOT">
</form> 
<?php 
$setSPACER = "<br>| - - - - - - - - - - - - - - - - - - - |";
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* 	ENTRADAS 	ENTRADAS 	ENTRADAS 	ENTRADAS 	ENTRADAS
	GPIO_02 >--> REDE-IN-FALHA 		 >--> E
	GPIO_03 >--> EXTRA-IN			 >--> E
	GPIO_04 >--> LEVEL-IN			 >--> E
	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S
	SAIDAS 	SAIDAS 	SAIDAS 	SAIDAS 	SAIDAS  SAIDAS  SAIDAS 
	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
	GPIO_18 >--> FAN-OUT-PWM		 >--> S
	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S */
// CONFIGURACAO GPIO COMO SAIDA
$setmodeOut17 = shell_exec("/usr/local/bin/gpio -g mode 17 out"); 
$setmodeOut18 = shell_exec("/usr/local/bin/gpio -g mode 18 out"); 
echo "{$setmodeOut17}";
echo "{$setmodeOut18}";
// LOGICA DE ACIONAMENTO PARA CADA UMA DAS SAIDAS
//	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
if(isset($_GET['on17'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 1");} 
else if(isset($_GET['off17'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 0");} 
// 	GPIO_18 >--> FAN-OUT-PWM		 >--> S
//if(isset($_GET['on18'])){ 
//$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1");} 
//else if(isset($_GET['off18'])){ 
//$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0");} 




// LOGICA PARA ENVIAR >--> SHUTDOWN ou REBOOT
else if(isset($_GET['REBOOT'])){ 
$gpio_off = shell_exec("sudo -u root reboot");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// LEITURA DOS ESTADOS DAS GPIO USADAS ANTERIORMENTE
$setmode17 = shell_exec("/usr/local/bin/gpio -g read 17"); 
//$setmode18 = shell_exec("/usr/local/bin/gpio -g read 18"); 
//	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
//	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S
//	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
//	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
//	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S
echo "<br>ESTADO out GPIO_17: {$setmode17}"; 
//echo "<br>ESTADO out GPIO_18: {$setmode18}"; 
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CONFIGURACAO GPIO COMO ENTRADA
$setmodeIn02 = shell_exec("/usr/local/bin/gpio -g mode 2 in");
$setmodeIn03 = shell_exec("/usr/local/bin/gpio -g mode 3 in");
$setmodeIn04 = shell_exec("/usr/local/bin/gpio -g mode 4 in");
echo "{$setmodeIn02}";
echo "{$setmodeIn03}";
echo "{$setmodeIn04}";
// LEITURA GPIO - VERIFICAR FALTA ENERGIA REDE
$setmode02 = shell_exec("/usr/local/bin/gpio -g read 2");
$setmode03 = shell_exec("/usr/local/bin/gpio -g read 3");
$setmode04 = shell_exec("/usr/local/bin/gpio -g read 4");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "<br>ESTADO in GPIO_02 [ENTRADA]: ===== {$setmode02}"; 
echo "<br>LOGICA INVERTIDA AQUI"; 
echo "<br>GPIO_02 -- REDE-IN-FALHA -- [0] = COM 220V";
echo "<br>GPIO_02 -- REDE-IN-FALHA -- [1] = SEM REDE";
echo "{$setSPACER}";
echo "<br>ESTADO in GPIO_03 [ENTRADA]: ===== {$setmode03}"; 
echo "<br>GPIO_03 -- EXTRA-IN -- [1] = ON";
echo "<br>GPIO_03 -- EXTRA-IN -- [0] = OFF";
echo "{$setSPACER}";
echo "<br>ESTADO in GPIO_04 [ENTRADA]: ===== {$setmode04}"; 
echo "<br>GPIO_04 -- LEVEL-IN -- [1] = ON";
echo "<br>GPIO_04 -- LEVEL-IN -- [0] = OFF";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// MOSTRA DATA ATUAL DO LINUX
echo "{$setSPACER}";
$x= shell_exec ("date");
echo "<br>Data atual: " . $x;
// A SER USADO PARA VERIFICAR FALTA DE ENERGIA ELETRICA ELETRICA DESLIGAR O RASPBERRY PI 3.
// echo "<br>Server: <br>"; // print_r($_SERVER);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// TEMPERATURA DA CPU LIDA DIRETAMENTE NO RPi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "{$setSPACER}";
$tempCPU = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
echo "<br>Temperatura ATUAL da UCP: " . $tempCPU / 1000 . " *C";
// CONTROLE DE TEMPERATURA POR PWM
// GPIO-12 E 18 SOMENTE >--> ACEITAM PWM - 0K!
//	GPIO_18 >--> FAN-OUT-PWM		 >--> S
$Tfan_controle = 45000;
echo "<br>Temperatura LIMITE controle FAN = " . $Tfan_controle / 1000 . " *C";
//echo "<br>Temperatura UCP atual = " . $tempCPU / 1000 . " *C";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// STARTS TEMPERATURE WITHOUT PWM CONTROLL
if($tempCPU > $Tfan_controle){
//$gpio_18off = shell_exec("/usr/local/bin/gpio -g write 18 0");
$gpio_18on  = shell_exec("/usr/local/bin/gpio -g write 18 1");
	echo "{$setSPACER}";
	echo "<br>GPIO_18 - FAN CONTROLL sem PWM - LIGADO!  T = ";
	echo "{$tempCPU}";
	echo "{$Tfan_controle}";
	echo "<br>TEMP CPU MAIOR QUE TEMP CONTROLE!";}
if($tempCPU < $Tfan_controle){
$gpio_18off = shell_exec("/usr/local/bin/gpio -g write 18 0");
//$gpio_18on  = shell_exec("/usr/local/bin/gpio -g write 18 1");
	echo "{$setSPACER}";
	echo "<br>GPIO_18 - FAN CONTROLL sem PWM - DESLIGADO! T = ";
	echo "{$tempCPU}";
	echo "{$Tfan_controle}";	
	echo "<br>TEMP CPU MENOR QUE TEMP CONTROLE!";}
echo "{$setSPACER}";
?> 
</body>
</html>