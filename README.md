# Auto Portal
Aplicación para la gestión de órdenes de servicios.

## Requisitos

- [Docker](https://www.docker.com/)

## Instrucciones para iniciar proyecto

### 1. Clonar Repositorio

Abre una terminal y navega al directorio donde deseas clonar el repositorio.
Luego, ejecuta el siguiente comando:

```bash
git clone https://github.com/JRafael91/auto-portal.git
```

### 2. Navegar a Carpeta de Proyecto

```bash
cd nombre-del-proyecto
```
### 3. Configuración el Archivo .env

Copia el archivo `.env.example` y créalo como `.env`

```bash
cp .env.example .env
```

Después, abre el archivo .env en un editor de texto y configura las variables de entorno necesarias,
como la conexión a la base de datos.

Variables por defecto para conectar la base de datos.

```editorconfig
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=auto_portal
DB_USERNAME=root
DB_PASSWORD=root
```
### 4. Configurar Docker
El proyecto utiliza Docker, asegúrate de tener Docker instalado en tu sistema.
Luego, navega al directorio del proyecto, el archivo docker-compose.yml carga las configuraciones de la base de datos proporcionadas
por el archivo .env, por lo cual debes configurar una conexión de base de datos desde el archivo .env.

Al usar los servicios de docker compose, la variable `DB_HOST` debe ser el nombre del contenedor
que proporciona el servicio de base de datos.

### 5. Construir y Levantar los Contenedores

Ejecuta el siguiente comando para construir 
y levantar los contenedores definidos en el archivo `docker-compose.yml`.

```bash
docker compose up -d
```

Esto iniciará los contenedores en segundo plano.

### 6. Crear usuario SuperAdmin

```bash
php artisan make:filament-user
```
