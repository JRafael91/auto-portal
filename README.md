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

### 6. Verificar conexión entre la aplicación y la base de datos
```bash
php artisan db:show
```

### 7. Crear usuario SuperAdmin

```bash
php artisan make:filament-user
```

### 8. Correr los Seeder para la creación de productos.
```bash
php artisan db:seed
```
### 9.- Pantallas
- Login, por medio de un correo y contraseña, al cuál se le manda un correo con un código OTP.
![Captura desde 2024-10-28 21-22-35](https://github.com/user-attachments/assets/ecac1858-3504-4ea7-9944-757656590460)
- Dashboard, donde se muestra el conteo de las órdenes y el monto total por mes. 
![Captura desde 2024-10-28 21-25-22](https://github.com/user-attachments/assets/999c22b7-d9af-46fb-b1e6-14124aa2e803)
- CRUD de Productos
![Captura desde 2024-10-28 21-25-50](https://github.com/user-attachments/assets/99d6c195-cfe4-4128-af8a-a167fa85da71)
- CRUD de Órdenes, también se asigna al técnico asignado para esa orden.
![Captura desde 2024-10-28 21-26-24](https://github.com/user-attachments/assets/78c52127-666e-4a00-b109-e2f791b3d3a7)
![Captura desde 2024-10-28 21-26-36](https://github.com/user-attachments/assets/34700874-aca7-4850-a8dc-9892daeae5ba)

### Otras funcionalidades
- Actualizar información de usuario y avatar.
- CRUD de técnicos, los cuáles tienen acceso a la aplicacion móvil.


