#!/bin/bash

while true
do
    clear
    
    echo "--- Menu Gestionador ---"
    echo "1. Abrir puerto"
    echo "2. Cerrar puerto"
    echo "3. Agregar regla"
    echo "4. Eliminar regla"
    echo "5. Listar reglas activas"
    echo "6. Activar firewall"
    echo "7. Desactivar firewall"
    echo "8. Salir"
    

    read -p "Seleccione una opción: " opcion

    case $opcion in

        1)
            echo "--- Abrir puerto ---"

            read -p "Ingrese el puerto: " puerto
            read -p "Ingrese el protocolo (tcp/udp): " protocolo

            sudo ufw allow "$puerto/$protocolo"

            echo "Puerto $puerto/$protocolo abierto."

            read -p "Presione ENTER para continuar..."
            ;;

        2)
            echo "--- Cerrar puerto ---"

            read -p "Ingrese el puerto: " puerto
            read -p "Ingrese el protocolo (tcp/udp): " protocolo

            sudo ufw delete allow "$puerto/$protocolo"

            echo "Regla del puerto $puerto/$protocolo eliminada."

            read -p "Presione ENTER para continuar..."
            ;;

        3)
            echo "--- Agregar regla ---"

            echo "1. Permitir"
            echo "2. Denegar"

            read -p "Seleccione el tipo de regla: " tipo
            read -p "Ingrese el puerto: " puerto
            read -p "Ingrese el protocolo (tcp/udp): " protocolo

            case $tipo in

                1)
                    sudo ufw allow "$puerto/$protocolo"
                    echo "Regla de permiso agregada."
                    ;;

                2)
                    sudo ufw deny "$puerto/$protocolo"
                    echo "Regla de denegación agregada."
                    ;;

                *)
                    echo "Tipo de regla inválido."
                    ;;

            esac

            read -p "Presione ENTER para continuar..."
            ;;

        4)  sudo ufw status numbered

            echo ""
            read -p "Ingrese el número de la regla a eliminar: " numero

            sudo ufw delete "$numero"

            echo "Regla eliminada."

            read -p "Presione ENTER para continuar..."
            ;;

        5)  sudo ufw status numbered

            read -p "Presione ENTER para continuar..."
            ;;

        6)  sudo ufw enable

            echo "Firewall activado."

            read -p "Presione ENTER para continuar..."
            ;;

        7)  sudo ufw disable

            echo "Firewall desactivado."

            read -p "Presione ENTER para continuar..."
            ;;

        8)
            echo "Saliendo de Gestión de firewall..."
            break
            ;;

        *)
            echo "Opción inválida."
            read -p "Presione ENTER para continuar..."
            ;;

    esac
done

