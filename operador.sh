#!/bin/bash
	echo "Bienvenido Operador!"
while true
do
	clear
	echo "---Menu Gestionador---"
	echo "1. Gestion de Usuarios"
	echo "2. Gestion de Grupos"
	echo "3. Gestion de Backups"
    echo "4. Gestion de Firewall"	
    echo "5. Salir"
read -p "Seleccione una opción: " opc
	case $opc in 
	1)./Gestiondeusuarios.sh
   	;;
	2)./Gestiondegrupos.sh
   	;;
	3)./Gestiondebackups.sh
   	;;
    4)./Gestiondefirewall.sh
    ;;	
    5)echo "Saliendo"
	break
	;;
	*)echo "Opcion no valida"
   	;;

	esac
	echo ""
done
