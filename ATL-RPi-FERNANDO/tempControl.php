<?php 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* executar na linha de comando: php tempControl.php
/usr/bin/php  /home/pi/tempControl.php
VERIFICA TEMPERATURA E LIGA VENTILADOR SE MAIOR QUE 45*C OU DESLIGA SE MAIOR 75*C.
CONFIGURADO PARA EXECUTAR O SCRIPT A CADA 5 MINUTOS!
*/ 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// VARIAVEIS DE AJUSTES E CONTROLES
$Tfan_controle = 45000;		// VALOR LIMITE PARA LIGAR OU DESLIGAR A VENTILACAO
$Tcpu_max = 75000;			// RPi SUPORTA NO MAXIMO 80 *C --> 75 SEGURANCA
$setSPACER = "| - - - - - - - - - - - - - - - - - - - |\n";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "{$setSPACER}";
echo "ATL - RPi - PHP - PCI - MAIO - 2017\n";
echo "Interface de Controle e Testes - SEM PWM\n";
echo "ATUALIZA AUTOMATICAMENTE\n";
echo "a todo multiplo de 5 minutos\n";
echo "versao: 6 - revisao: 14052017\n";
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CONFIGURACAO GPIO COMO SAIDA
$setmodeOut18 = shell_exec("/usr/local/bin/gpio -g mode 18 out\n"); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// MOSTRA DATA ATUAL DO LINUX
$x= shell_exec ("date");
echo "Data atual: " . $x;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// TEMPERATURA DA CPU LIDA DIRETAMENTE NO RPi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "{$setSPACER}";
$tempCPU = shell_exec("cat /sys/class/thermal/thermal_zone0/temp");
echo "Temperatura ATUAL da UCP: " . $tempCPU / 1000 . " *C\n";
// CONTROLE DE TEMPERATURA sem PWM - SOMENTE ON/OFF
// GPIO-12 E 18 SOMENTE >--> ACEITAM PWM - 0K!
// GPIO_18 >--> FAN-OUT-PWM		 >--> S
echo "Temperatura MAXIMA de  SHUTDOWN = " . $Tcpu_max / 1000 . " *C\n";
echo "Temperatura LIMITE controle FAN = " . $Tfan_controle / 1000 . " *C\n";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// SHUTDOWN PROVOCANDO DESLIGAMENTO DO RPi - AQUI!
if ($tempCPU > $Tcpu_max){
//$gpio_18off = shell_exec("/usr/local/bin/gpio -g write 18 0\n");
$gpio17SetOut = shell_exec("/usr/local/bin/gpio -g mode 17 out");
$gpio17off  = shell_exec("/usr/local/bin/gpio -g write 17 0");
$gpio17Read = shell_exec("/usr/local/bin/gpio -g read 17"); 
echo "{$gpio17SetOut}"; echo "{$gpio17off}";
echo "ESTADO out GPIO_17: {$gpio17Read}\n"; 
echo "TEMPERATURA CPU CRITICA!\n Tcpu = " . $tempCPU / 1000 . " *C ";		
echo "SUPERIOR LIMITE MAXIMO!\n. . . . . INICIANDO SHUTDOWN . . . . . .\n";
$gpioSHUTDOWN = shell_exec("/usr/bin/sudo shutdown -a now");
echo "{$gpioSHUTDOWN}";}
// $gpioSHUTDOWN = shell_exec("sudo -u root reboot");}
// STARTS TEMPERATURE WITHOUT PWM CONTROLL
else if($tempCPU > $Tfan_controle){
//$gpio_18off = shell_exec("/usr/local/bin/gpio -g write 18 0");
$gpio_18on  = shell_exec("/usr/local/bin/gpio -g write 18 1\n");
	echo "{$setSPACER}";
	echo "GPIO_18 - FAN CONTROLL - LIGADO!\n T = " . $tempCPU / 1000 . " *C\n";		
	echo "TEMP CPU MAIOR QUE TEMP CONTROLE!\n";}
else if($tempCPU < $Tfan_controle){
$gpio_18off = shell_exec("/usr/local/bin/gpio -g write 18 0\n");
//$gpio_18on  = shell_exec("/usr/local/bin/gpio -g write 18 1");
	echo "{$setSPACER}";
	echo "GPIO_18 - FAN CONTROLL - DESLIGADO!\n T = " . $tempCPU / 1000 . " *C\n";	
	echo "TEMP CPU MENOR QUE TEMP CONTROLE!\n";}
echo "{$setSPACER}"; ?>