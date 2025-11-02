<?php

class AutenticacionController {
    /**
     * @var PDO La instancia de conexión a la base de datos.
     */
    private $pdo;

    /**
     * Constructor de la clase.
     *
     * @param PDO $pdo Una instancia de PDO para la conexión a la base de datos.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function register() {
        // Recoge los datos del formulario desde POST, con valores por defecto vacíos.
        $nombre_completo = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $rol_codigo = 'estudiante'; // Por defecto, todos los registros son de estudiantes.

        // Valida que los campos esenciales no estén vacíos.
        if (empty($nombre_completo) || empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/registro.php', 'Todos los campos son obligatorios.');
        }

        // Valida que el correo no exista en ninguna de las tablas de usuario.
        if ($this->userExists($correo)) {
            $this->redirect_with_error('/views/auth/registro.php', 'El correo electrónico ya está registrado.');
        }

        // Hashea la contraseña para un almacenamiento seguro.
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            $this->pdo->beginTransaction();

            // Prepara y ejecuta la consulta de inserción en la tabla 'usuarios'.
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, contrasena_hash) VALUES (?, ?, ?)");
            $stmt->execute([$nombre_completo, $correo, $contrasena_hash]);
            $id_usuario = $this->pdo->lastInsertId();

            // Obtiene el ID del rol seleccionado.
            $stmt = $this->pdo->prepare("SELECT id_rol FROM roles WHERE codigo = ?");
            $stmt->execute([$rol_codigo]);
            $id_rol = $stmt->fetchColumn();

            if (!$id_rol) {
                throw new Exception("El rol especificado no es válido."); // Este mensaje ya está en español.
            }

            // Inserta la relación en la tabla 'usuarios_roles'.
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (?, ?)");
            $stmt->execute([$id_usuario, $id_rol]);

            $this->pdo->commit();

            // Redirige a la página de login con un mensaje de éxito.
            header('Location: /portal_cursos/views/auth/login.php?status=success');
            exit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // En caso de un error de base de datos, redirige con un mensaje de error.
            $this->redirect_with_error('/views/auth/registro.php', 'Error al registrar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     *
     * Valida las credenciales, busca al usuario en las tablas correspondientes,
     * y si son correctas, establece las variables de sesión.
     */
    public function login() {
        // Recoge los datos del formulario desde POST.
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        // Valida que los campos no estén vacíos.
        if (empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/login.php', 'Correo y contraseña son obligatorios.');
        }

        // Busca al usuario por su dirección de correo en todas las tablas de roles.
        $user_data = $this->findUserByEmail($correo);

        // Si el usuario existe y la contraseña es correcta.
        if ($user_data && password_verify($contrasena, $user_data['contrasena_hash'])) {
            // ...establece las variables de sesión.
            $_SESSION['user_id'] = $user_data['id_usuario'];
            $_SESSION['user_nombre'] = $user_data['nombre_completo'];
            $_SESSION['user_rol'] = $user_data['rol_codigo']; // Asigna el código del rol
            // Redirige al dashboard o a la página principal.
            header('Location: /portal_cursos/public/index.php');
            exit();
        } else {
            // Si las credenciales son incorrectas, redirige con un error.
            $this->redirect_with_error('/views/auth/login.php', 'Credenciales incorrectas.');
        }
    }

    /**
     * Cierra la sesión del usuario.
     *
     * Destruye la sesión actual y redirige a la página de inicio.
     */
    public function logout() {
        // Elimina todas las variables de sesión.
        session_unset();
        // Destruye la sesión.
        session_destroy();
        // Redirige a la página principal.
        header('Location: /portal_cursos/public/index.php');
        exit();
    }

    /**
     * Comprueba si un usuario ya existe en la base de datos.
     *
     * @param string $correo El correo a verificar.
     * @return bool True si el usuario existe, false en caso contrario.
     */
    private function userExists($correo) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch() !== false;
    }

    /**
     * Busca un usuario por su dirección de correo en todas las tablas de roles.
     *
     * @param string $correo El correo del usuario a buscar.
     * @return array|null Los datos del usuario si se encuentra, o null si no.
     */
    private function findUserByEmail($correo) {
        // Busca al usuario en la tabla 'usuarios' y une con 'roles' para obtener el rol.
        $stmt = $this->pdo->prepare("\n            SELECT u.*, r.codigo as rol_codigo\n            FROM usuarios u\n            JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario\n            JOIN roles r ON ur.id_rol = r.id_rol\n            WHERE u.correo = ?\n        ");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }

    /**
     * Redirige al usuario a una ubicación específica con un mensaje de error.
     *
     * El mensaje de error se almacena en la sesión para ser mostrado en la página de destino.
     *
     * @param string $location La ruta a la que redirigir (ej. '/views/auth/login.php').
     * @param string $message El mensaje de error a mostrar.
     */
    private function redirect_with_error($location, $message) {
        $_SESSION['error_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }
}
