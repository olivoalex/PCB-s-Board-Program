<html> 
<head> 
<meta name="viewport" content="width=device-width" /> 
<title>LED Control</title> 
</head> 
<body> 
PHP - TEST LED_17 + 18 + 27 + 22 + 23 Control: 
<form method="get" action="gpio.php"> 
<input type="submit" value="ON" name="on"> 
<input type="submit" value="OFF" name="off"> 
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

echo "<br>ESTADO 17: {$setmode17}"; 
echo "<br>ESTADO 18: {$setmode18}"; 
echo "<br>ESTADO 27: {$setmode27}"; 
echo "<br>ESTADO 22: {$setmode22}"; 
echo "<br>ESTADO 23: {$setmode23}"; 

echo "<br>Server: <br>";
print_r($_SERVER);
?> 
</body>
</html>