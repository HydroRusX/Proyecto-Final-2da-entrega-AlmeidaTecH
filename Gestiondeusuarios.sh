#!/bin/bash
echo "Bienvenido al gestionador de usuarios"
while true
do
    clear	
    echo "---Menu Gestionador---"
	echo "1. Agregar Usuario"
	echo "2. Remover Usuario"
	echo "3. Modificar usuario"
    echo "4. Agregar a un grupo"
    echo "5. Agregar a un grupo principal"
    echo '6. Eliminar de un grupo'
    echo "7. Ver usuarios exsistente"  
    echo "8. Salir"
read -p ":" opc
	case $opc in 
	1) read -p "Ingresa el nombre del usuario: " nombre
       sudo useradd -m $nombre
       sudo passwd $nombre
   	;;
	
    2) read -p "Ingresa el nombre del usuario a eliminar: " nombre
       echo "Elije si borrar el usuario con o sin directorio"
       echo "1. Sin Directorio"
       echo "2. Con Directorio"
       read -p "Seleccione una opción: " opc2        
       case $opc2 in        
       1) sudo userdel "$nombre"
       ;;
       2) sudo userdel -r "$nombre"
   	   ;;
       esac
    ;;
	3) while true
do
    echo "---Modificacion de usuarios---"
    echo "1. Cambiar nombre de usuario"
    echo "2. Cambiar directorio personal"
    echo "3. Cambiar shell"
    echo "4. Cambiar comentario"
    echo "5. Cambiar contraseña"
    echo "6. Salir"

    read -p "Seleccione una opcion: " opc

    case $opc in
        1)
            read -p "Usuario actual: " usuario
            read -p "Nuevo nombre: " nuevo
            sudo usermod -l "$nuevo" "$usuario"
            ;;

        2)
            read -p "Usuario: " usuario
            read -p "Nuevo directorio home: " home
            sudo usermod -d "$home" -m "$usuario"
            ;;

        

        3)
            read -p "Usuario: " usuario
            echo "Shells disponibles:"
            cat /etc/shells
            read -p "Nueva shell: " shell
            sudo usermod -s "$shell" "$usuario"
            ;;

        4)
            read -p "Usuario: " usuario
            read -p "Nuevo comentario: " comentario
            sudo usermod -c "$comentario" "$usuario"
            ;;

        5)
            read -p "Usuario: " usuario
            sudo passwd "$usuario"
            ;;

        6)  echo "Saliendo"
        break

            ;;

        *)
            echo "Opcion no valida"
            ;;
        esac

        done
    ;;
    
    4) read -p "Usuario: " usuario
       read -p "Grupo secundario: " grupo
       sudo usermod -aG "$grupo" "$usuario"
    ;;
    
    5) read -p "Usuario: " usuario
       read -p 'Grupo principal:' grupo
       sudo usermod -g "$grupo" "$usuario"
    ;;    
    
    6) read -p "Usuario: " usuario
       read -p "Grupo: " grupo
       sudo deluser "$usuario" "$grupo"
    ;;

    7) echo "---Menu Gestionador---"
	   echo "1. Ver todos los usuarios"
	   echo "2. Ver un usuario"
       read -p ":" usu
       case $usu in
        1) cut -d: -f1 /etc/passwd
           read -p "Presione ENTER para continuar..."
        ;;
        
        2) read -p "Ingrese el nombre del usuario: " usuar
        getent passwd "$usuar"
        read -p "Presione ENTER para continuar..."
        ;;
                
        *) echo "Opcion no valida"     
        ;;   
    esac
;;

    8)echo "Saliendo"
      break
    ;;
	
    *)echo "Opcion no valida"
   	;;

	esac
done
