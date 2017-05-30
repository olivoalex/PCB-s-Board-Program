<?php 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* executar na linha de comando: php gpioOnRelayOnStartup.php.php	OU
/usr/bin/php /home/pi/gpioOnRelayOnStartup.php
SCRIPT PARA LIGAR RELE NO GPIO-17 QUE ALIMENTA O RPi 3 ATRAVES DA BATERIA
TODA VEZ QUE ELE FOR REINICIADO OU LIGADO! */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// VARIAVEIS DE AJUSTES E CONTROLES
$setSPACER = "| - - - - - - - - - - - - - - - - - - - |\n";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
echo "{$setSPACER}";
echo "ATL - RPi - PHP - PCI - MAIO - 2017\n";
echo "AGENDAMENTO PARA LIGAR - GPIO-17\n";
echo "RELE BAT_OUT_CONTROL - CONEXAO COM BATERIA\n";
echo "ATUALIZA AUTOMATICAMENTE\n";
echo "a todo reboot - REINICIO\n";
echo "versao: 1.1 - revisao: 18052017\n";
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CONFIGURACAO GPIO COMO SAIDA
$setmodeOut17 = 	shell_exec("/usr/local/bin/gpio -g mode 17 out"); 
$gpio02SetIn = 		shell_exec("/usr/local/bin/gpio -g mode 2 in");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// MOSTRA DATA ATUAL DO LINUX
$x= shell_exec ("date");
echo "Data atual: " . $x;
echo "{$setSPACER}";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
$gpio17SetOut = 	shell_exec("/usr/local/bin/gpio -g mode 17 out");
//$gpio17off  = 		shell_exec("/usr/local/bin/gpio -g write 17 0");
$gpio17on  = 		shell_exec("/usr/local/bin/gpio -g write 17 1");
$gpio17Read = 		shell_exec("/usr/local/bin/gpio -g read 17"); 
$gpio02Read = 		shell_exec("/usr/local/bin/gpio -g read 2");
echo "{$gpio17SetOut}"; 
echo "{$gpio17on}";
echo "ESTADO out GPIO_17: {$gpio17Read}"; 
echo "ESTADO In GPIO_02: {$gpio02Read}"; 
echo "[0] COM REDE ELETRICA CELESC\n[1] FALTA ENERGIA REDE ELETRICA\n";
echo "{$setSPACER}"; ?>