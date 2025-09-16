<?php
/**
 * AuthController
 *
 * Gestiona la autenticación de usuarios, incluyendo el registro,
 * inicio de sesión y cierre de sesión.
 */
class AuthController {
    /**
     * @var PDO La instancia de la conexión a la base de datos.
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

    /**
     * Procesa la solicitud de registro de un nuevo usuario.
     *
     * Valida los datos del formulario, verifica si el correo ya existe,
     * hashea la contraseña y crea el nuevo registro en la tabla correspondiente
     * (estudiante o instructor).
     */
    public function register() {
        // Recoge los datos del formulario POST, con valores por defecto vacíos.
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $rol = $_POST['rol'] ?? 'estudiante';

        // Valida que los campos esenciales no estén vacíos.
        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/registro.php', 'Todos los campos son obligatorios.');
        }

        // Valida que el correo no exista en ninguna de las tablas de usuario.
        if ($this->userExists($correo)) {
            $this->redirect_with_error('/views/auth/registro.php', 'El correo electrónico ya está registrado.');
        }

        // Se eliminó el hasheo de contraseña.
        // Determina en qué tabla se insertará el usuario según su rol.
        $table = ($rol === 'instructor') ? 'instructor' : 'estudiante';

        try {
            // Prepara y ejecuta la consulta de inserción.
            $stmt = $this->pdo->prepare("INSERT INTO $table (nombre, correo, contrasena) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $correo, $contrasena]);
            // Redirige a la página de login con un mensaje de éxito.
            header('Location: /portal_cursos/views/auth/login.php?status=success');
            exit();
        } catch (PDOException $e) {
            // En caso de error en la base de datos, redirige con un mensaje de error.
            $this->redirect_with_error('/views/auth/registro.php', 'Error al registrar el usuario.');
        }
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     *
     * Valida las credenciales, busca al usuario en las tablas correspondientes
     * y, si son correctas, establece las variables de sesión.
     */
    public function login() {
        // Recoge los datos del formulario POST.
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        // Valida que los campos no estén vacíos.
        if (empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/login.php', 'Correo y contraseña son obligatorios.');
        }

        // Busca al usuario por su correo electrónico en todas las tablas de roles.
        $user_data = $this->findUserByEmail($correo);

        // Si el usuario existe y la contraseña es correcta (comparación en texto plano).
        // ADVERTENCIA: Se eliminó la verificación segura con password_verify().
        if ($user_data && $contrasena === $user_data['contrasena']) {
            // ...establece las variables de sesión.
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_nombre'] = $user_data['nombre'];
            $_SESSION['user_rol'] = $user_data['rol'];
            // Redirige al dashboard o página principal.
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
     * Verifica si un usuario ya existe en la base de datos.
     *
     * @param string $correo El correo electrónico a verificar.
     * @return bool True si el usuario existe, false en caso contrario.
     */
    private function userExists($correo) {
        return $this->findUserByEmail($correo) !== null;
    }

    /**
     * Busca un usuario por su correo electrónico en todas las tablas de roles.
     *
     * Itera sobre las tablas 'estudiante', 'instructor' y 'administrador' para
     * encontrar una coincidencia.
     *
     * @param string $correo El correo electrónico del usuario a buscar.
     * @return array|null Los datos del usuario si se encuentra, o null si no.
     */
    private function findUserByEmail($correo) {
        // Define las tablas de roles y sus respectivas columnas de ID.
        $tables = ['estudiante' => 'id_estudiante', 'instructor' => 'id_instructor', 'administrador' => 'id_admin'];
        foreach ($tables as $rol => $id_column) {
            // Prepara la consulta para buscar en la tabla actual.
            // Se añade el rol y se renombra la columna de ID para unificar la respuesta.
            $stmt = $this->pdo->prepare("SELECT *, '$rol' as rol, $id_column as id FROM $rol WHERE correo = ?");
            $stmt->execute([$correo]);
            $user = $stmt->fetch();
            // Si se encuentra el usuario, se retorna inmediatamente.
            if ($user) {
                return $user;
            }
        }
        // Si no se encuentra en ninguna tabla, retorna null.
        return null;
    }

    /**
     * Redirige al usuario a una ubicación específica con un mensaje de error.
     *
     * El mensaje de error se almacena en la sesión para ser mostrado en la página de destino.
     *
     * @param string $location La ruta a la que se redirigirá (ej. '/views/auth/login.php').
     * @param string $message El mensaje de error a mostrar.
     */
    private function redirect_with_error($location, $message) {
        $_SESSION['error_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }
}