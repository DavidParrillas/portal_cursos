# Curzilla - Portal de Cursos Online

Curzilla es un portal web para la gestión, venta e inscripción a cursos cortos y talleres en línea. La plataforma está diseñada para conectar a instructores con estudiantes, ofreciendo una experiencia de aprendizaje fluida y completa.

## ✨ Características Principales

-   **Autenticación de Usuarios:** Sistema de registro e inicio de sesión seguro con roles diferenciados (Estudiante, Instructor, Administrador).
-   **Gestión de Cursos:** Los instructores pueden crear, editar y gestionar sus propios cursos, incluyendo descripción, contenido multimedia y materiales.
-   **Exploración y Búsqueda:** Los usuarios pueden explorar cursos por categorías y encontrar los más vendidos o recomendados.
-   **Inscripción a Cursos:** Flujo de inscripción sencillo para que los estudiantes accedan al contenido.
-   **Panel de Control por Rol:** Dashboards personalizados para estudiantes (mis cursos), instructores (gestión de cursos) y administradores (gestión de la plataforma).
-   **Procesamiento de Pagos:** Integración con pasarelas de pago para la compra de cursos (ej. PayPal).

## 🛠️ Tecnologías Utilizadas

-   **Backend:** PHP 8+ (Nativo, siguiendo el patrón MVC)
-   **Frontend:** HTML5, CSS3 (con estilos personalizados), JavaScript (ES6+)
-   **Iconos:** Font Awesome
-   **Base de Datos:** MySQL / MariaDB
-   **Servidor Local:** XAMPP / WAMP / MAMP
-   **Pasarela de Pago (Ejemplo):** PayPal Sandbox

## 📂 Estructura del Proyecto

El proyecto sigue una estructura MVC para una clara separación de responsabilidades:

-   `/config`: Archivos de configuración (base de datos, variables de entorno).
-   `/controllers`: Lógica de negocio y coordinación entre modelos y vistas.
-   `/models`: Representación de los datos y la interacción con la base de datos.
-   `/views`: Capa de presentación (archivos HTML/PHP y layouts).
-   `/public`: Punto de entrada (`index.php`) y recursos públicos (CSS, JS, imágenes).
-   `/routes`: Definición de las rutas de la aplicación (web y API).
-   `/docs`: Documentación detallada del proyecto.

## 🚀 Instalación

# Instalación de XAMPP

XAMPP es un paquete de software libre que incluye Apache, MySQL, PHP y Perl para crear un servidor web local.

## 📋 Requisitos del Sistema

### Windows
- **Sistema Operativo:** Windows 7, 8, 10, 11 (32 o 64 bits)
- **Memoria RAM:** Mínimo 512 MB, recomendado 1 GB
- **Espacio en disco:** Mínimo 1.5 GB de espacio libre

### Mac
- **Sistema Operativo:** macOS 10.10 o superior
- **Memoria RAM:** Mínimo 512 MB
- **Espacio en disco:** Mínimo 1.5 GB de espacio libre

### Linux
- **Distribuciones:** Ubuntu, CentOS, Debian, Fedora
- **Memoria RAM:** Mínimo 512 MB
- **Espacio en disco:** Mínimo 1.5 GB de espacio libre

## 🚀 Instalación paso a paso

### Windows

