Hola profesores, bienvenidos al github de AlmeidaTech.
Como aun no se del todo como usar github tuve que crear otro para poder subir los archivos de esta entrega.

El avance del proyecto en esta segunda entrega fue muy satisfactorio a comparacion de la primera, todos los puntos que se piden estan totalmente operativos exceptuando el modulo de ambulancias.

NOTAS:
1. Debido a que Usuarios y Traslados aun no estan operativos no se mencionan en el manual de uso.




-- Manual de instalacion --
Es necesario tener activo Apache y Mysql.

En nuestro sistema de clinicas, se debe respetar el orden de carpetas en las imagenes que se muestran en esta branch 
se puede como se ordena cada una de las carpetas, no es muy dificil debido ya que cada uno de sus nombres hace referencia a su tipo de archivo 
que se guarda y mientras se respeto eso las rutas que hay en el codigo deberian funcionar.

Lo siguiente seria importar la base de datos, tendrias que crear una tabla llamada "projecto" y ahi mismo importar la base de datos, 
esto es obligatorio debido a que conexion.php llama a la tabla "projecto"
en el caso que no haya ninguna tabla llamada asi no va a andar el sistema.

Si no funciona el sistema HABRAS hecho algo mal.

-- Manual de uso --
Si funciono todo correctamente te vas a encontrar en el index estando como "invitado"
invitado es un usuario pensado para: pacientes y familiares de los mismos ¿porque no aparece dentro de la base de datos? esto es debido a que nuestro cliente el hospital de clinicas no nos pidio como requerimiento un login para gente externa al hospital, nos aclararon que era al contrario que nosotros decidimos utilizar esta solucion.
Al estar como invitado unicamente podes acceder a la parte de repositorio y encuestas anonimas, todo los demas estara bloqueado.
Al repositorio podes acceder mediante codigo qr o el boton de ¨acceder al repositorio¨ este ultimo boton se añadio para mejor practicidad en pruebas y en el uso real de la aplicacion.
A las encuestas anonimas accedes mediante el boton con el mismo nombre y ahi pasas a un apartado que muestra cada una de las encuestas disponibles a responder.

Si entras como funcionario vas a tener desbloqueado todo el menu pero unicamente teniendo permisos para consultas
debido a esto al acceder a administrar encuestas y documentos solo veras ¨ver resultados¨(Encuestas) y ¨ver/ver achivo¨(documento).

Si entras como administrador igualmente todo el menu va a estar desbloqueado a su vez que las funciones administrativas de administrar encuestas y documentos.
en administrar encuestas tenes  6 botones para administrar los documentos
¨Subir Documento¨ -- sirve para dar de alta un documento, tiene una interfaz donde pide titulo, archivo (limitado a tipos de archivos que funcionan como pdfs) y seleccionar unas categorias para el archivo, siendo obligatorio elegir una
¨ver/ver achivo¨ -- Sirve para visualizar el pdf 
¨Editar¨ -- que sirve para modificar el titulo,categoria y remplazar el propio archivo. A su vez indica si esta activo y podes visualizar nuevamente q archivo hay
¨Baja Logica¨ -- sirve para bajar logicamente el archivo, en criollo desactiva el archivo de la visualizacion publica, el boton es remplazado con reactivar cuando esta bajado mediante esta forma}
¨Baja Fisica¨ -- sirve para eliminar directamente el archivo, hay que tener cuidado


mientras que en administrar encuestas es asi
¨Crear 









