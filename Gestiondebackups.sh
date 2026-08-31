#!/bin/bash

while true
do
    clear
    echo "---Menu Gestionador de back ups---"
    echo "1. Generar copia de seguridad"
    echo "2. Programar respaldo periódico"
    echo "3. Restaurar datos"
    echo "4. Ver backups disponibles"
    echo "5. Salir"
    read -p "Seleccione una opción: " opcion

    case $opcion in

        1)  read -p "Ingrese el archivo/directorio a respaldar: " origen
            read -p "Ingrese el destino del backup: " destino

            if [ -e "$origen" ]; then
                mkdir -p "$destino"
                rsync -avh "$origen" "$destino/"
                echo "Backup realizado correctamente."
            else
                echo "El archivo o directorio no existe."
            fi

            read -p "Presione ENTER para continuar..."
            ;;

        2)  read -p "Ingrese el archivo/directorio a respaldar: " origen
            read -p "Ingrese el destino del backup: " destino
            read -p "Ingrese cada cuántos minutos realizar el backup: " minutos

            mkdir -p "$destino"

            (crontab -l 2>/dev/null; echo "*/$minutos * * * * rsync -avh '$origen' '$destino/'") | crontab -

            echo "Backup programado cada $minutos minutos."
            read -p "Presione ENTER para continuar..."
            ;;

        3)  read -p "Ingrese la ubicación del backup: " backup
            read -p "Ingrese dónde restaurar los datos: " destino

            if [ -e "$backup" ]; then
                mkdir -p "$destino"
                rsync -avh "$backup" "$destino/"
                echo "Datos restaurados correctamente."
            else
                echo "El backup no existe."
            fi

            read -p "Presione ENTER para continuar..."
            ;;

        4)  crontab -l
	    read -p "Presione ENTER para continuar..."
            ;;

        5)
            echo "Saliendo..."
            break
            ;;

        *)
            echo "Opción inválida."
            read -p "Presione ENTER para continuar..."
            ;;
    esac
done
