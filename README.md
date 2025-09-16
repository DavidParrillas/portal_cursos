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
-   `/sql`: Scripts de la base de datos (esquema y datos iniciales).
-   `/docs`: Documentación detallada del proyecto.

## 🚀 Instalación

Para obtener instrucciones detalladas sobre cómo configurar el proyecto en tu entorno local, por favor consulta el archivo `docs/installation.md`.

## 🧑‍💻 Uso

1.  **Registro:** Crea una cuenta como `Estudiante` para explorar e inscribirte en cursos, o como `Instructor` para empezar a crear tu propio contenido.
2.  **Explorar:** Navega por la sección de "Explorar" para descubrir nuevos cursos.
3.  **Crear Cursos (Instructores):** Accede a tu panel de instructor para añadir nuevos cursos, subir videos y materiales.
