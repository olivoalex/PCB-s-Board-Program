<html> 
<head> 
<meta name="viewport" content="width=device-width" /> 
<title>ATL - RPi - PHP</title> 
</head> 
<body> 
ATL - RPi - PHP
<br>Interface de Controle e Testes
<br>GPIO_17 <br> GPIO_18 <br> GPIO_27 <br> GPIO_22 <br> GPIO_23: 
<form method="get" action="gpio.php"> 
<input type="submit" value="ON" name="on"> 
<!http://11.12.13.30/gpio.php?on=ON>
<input type="submit" value="OFF" name="off"> 
<!http://11.12.13.30/gpio.php?off=OFF>
</form> 

<?php 
$setmode17 = shell_exec("/usr/local/bin/gpio -g mode 17 out"); 
$setmode18 = shell_exec("/usr/local/bin/gpio -g mode 18 out"); 
$setmode27 = shell_exec("/usr/local/bin/gpio -g mode 27 out"); 
$setmode22 = shell_exec("/usr/local/bin/gpio -g mode 22 out"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g mode 23 out"); 

if(isset($_GET['on'])){ 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 17 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 18 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 27 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 22 1"); 
$gpio_on = shell_exec("/usr/local/bin/gpio -g write 23 1"); 
echo "<br>LED is on: {$gpio_on}"; 
} 
else if(isset($_GET['off'])){ 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 17 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 18 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 27 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 22 0"); 
$gpio_off = shell_exec("/usr/local/bin/gpio -g write 23 0"); 
echo "<br>LED is off {$gpio_off}"; 
} 
$setmode17 = shell_exec("/usr/local/bin/gpio -g read 17"); 
$setmode18 = shell_exec("/usr/local/bin/gpio -g read 18"); 
$setmode27 = shell_exec("/usr/local/bin/gpio -g read 27"); 
$setmode22 = shell_exec("/usr/local/bin/gpio -g read 22"); 
$setmode23 = shell_exec("/usr/local/bin/gpio -g read 23"); 

echo "<br>ESTADO out 17: {$setmode17}"; 
echo "<br>ESTADO out 18: {$setmode18}"; 
echo "<br>ESTADO out 27: {$setmode27}"; 
echo "<br>ESTADO out 22: {$setmode22}"; 
echo "<br>ESTADO out 23: {$setmode23}"; 
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
?> 
</body>
</html>