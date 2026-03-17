## PASOS DE EJECUCION EN LINUX

1. Clonar el repositorio (en el caso de ubuntu asegurarse que los archivos queden en var/www/html)
2. Habilitar el modulo de reescritura de apache (a2enmod rewrite) (es posible que tambien se tenga que modificar el archivo de configuracion de apache2.conf y asignar AllowOverride en All para el directorio /var/www)
3. Cambiar el propietario de los archivos
4. Asegurarse que resources/ tenga permisos de lectura y escritura (chmod -R 766 resources/)
5. configurar la base de datos en config/database.php (si te da y para usar mariadb siempre debes usar "sudo" entonces intenta crear un nuevo usuario de mariadb y asignar ese a la aplicacion)


## Algunos consejos

### Montar la base de datos
Cuando debas montar la base de datos si el proyecto te esta funcionando pudes usar ```php bin/console migrate -s```, con esto se te crearan las tablas de la base de datos junto a unos pocos datos de prueba