#### 1. Descargar XAMPP
1. Ve a la página oficial: [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Haz clic en **"Download"**
3. Selecciona **"XAMPP for Windows"**
4. Descarga la versión más reciente (recomendado PHP 8.2)

#### 2. Ejecutar el instalador
1. Localiza el archivo descargado (`xampp-windows-x64-8.2.x-x-VS16-installer.exe`)
2. **Clic derecho** → **"Ejecutar como administrador"**
3. Si Windows pregunta sobre el UAC, haz clic en **"Sí"**

#### 3. Asistente de instalación
1. **Pantalla de bienvenida:**
   - Clic en **"Next"**

2. **Seleccionar componentes:**
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
   - ❌ Mercury (opcional)
   - ❌ Tomcat (opcional)
   - ❌ Perl (opcional)
   - Clic en **"Next"**

3. **Carpeta de instalación:**
   - Ruta por defecto: `C:\xampp`
   - Puedes cambiarla si deseas
   - Clic en **"Next"**

4. **Bitnami:**
   - Desmarca la casilla si no quieres información adicional
   - Clic en **"Next"**

5. **Listo para instalar:**
   - Clic en **"Next"**
   - Espera a que termine la instalación (5-10 minutos)

6. **Finalización:**
   - Marca **"Do you want to start the Control Panel now?"**
   - Clic en **"Finish"**

### macOS

#### 1. Descargar XAMPP
1. Ve a [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Selecciona **"XAMPP for OS X"**
3. Descarga el archivo `.dmg`

#### 2. Instalar
1. Abre el archivo `.dmg` descargado
2. Arrastra **XAMPP** a la carpeta **Applications**
3. Abre **Applications** y ejecuta **XAMPP**
4. Introduce tu contraseña de administrador

### Linux (Ubuntu/Debian)

#### 1. Descargar XAMPP
```bash
# Descargar XAMPP (reemplaza con la versión más reciente)
wget https://www.apachefriends.org/xampp-files/8.2.4/xampp-linux-x64-8.2.4-0-installer.run

# Dar permisos de ejecución
chmod +x xampp-linux-x64-8.2.4-0-installer.run
```

#### 2. Instalar
```bash
# Ejecutar instalador (requiere sudo)
sudo ./xampp-linux-x64-8.2.4-0-installer.run
```

## ⚙️ Configuración inicial

### 1. Iniciar servicios
1. Abrir **XAMPP Control Panel**
2. Iniciar servicios necesarios:
   - **Apache:** Clic en **"Start"**
   - **MySQL:** Clic en **"Start"**

### 2. Verificar instalación
1. Abrir navegador web
2. Ir a: `http://localhost` o `http://127.0.0.1`
3. Deberías ver la página de bienvenida de XAMPP

### 3. Acceder a phpMyAdmin
1. En el navegador, ir a: `http://localhost/phpmyadmin`
2. Usuario por defecto: `root`
3. Contraseña: (dejar en blanco)

## 📁 Estructura de carpetas importantes

```
C:\xampp\ (Windows) o /Applications/XAMPP/ (Mac)
│
├── htdocs/          # Carpeta donde van los proyectos web
├── apache/          # Configuración de Apache
├── mysql/           # Base de datos MySQL
├── php/             # Archivos PHP
└── phpMyAdmin/      # Administrador de bases de datos
```

## 🌐 Configurar tu proyecto

### 1. Ubicar tu proyecto
- **Windows:** `C:\xampp\htdocs\tu-proyecto\`
- **Mac:** `/Applications/XAMPP/htdocs/tu-proyecto/`
- **Linux:** `/opt/lampp/htdocs/tu-proyecto/`

### 2. Acceder al proyecto
- URL: `http://localhost/tu-proyecto/`

### Ejemplo para este proyecto:
```bash
# Copiar proyecto a htdocs
cp -r portal_cursos C:\xampp\htdocs\

# Acceder en el navegador
http://localhost/portal_cursos/
```

## 🔧 Configuración adicional (Opcional)

### Cambiar puerto de Apache
Si el puerto 80 está ocupado:

1. Abrir **XAMPP Control Panel**
2. Clic en **"Config"** de Apache
3. Seleccionar **"httpd.conf"**
4. Buscar: `Listen 80`
5. Cambiar a: `Listen 8080` (o cualquier puerto libre)
6. Buscar: `ServerName localhost:80`
7. Cambiar a: `ServerName localhost:8080`
8. Guardar y reiniciar Apache

### Configurar contraseña para MySQL
```sql
-- Acceder a phpMyAdmin
-- Ir a la pestaña "Cuentas de usuario"
-- Editar usuario "root"
-- Establecer contraseña
```

## ❗ Solución de problemas comunes

### Apache no inicia
**Windows:**
- **Puerto ocupado:** Cambiar puerto en configuración
- **Antivirus:** Agregar excepción para XAMPP
- **Skype:** Desactivar uso del puerto 80/443

**Comando para verificar puertos:**
```bash
netstat -an | find "80"
```

### MySQL no inicia
- **Puerto 3306 ocupado:** Cambiar puerto MySQL
- **Servicios conflictivos:** Detener otros servicios MySQL

### Página no carga
1. Verificar que Apache esté iniciado
2. Revisar que el archivo esté en `htdocs`
3. Verificar permisos de archivos
4. Revisar logs de Apache

## 🧑‍💻 Uso

1.  **Registro:** Crea una cuenta como `Estudiante` para explorar e inscribirte en cursos, o como `Instructor` para empezar a crear tu propio contenido.
2.  **Explorar:** Navega por la sección de "Explorar" para descubrir nuevos cursos.
3.  **Crear Cursos (Instructores):** Accede a tu panel de instructor para añadir nuevos cursos, subir videos y materiales.
