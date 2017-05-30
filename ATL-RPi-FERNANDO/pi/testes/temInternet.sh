#!/bin/sh
### Criado por Fernando Viana
### data 25 de Maio de 2017

### Verifica se a internet está no ar ou nao
ping -c 1 www.agrotechlink.com >/dev/null 2>/dev/null

if test $? -ne 0 ; then
echo "Internet I está PARADA !!!"
ping -c 1 www.uol.com.br >/dev/null 2>/dev/null
if test $? -ne 0 ; then
echo "Internet II está PARADA !!!"
ping -c 1 www.googl.com >/dev/null 2>/dev/null
if test $? -ne 0 ; then
echo "Internet III está PARADA !!!"
else
echo "Internet III FUNCIONANDO !!!"
fi
else
echo "Internet II FUNCIONANDO !!!"
fi
else
echo "Internet I FUNCIONANDO !!!"
fi
exit
