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

# Cómo Clonar un Repositorio de GitHub

Clonar un repositorio significa descargar una copia completa del proyecto con todo su historial de cambios a tu computadora local.

## 📋 Prerrequisitos

### Tener Git instalado
- **Windows:** [Descargar Git](https://git-scm.com/download/win)
- **Mac:** `brew install git` o descargar desde git-scm.com
- **Linux:** `sudo apt install git` (Ubuntu/Debian)

### Verificar instalación
```bash
git --version
```

## 🚀 Métodos para Clonar

### Método 1: Usar HTTPS (Más común)

#### 1. Obtener la URL del repositorio
1. Ve al repositorio en GitHub
2. Clic en el botón **"Code"** (verde)
3. Asegúrate de estar en la pestaña **"HTTPS"**
4. Copia la URL (ejemplo: `https://github.com/usuario/repositorio.git`)

#### 2. Clonar el repositorio
```bash
# Sintaxis básica
git clone https://github.com/usuario/repositorio.git

# Ejemplo específico
git clone https://github.com/DavidParrillas/portal_cursos.git
```

#### 3. Navegar al directorio
```bash
cd portal_cursos
```

### Método 2: Usar SSH (Más seguro)

#### 1. Configurar clave SSH (una sola vez)

**Generar clave SSH:**
```bash
ssh-keygen -t ed25519 -C "tu-email@ejemplo.com"
```

**Agregar clave al ssh-agent:**
```bash
# Iniciar ssh-agent
eval "$(ssh-agent -s)"

# Agregar clave
ssh-add ~/.ssh/id_ed25519
```

**Agregar clave a GitHub:**
1. Copiar clave pública: `cat ~/.ssh/id_ed25519.pub`
2. GitHub → Settings → SSH and GPG keys → New SSH key
3. Pegar la clave y guardar

#### 2. Clonar con SSH
```bash
git clone git@github.com:usuario/repositorio.git

# Ejemplo
git clone git@github.com:DavidParrillas/portal_cursos.git
```

### Método 3: Clonar en carpeta específica

```bash
# Clonar con nombre de carpeta personalizado
git clone https://github.com/usuario/repositorio.git mi-proyecto

# Clonar en carpeta actual
git clone https://github.com/usuario/repositorio.git .
```

### Método 4: Clonar rama específica

```bash
# Clonar solo una rama específica
git clone -b nombre-rama https://github.com/usuario/repositorio.git

# Ejemplo
git clone -b desarrollo https://github.com/DavidParrillas/portal_cursos.git
```

## 📁 Opciones avanzadas de clonación

### Clonar con profundidad limitada
```bash
# Clonar solo el último commit (más rápido)
git clone --depth 1 https://github.com/usuario/repositorio.git

# Clonar últimos 5 commits
git clone --depth 5 https://github.com/usuario/repositorio.git
```

### Clonar sin descargar el historial completo
```bash
# Clonar superficial (útil para proyectos grandes)
git clone --shallow-since="2024-01-01" https://github.com/usuario/repositorio.git
```

### Clonar submódulos
```bash
# Si el repositorio tiene submódulos
git clone --recursive https://github.com/usuario/repositorio.git

# O después de clonar normal
git submodule update --init --recursive
```

## 🔧 Configuración después de clonar

### 1. Configurar información personal
```bash
cd repositorio-clonado

# Configurar nombre y email (si no está configurado globalmente)
git config user.name "Tu Nombre"
git config user.email "tu-email@ejemplo.com"
```

### 2. Verificar configuración
```bash
# Ver información del repositorio
git remote -v

# Ver estado
git status

# Ver ramas disponibles
git branch -a

# Ver historial de commits
git log --oneline
```

### 3. Configurar upstream (si es un fork)
```bash
# Si clonaste un fork y quieres seguir el repositorio original
git remote add upstream https://github.com/usuario-original/repositorio.git

# Verificar remotes
git remote -v
```

## 💻 Clonación según el sistema operativo

### Windows

#### Usando Git Bash
1. Abrir **Git Bash**
2. Navegar a donde quieres clonar:
   ```bash
   cd /c/Users/tu-usuario/Documents
   ```
3. Clonar:
   ```bash
   git clone https://github.com/usuario/repositorio.git
   ```

#### Usando Command Prompt
1. Abrir **CMD** o **PowerShell**
2. Navegar:
   ```cmd
   cd C:\Users\tu-usuario\Documents
   ```
3. Clonar:
   ```cmd
   git clone https://github.com/usuario/repositorio.git
   ```

#### Usando GitHub Desktop
1. Abrir **GitHub Desktop**
2. **File** → **Clone repository**
3. Pegar URL o buscar repositorio
4. Elegir carpeta local
5. Clic en **Clone**

### Mac

#### Usando Terminal
1. Abrir **Terminal**
2. Navegar:
   ```bash
   cd ~/Documents
   ```
3. Clonar:
   ```bash
   git clone https://github.com/usuario/repositorio.git
   ```

#### Usando GitHub Desktop
- Mismo proceso que Windows

### Linux

#### Usando Terminal
1. Abrir terminal
2. Navegar:
   ```bash
   cd ~/Documents
   ```
3. Clonar:
   ```bash
   git clone https://github.com/usuario/repositorio.git
   ```

## 🎯 Casos de uso específicos

#### 1. Clonar el repositorio del proyecto
```bash
# Navegar a donde guardas tus proyectos
cd ~/Documents/Universidad

# Clonar tu repositorio
git clone https://github.com/DavidParrillas/portal_cursos.git

# Entrar al proyecto
cd portal_cursos
```

#### 2. Configurar para XAMPP
```bash
# Copiar a htdocs de XAMPP
cp -r portal_cursos /c/xampp/htdocs/

# O crear enlace simbólico (recomendado)
ln -s "$(pwd)/portal_cursos" /c/xampp/htdocs/
```

### Para colaborar en proyecto existente

#### 1. Fork y clonar
1. **Fork** el repositorio en GitHub
2. Clonar tu fork:
   ```bash
   git clone https://github.com/tu-usuario/repositorio-forkeado.git
   ```
3. Agregar upstream:
   ```bash
   git remote add upstream https://github.com/usuario-original/repositorio.git
   ```

#### 2. Crear rama para tu trabajo
```bash
# Crear y cambiar a nueva rama
git checkout -b mi-feature

# O
git branch mi-feature
git checkout mi-feature
```

## 📊 Verificación después de clonar

### Lista de verificación básica
- [ ] Repositorio descargado completamente
- [ ] Puedes acceder a la carpeta
- [ ] `git status` funciona sin errores
- [ ] Archivos principales están presentes
- [ ] Si es proyecto web, funciona en XAMPP

### Comandos de verificación
```bash
# Verificar que el repositorio está funcionando
git status

# Ver archivos descargados
ls -la

# Ver información del remote
git remote -v

# Ver última información de commits
git log -3 --oneline

# Ver ramas disponibles
git branch -a
```

## ❗ Problemas comunes y soluciones

### Error de autenticación HTTPS
```bash
# Si pide usuario/contraseña constantemente
git config --global credential.helper store

# Usar token en lugar de contraseña
Username: tu-usuario
Password: ghp_tu-token-personal
```

## 🔄 Flujo de trabajo después de clonar

### 1. Trabajo diario
```bash
# Obtener últimos cambios
git pull origin main

# Hacer cambios en archivos
# ...

# Agregar cambios
git add .

# Hacer commit
git commit -m "Descripción de cambios"

# Subir cambios
git push origin main
```

### 2. Mantener fork actualizado
```bash
# Obtener cambios del repositorio original
git fetch upstream

# Cambiar a rama principal
git checkout main

# Fusionar cambios
git merge upstream/main

# Subir cambios actualizados
git push origin main
```

## 🛠️ Herramientas gráficas alternativas

### GitHub Desktop
- **Descarga:** [desktop.github.com](https://desktop.github.com)
- **Ventajas:** Interfaz visual, fácil para principiantes
- **Desventajas:** Funcionalidad limitada

### Git integrado en editores
- **VS Code:** Extensión Git integrada
- **IntelliJ/PHPStorm:** Git integrado
- **Atom:** Git integrado

## 📚 Comandos útiles post-clonación

```bash
# Ver información del repositorio
git remote show origin

# Ver todas las ramas (locales y remotas)
git branch -a

# Cambiar a otra rama
git checkout nombre-rama

# Crear nueva rama desde la actual
git checkout -b nueva-rama

# Ver diferencias
git diff

# Ver historial gráfico
git log --graph --oneline --all

# Ver archivos ignorados
git ls-files --others --ignored --exclude-standard
