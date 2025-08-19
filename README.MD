# StarDust – Simulación de una red social

# Este proyecto es una simulación de red social desarrollada con **HTML, CSS, JavaScript, PHP y phpMyAdmin**, que permite a los usuarios registrarse, crear publicaciones con diferentes tipos de contenido y relacionarse entre sí mediante comentarios y likes.  

# El objetivo es practicar y demostrar habilidades en **desarrollo web full stack**, gestión de bases de datos y autenticación de usuarios.

## Funcionalidades principales

**Gestión de usuarios**  
  -Registro con confirmación por correo electrónico  
  -Inicio de sesión seguro  
  -Edición de perfil (imagen, biografía, fecha de nacimiento, etc.)  
  -Restablecimiento de contraseña mediante email  

**Publicaciones**  
  -Crear posts con texto, imágenes, gifs o vídeos  
  -Etiquetar publicaciones para agruparlas  
  -Editar y eliminar publicaciones propias  

**Comentarios**  
  - Comentar publicaciones de otros usuarios  
  - Editar y eliminar comentarios propios  

**Interacciones**  
  -Sistema de likes en publicaciones  


## Tecnologías utilizadas

**Frontend:** HTML5, CSS3, JavaScript  
**Backend:** PHP  
**Base de datos:** MySQL (gestionada con phpMyAdmin)  
**Otros:**  
  -Envío de correos (registro y recuperación de contraseña)  
  -Validaciones de formularios  

## Instalación y ejecución

1. Clona este repositorio:

  -git clone https://github.com/MarioRC312/StarDust.git
  -copia los archivos en el directorio raíz de tu servidor local, en mi caso uso XAMPP y lo tengo ubicado en "C:\xampp\htdocs\StarDust"
  -copia de la BBDD en el repositorio dentro de "sql/script.sql"
  -Abre en el navegador: "http://localhost/TU-REPO"


## Aprendizajes

**Con este proyecto reforcé conocimientos en:**

  -Autenticación y seguridad básica de usuarios
  -Gestión de sesiones en PHP
  -CRUD completo (crear, leer, actualizar, eliminar) en posts y comentarios
  -Relaciones entre tablas en MySQL (usuarios, posts, comentarios, etiquetas)
  -Manejo de formularios y validaciones
  -Integración de correo electrónico para registro y recuperación de contraseñas