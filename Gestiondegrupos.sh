#!/bin/bash
	echo "Bienvenido al gestionador de grupos!"
while true
do
	clear
    echo "---Menu Gestionador---"
	echo "1. Alta de Grupo"
	echo "2. Baja de Grupo"
	echo "3. Modificar Grupo"
	echo "4. Ver Grupos"    
    echo "5. Salir"
read -p "Seleccione una opción: " opc
	case $opc in 
	1) read -p "Nombre del grupo a agregar: " grupo
       sudo groupadd $grupo
   	   read -p "Presione ENTER para continuar..."
;;
	2) read -p "Nombre del grupo a eliminar: " grupo
       sudo groupdel $grupo
       read -p "Presione ENTER para continuar..."   	
;;
    3) echo "Que desea modificar?"
       echo "1. Nombre"
       echo "2. GID"

       read -p "Opcion: " mod
       
       case $mod in
        
          1)
             read -p "Nombre actual: " actual
             read -p "Nombre Nuevo: " nuevo
            
             sudo groupmod -n $nuevo $actual
             ;;
          
          2) 
             read -p "Nombre del grupo: " grupo
             read -p "Nuevo GID: " gid

             sudo groupmod -g $gid $grupo
             ;;
          *)
             echo "Opcion Invalida"
             ;;
        esac
        read -p "Presione ENTER para continuar..."        
;;
	4)   echo "---Menu Gestionador---"
	     echo "1. Ver un grupo en especifico"
	     echo "2. Ver todos los grupos"
    read -p ":" gp
    case $gp in
        
        1)read -p "Ingrese el nombre del grupo: " grupp
        getent group "$grupp"
        read -p "Presione ENTER para continuar..."
        ;;        
            
        2)getent group 
        read -p "Presione ENTER para continuar..."
        ;;
        
        *)echo "Opcion no valida"
        ;;	
    esac
;;
    5)
    echo "Saliendo"
        break
    ;;
	*)echo "Opcion no valida"
   	;;

	esac
	echo ""
done
