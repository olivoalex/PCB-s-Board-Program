<html> 
<head> 
<meta name="viewport" content="width=device-width" /> 
<title>ATL - RPi - PHP</title> 
</head> 
<body> 
<h1>ATL - RPi - PHP</h1>
<br>Interface de Controle e Testes
<form method="get" action="gpio2.php"> 
<br> GPIO_17: <br>
<input type="submit" value="ON" name="on17"> <!http://11.12.13.30/gpio.php?on=ON>
<input type="submit" value="OFF" name="off17"> <!http://11.12.13.30/gpio.php?off=OFF>
<br> GPIO_27: <br>
<input type="submit" value="ON" name="on27">
<input type="submit" value="OFF" name="off27">
<br> GPIO_22: <br>
<input type="submit" value="ON" name="on22">
<input type="submit" value="OFF" name="off22">
<br> GPIO_23: <br>
<input type="submit" value="ON" name="on23">
<input type="submit" value="OFF" name="off23">
<br> GPIO_ALL: <br>
<input type="submit" value="ON" name="onALL">
<input type="submit" value="OFF" name="offALL">
</form> 
<?php 
$setmode17 = shell_exec("/usr/local/bin/gpio -g mode 17 out"); 
// $setmode18 = shell_exec("/usr/local/bin/gpio -g mode 18 out");
$setmode27 = shell_exec("/usr/local/bin/gpio -g mode 27 out"); 
$setmode22 = shell_exec("/usr/local/bin/gpio -g mode 22 out"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g mode 23 out"); 
if(isset($_GET['on17'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 1");} 
else if(isset($_GET['off17'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 0");} 
//if(isset($_GET['on18'])){
//$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1");}
//else if(isset($_GET['off18'])){
//$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0");}
if(isset($_GET['on27'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 27 1");} 
else if(isset($_GET['off27'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 27 0");} 
if(isset($_GET['on22'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 22 1");} 
else if(isset($_GET['off22'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 22 0");} 
if(isset($_GET['on23'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 23 1");} 
else if(isset($_GET['off23'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 23 0");} 
if(isset($_GET['onALL'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 1"); 
//$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1");
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 27 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 22 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 23 1");} 
else if(isset($_GET['offALL'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 0"); 
//$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0");
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 27 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 22 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 23 0");} 
// LEITURA DOS ESTADOS DAS GPIO USADAS ANTERIORMENTE
$setmode17 = shell_exec("/usr/local/bin/gpio -g read 17"); 
//$setmode18 = shell_exec("/usr/local/bin/gpio -g read 18");
$setmode27 = shell_exec("/usr/local/bin/gpio -g read 27"); 
$setmode22 = shell_exec("/usr/local/bin/gpio -g read 22"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g read 23"); 
echo "<br>ESTADO out LED17: {$setmode17}"; 
//echo "<br>ESTADO out LED18: {$setmode18}";
echo "<br>ESTADO out LED27: {$setmode27}"; 
echo "<br>ESTADO out LED22: {$setmode22}"; 
echo "<br>ESTADO out LED23: {$setmode23}"; 
// CONFIGURACAO GPIO COMO ENTRADA
$setmode24 = shell_exec("/usr/local/bin/gpio -g mode 24 in");
// LEITURA GPIO - VERIFICAR FALTA ENERGIA REDE
$setmode24 = shell_exec("/usr/local/bin/gpio -g read 24");
echo "<br><br>ESTADO in 24: {$setmode24}"; 
// MOSTRA DATA ATUAL DO LINUX
$x= shell_exec ("date");
echo "<br>Data atual: " . $x;
// A SER USADO PARA VERIFICAR FALTA DE ENERGIA ELETRICA ELETRICA DESLIGAR O RASPBERRY PI 3.
//echo "<br>Server: <br>";
//print_r($_SERVER);
// TEMPERATURA DA CPU LIDA DIRETAMENTE NO RPi
$tempCPU = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
echo "<br>Temperatura da UCP: " . $tempCPU/1000 . " *C";

// TESTES DE CONTROLE PWM GPIO-12 E 18 SOMENTE ACEITAM PWM - 0K!
//$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
//echo "<br>PWM NO LED GPIO 18: {$setmodePWM}"; 
//$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 100");
//echo "<br>Razao Ciclica PWM = " . 100*100/1023 . "%" . "{$setratePWM}";

// CONTROLE DE TEMPERATURA POR PWM
$Tfan_controle = 35000;
echo "<br><br>Temperatura controle FAN = " . $Tfan_controle . " x 1000 *C";
echo "<br>Temperatura UCP atual = " . $tempCPU . " x 1000 *C";

if($tempCPU < $Tfan_controle){
	$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0"); 
	echo "<br>ESTADO out LED18: {$setmode18}";
	echo"<br>FAN DESLIGADO";}
else if($tempCPU > 1.2 * $Tfan_controle){
	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
	echo "<br>PWM NO LED GPIO 18: {$setmodePWM}"; 
	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 1023");	// CORRIGIR PARA 100%
	echo "<br>Razao Ciclica PWM = " . 1023*100/1023 . "%" . "{$setratePWM}";
	echo"<br>RAZAO CICLICA PWM = 100%";}
else if($tempCPU > $Tfan_controle){
	$setmodePWM = shell_exec("/usr/local/bin/gpio -g mode 18 pwm");
	echo "<br>PWM NO LED GPIO 18: {$setmodePWM}"; 
	$setratePWM =  shell_exec("/usr/local/bin/gpio -g pwm 18 25");	// CORRIGIR PARA 50%
	echo "<br>Razao Ciclica PWM = " . 512*100/1023 . "%" . "{$setratePWM}";
	echo"<br>RAZAO CICLICA PWM = 50%";}
?> 
</body>
</html>