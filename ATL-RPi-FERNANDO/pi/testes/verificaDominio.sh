#!/bin/sh
### Criado por Fernando Viana
### data 25 de Maio de 2017

###
### O parametro 1 tem que ser um dominio, sem http. Exemplo: www.agrotechlink.com
###

WWW=$1;

### Verifica se a internet está no ar ou nao
ping -c 1 $WWW >/dev/null 2>/dev/null

if test $? -ne 0 ; then
echo "www NAO RESPONDE !!!"
else
echo "www FUNCIONANDO !!!"
fi

exit
