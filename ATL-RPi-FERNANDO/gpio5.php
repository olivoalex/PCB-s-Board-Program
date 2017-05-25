<html> 
<head> 
<meta name="viewport" content="width=device-width" /> 
<title>ATLRPi - MAIO17</title> 
</head> 
<body> 
<h1>ATL - RPi - PHP - PCI - MAIO17</h1>
<br>Interface de Controle e Testes
<br>versao: 5 - revisao: 13052017
<form method="get" action="gpio5.php"> 
<br>GPIO_17 >--> BAT-OUT-CONTROL<br>
<input type="submit" value=" ON " name="on17"> 
<!http://11.12.13.30/gpio.php?on=ON>
<input type="submit" value=" OFF " name="off17"> 
<!http://11.12.13.30/gpio.php?off=OFF>
<br>GPIO_27 >--> EXTRA-OUT-ONOFF<br>
<input type="submit" value=" ON " name="on27">
<input type="submit" value=" OFF " name="off27">
<br>GPIO_23 >--> ESTACOES-OUT-ONOFF<br>
<input type="submit" value=" ON " name="on23">
<input type="submit" value=" OFF " name="off23">
<br>GPIO_15 >--> LED-OUT-INTERNET<br>
<input type="submit" value=" ON " name="on15">
<input type="submit" value=" OFF " name="off15">
<br>GPIO_14 >--> PUMP-OUT-ONOFF<br>
<input type="submit" value=" ON " name="on14">
<input type="submit" value=" OFF " name="off14">
<br>SHUTDOWN - REBOOT - CUIDADO >--> RPi<br>
<input type="submit" value=" SHUTDOWN - DO NOT WORK " name="SHUTDOWN">
<input type="submit" value=" REBOOT - 0K " name="REBOOT">
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
$setmodeOut17 = shell_exec("sudo /usr/local/bin/gpio -g mode 17 out"); 
$setmodeOut27 = shell_exec("sudo /usr/local/bin/gpio -g mode 27 out"); 
$setmodeOut23 = shell_exec("sudo /usr/local/bin/gpio -g mode 23 out"); 
$setmodeOut18 = shell_exec("sudo /usr/local/bin/gpio -g mode 18 out");
$setmodeOut15 = shell_exec("sudo /usr/local/bin/gpio -g mode 15 out");
$setmodeOut14 = shell_exec("sudo /usr/local/bin/gpio -g mode 14 out");
echo "{$setmodeOut17}";
echo "{$setmodeOut27}";
echo "{$setmodeOut23}";
echo "{$setmodeOut18}";
echo "{$setmodeOut15}";
echo "{$setmodeOut14}";
// LOGICA DE ACIONAMENTO PARA CADA UMA DAS SAIDAS
//	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
if(isset($_GET['on17'])){ 
$gpio_on = shell_exec("sudo /usr/local/bin/gpio -g write 17 1");} 
else if(isset($_GET['off17'])){ 
$gpio_off = shell_exec("sudo /usr/local/bin/gpio -g write 17 0");} 
//	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S
if(isset($_GET['on27'])){ 
$gpio_on = shell_exec("sudo /usr/local/bin/gpio -g write 27 1");} 
else if(isset($_GET['off27'])){ 
$gpio_off = shell_exec("sudo /usr/local/bin/gpio -g write 27 0");} 
//	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
if(isset($_GET['on23'])){ 
$gpio_on = shell_exec("sudo /usr/local/bin/gpio -g write 23 1");} 
else if(isset($_GET['off23'])){ 
$gpio_off = shell_exec("sudo /usr/local/bin/gpio -g write 23 0");} 
//	GPIO_18 >--> FAN-OUT-PWM		 >--> S
//if(isset($_GET['on18'])){ 
//$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1");} 
//else if(isset($_GET['off18'])){ 
//$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0");} 
//	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
if(isset($_GET['on15'])){ 
$gpio_on = shell_exec("sudo /usr/local/bin/gpio -g write 15 1");} 
else if(isset($_GET['off15'])){ 
$gpio_off = shell_exec("sudo /usr/local/bin/gpio -g write 15 0");} 
//	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S 
if(isset($_GET['on14'])){ 
$gpio_on = shell_exec("sudo /usr/local/bin/gpio -g write 14 1");} 
else if(isset($_GET['off14'])){
$gpio_off = shell_exec("sudo /usr/local/bin/gpio -g write 14 0");}
// LOGICA PARA ENVIAR >--> SHUTDOWN ou REBOOT
if(isset($_GET['SHUTDOWN'])){ 
$gpio_on = shell_exec("/usr/bin/sudo shutdown -a now");}
else if(isset($_GET['REBOOT'])){ 
$gpio_off = shell_exec("sudo -u root reboot");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// LEITURA DOS ESTADOS DAS GPIO USADAS ANTERIORMENTE
$setmode17 = shell_exec("sudo /usr/local/bin/gpio -g read 17"); 
$setmode27 = shell_exec("sudo /usr/local/bin/gpio -g read 27"); 
$setmode23 = shell_exec("sudo /usr/local/bin/gpio -g read 23"); 
$setmode15 = shell_exec("sudo /usr/local/bin/gpio -g read 15"); 
$setmode14 = shell_exec("sudo /usr/local/bin/gpio -g read 14"); 
//	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
//	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S
//	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
//	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
//	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S
echo "<br>ESTADO out GPIO_17: {$setmode17}"; 
echo "<br>ESTADO out GPIO_27: {$setmode27}"; 
echo "<br>ESTADO out GPIO_23: {$setmode23}"; 
echo "<br>ESTADO out GPIO_15: {$setmode15}"; 
echo "<br>ESTADO out GPIO_14: {$setmode14}"; 
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CONFIGURACAO GPIO COMO ENTRADA
$setmodeIn02 = shell_exec("sudo /usr/local/bin/gpio -g mode 2 in");
$setmodeIn03 = shell_exec("sudo /usr/local/bin/gpio -g mode 3 in");
$setmodeIn04 = shell_exec("sudo /usr/local/bin/gpio -g mode 4 in");
echo "{$setmodeIn02}";
echo "{$setmodeIn03}";
echo "{$setmodeIn04}";
// LEITURA GPIO - VERIFICAR FALTA ENERGIA REDE
$setmode02 = shell_exec("sudo /usr/local/bin/gpio -g read 2");
$setmode03 = shell_exec("sudo /usr/local/bin/gpio -g read 3");
$setmode04 = shell_exec("sudo /usr/local/bin/gpio -g read 4");
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
echo "<br>Temperatura ATUAL da UCP: " . $tempCPU/1000 . " *C";
// CONTROLE DE TEMPERATURA POR PWM
// GPIO-12 E 18 SOMENTE >--> ACEITAM PWM - 0K!
//	GPIO_18 >--> FAN-OUT-PWM		 >--> S
$Tfan_controle = 35000;
echo "<br>Temperatura LIMITE controle FAN = " . $Tfan_controle . " / 1000 *C";
echo "<br>Temperatura UCP atual = " . $tempCPU . " / 1000 *C";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// PWM SETTINGS ++ STARTS PWM
$setmodePWM = 		shell_exec("sudo /usr/local/bin/gpio -g mode 18 pwm");
$setratePWM100 = 	shell_exec("sudo /usr/local/bin/gpio -g pwm 18 1023");	// 1023 = 100%
$setratePWM075 =	shell_exec("sudo /usr/local/bin/gpio -g pwm 18 768");	// 768 = 75%
$setratePWM050 = 	shell_exec("sudo /usr/local/bin/gpio -g pwm 18 512");	// 512 = 50%
$setratePWM025 = 	shell_exec("sudo /usr/local/bin/gpio -g pwm 18 256");	// 256 = 25%
$setratePWM000 = 	shell_exec("sudo /usr/local/bin/gpio -g pwm 18 0");		// 0 = 0%
// STARTS PWM WORK
echo "{$setSPACER}";
echo "<br>INICIA PWM!";		echo "{$setmodePWM}"; 
// MONITORING AND CONTROLLING TEMPERATURE
if($tempCPU < $Tfan_controle){ // FAN COOLER DESLIGADO
echo "{$setSPACER}";
//	$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0"); 
	echo "<br>ESTADO out PWM GPIO18 - DESLIGADO: {$setratePWM000}";}
//	echo"<br>FAN DESLIGADO";}
else if($tempCPU > 1.2 * $Tfan_controle){
echo "{$setSPACER}";
//	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
//	echo "<br><br>PWM GPIO18: {$setmodePWM}"; 
//	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 1023");	// CORRIGIR PARA 100%
	echo "<br>RAZAO CICLICA PWM 100= " . 1023*100/1023 . "%" . "{$setratePWM100}";}
//	echo"<br>RAZAO CICLICA PWM = 100%";}
else if($tempCPU > $Tfan_controle){
echo "{$setSPACER}";
//	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
//	echo "<br><br>PWM GPIO18: {$setmodePWM}"; 
//	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 1023");	// CORRIGIR PARA 50%
	echo "<br>RAZAO CICLICA PWM 075= " . 512*100/1023 . "%" . "{$setratePWM075}";}
//	echo"<br>RAZAO CICLICA PWM = 50%";}
echo "{$setSPACER}";
?> 
</body>
</html>