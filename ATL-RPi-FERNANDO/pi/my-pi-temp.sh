#!/bin/bash
# Script: my-pi-temp.sh
# Purpose: Display the ARM CPU and GPU  temperature of Raspberry Pi 2/3 
# Author: Vivek Gite <www.cyberciti.biz> under GPL v2.x+
# -------------------------------------------------------
cpu=$(</sys/class/thermal/thermal_zone0/temp)
echo "$(date) @ $(hostname)"
echo "|-------------------------------------|"
echo "| T GPU >-------> $(/opt/vc/bin/vcgencmd measure_temp | cut -d = -f2)--------------|"
echo "| T CPU >-------> $((cpu/1000))'C----------------|"
echo "| DIA ANO >-----> $(date +%j)-----------------|"
echo "| SEMANA >------> $(date +%U)------------------|"
echo "| DIA SEMANA >--> $(date +%A)-------------|"
echo "| MES >---------> $(date +%B)---------------|"
echo "| DATA >--------> $(date +%d/%m/%Y)----------|"
echo "| HORARIO >-----> $(date +"%T")------------|"
echo "| TIMEZONE >----> America/Sao_Paulo --|"
echo "|-------------------------------------|"
