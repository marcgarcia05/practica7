-- Script para crear un nuevo usuario para Laravel sin depender de mysql_native_password
-- Creamos un nuevo usuario 'laravel_user' con una contraseña segura
CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'Laravel_2025';

-- Otorgamos privilegios completos sobre la base de datos específica
GRANT ALL PRIVILEGES ON `Pt04_Marc_Garcia`.* TO 'laravel_user'@'localhost';

-- Si estás usando MySQL 8.0+, ajustamos la autenticación
-- Esto permite que PDO se conecte sin problemas
ALTER USER 'laravel_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Laravel_2025';

-- También podemos usar esta configuración alternativa si la anterior falla
-- ALTER USER 'laravel_user'@'localhost' IDENTIFIED WITH sha256_password BY 'Laravel_2025';
-- O incluso (en MySQL 8.0)
-- ALTER USER 'laravel_user'@'localhost' IDENTIFIED WITH caching_sha2_password BY 'Laravel_2025';

-- Aplicamos los cambios
FLUSH PRIVILEGES;

-- Mostramos los usuarios para verificar
SELECT user, host, plugin FROM mysql.user WHERE user = 'laravel_user';