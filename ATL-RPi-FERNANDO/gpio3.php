<html> 
<head> 
<meta name="viewport" content="width=device-width" /> 
<title>ATLRPi - MAIO17</title> 
</head> 
<body> 
<h1>ATL - RPi - PHP - PCI - MAIO17</h1>
<br>Interface de Controle e Testes
<br>versao: 2 - revisao: 06052017
<form method="get" action="gpio3.php"> 
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
<br>SHUTDOWN >--> RPi<br>
<input type="submit" value=" ON " name="onSHUTDOWN">
<br>REBOOT >--> RPi<br>
<input type="submit" value=" ON " name="onREBOOT">
</form> 
<?php 
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
$setmode17 = shell_exec("/usr/local/bin/gpio -g mode 17 out"); 
$setmode27 = shell_exec("/usr/local/bin/gpio -g mode 27 out"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g mode 23 out"); 
$setmode18 = shell_exec("/usr/local/bin/gpio -g mode 18 out");
$setmode15 = shell_exec("/usr/local/bin/gpio -g mode 15 out");
$setmode14 = shell_exec("/usr/local/bin/gpio -g mode 14 out");
// LOGICA DE ACIONAMENTO PARA CADA UMA DAS SAIDAS
//	GPIO_17 >--> BAT-OUT-CONTROL	 >--> S
if(isset($_GET['on17'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 1");} 
else if(isset($_GET['off17'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 0");} 
//	GPIO_27 >--> EXTRA-OUT-ONOFF	 >--> S
if(isset($_GET['on27'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 27 1");} 
else if(isset($_GET['off27'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 27 0");} 
//	GPIO_23 >--> ESTACOES-OUT-ONOFF	 >--> S
if(isset($_GET['on23'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 23 1");} 
else if(isset($_GET['off23'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 23 0");} 
//	GPIO_18 >--> FAN-OUT-PWM		 >--> S
//if(isset($_GET['on18'])){ 
//$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1");} 
//else if(isset($_GET['off18'])){ 
//$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0");} 
//	GPIO_15 >--> LED-OUT-INTERNET	 >--> S
if(isset($_GET['on15'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 15 1");} 
else if(isset($_GET['off15'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 15 0");} 
//	GPIO_14 >--> PUMP-OUT-ONOFF		 >--> S 
if(isset($_GET['on14'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 14 1");} 
else if(isset($_GET['off14'])){
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 14 0");}
// LOGICA PARA ENVIAR >--> onSHUTDOWN
if(isset($_GET['onSHUTDOWN'])){ 
$gpio_on = shell_exec("sudo -u root shutdown -P now");}
if(isset($_GET['onREBOOT'])){ 
$gpio_on = shell_exec("sudo -u root reboot");}

// LEITURA DOS ESTADOS DAS GPIO USADAS ANTERIORMENTE
$setmode17 = shell_exec("/usr/local/bin/gpio -g read 17"); 
$setmode27 = shell_exec("/usr/local/bin/gpio -g read 27"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g read 23"); 
$setmode15 = shell_exec("/usr/local/bin/gpio -g read 15"); 
$setmode14 = shell_exec("/usr/local/bin/gpio -g read 14"); 
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

// CONFIGURACAO GPIO COMO ENTRADA
$setmode02 = shell_exec("/usr/local/bin/gpio -g mode 2 in");
$setmode03 = shell_exec("/usr/local/bin/gpio -g mode 3 in");
$setmode04 = shell_exec("/usr/local/bin/gpio -g mode 4 in");
// LEITURA GPIO - VERIFICAR FALTA ENERGIA REDE
$setmode02 = shell_exec("/usr/local/bin/gpio -g read 2");
$setmode03 = shell_exec("/usr/local/bin/gpio -g read 3");
$setmode04 = shell_exec("/usr/local/bin/gpio -g read 4");
echo "<br><br>ESTADO in GPIO_02: {$setmode02}"; 
echo "<br>GPIO_02 >--> REDE-IN-FALHA >--> [1] = COM 220V [0] = SEM REDE >--> E";
echo "<br>ESTADO in GPIO_03: {$setmode03}"; 
echo "<br>GPIO_03 >--> EXTRA-IN	>--> [1] = ON [0] = OFF >--> E";
echo "<br>ESTADO in GPIO_04: {$setmode04} <br>"; 
echo "<br>GPIO_04 >--> LEVEL-IN	>--> [1] = ON [0] = OFF >--> E";
// MOSTRA DATA ATUAL DO LINUX
$x= shell_exec ("date");
echo "<br>Data atual: " . $x;
// A SER USADO PARA VERIFICAR FALTA DE ENERGIA ELETRICA ELETRICA DESLIGAR O RASPBERRY PI 3.
// echo "<br>Server: <br>"; // print_r($_SERVER);
// TEMPERATURA DA CPU LIDA DIRETAMENTE NO RPi
$tempCPU = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
echo "<br>Temperatura ATUAL da UCP: " . $tempCPU/1000 . " *C";
// CONTROLE DE TEMPERATURA POR PWM
// GPIO-12 E 18 SOMENTE >--> ACEITAM PWM - 0K!
//	GPIO_18 >--> FAN-OUT-PWM		 >--> S
$Tfan_controle = 35000;
echo "<br>Temperatura LIMITE controle FAN = " . $Tfan_controle . " / 1000 *C";
echo "<br>Temperatura UCP atual = " . $tempCPU . " / 1000 *C";
if($tempCPU < $Tfan_controle){
	$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0"); 
	echo "<br>ESTADO out PWM GPIO18: {$setmode18}";
	echo"<br>FAN DESLIGADO";}
else if($tempCPU > 1.2 * $Tfan_controle){
	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
	echo "<br><br>PWM GPIO18: {$setmodePWM}"; 
	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 1023");	// CORRIGIR PARA 100%
	echo "<br>Razao Ciclica PWM = " . 1023*100/1023 . "%" . "{$setratePWM}";
	echo"<br>RAZAO CICLICA PWM = 100%";}
else if($tempCPU > $Tfan_controle){
	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
	echo "<br><br>PWM GPIO18: {$setmodePWM}"; 
	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 1023");	// CORRIGIR PARA 50%
	echo "<br>Razao Ciclica PWM = " . 512*100/1023 . "%" . "{$setratePWM}";
	echo"<br>RAZAO CICLICA PWM = 50%";}
?> 
</body>
</html>