<?php
/**
 * AuthController
 *
 * Manages user authentication, including registration,
 * login, and logout.
 */
class AuthController {
    /**
     * @var PDO The database connection instance.
     */
    private $pdo;

    /**
     * Class constructor.
     *
     * @param PDO $pdo A PDO instance for database connection.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Processes the registration request for a new user.
     *
     * Validates form data, checks if the email already exists,
     * hashes the password, and creates the new record in the corresponding table
     * (student or instructor).
     */
    public function register() {
        // Collects form data from POST, with empty default values.
        $nombre_completo = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $rol_codigo = $_POST['rol'] ?? 'estudiante'; // Role code from the form

        // Validates that essential fields are not empty.
        if (empty($nombre_completo) || empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/registro.php', 'Todos los campos son obligatorios.');
        }

        // Validates that the email does not exist in any of the user tables.
        if ($this->userExists($correo)) {
            $this->redirect_with_error('/views/auth/registro.php', 'El correo electrónico ya está registrado.');
        }

        // Hashes the password for secure storage.
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            $this->pdo->beginTransaction();

            // Prepares and executes the insertion query into the 'usuarios' table.
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, contrasena_hash) VALUES (?, ?, ?)");
            $stmt->execute([$nombre_completo, $correo, $contrasena_hash]);
            $id_usuario = $this->pdo->lastInsertId();

            // Gets the ID of the selected role.
            $stmt = $this->pdo->prepare("SELECT id_rol FROM roles WHERE codigo = ?");
            $stmt->execute([$rol_codigo]);
            $id_rol = $stmt->fetchColumn();

            if (!$id_rol) {
                throw new Exception("El rol especificado no es válido.");
            }

            // Inserts the relationship into the 'usuarios_roles' table.
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (?, ?)");
            $stmt->execute([$id_usuario, $id_rol]);

            $this->pdo->commit();

            // Redirects to the login page with a success message.
            header('Location: /portal_cursos/views/auth/login.php?status=success');
            exit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // In case of a database error, redirects with an error message.
            $this->redirect_with_error('/views/auth/registro.php', 'Error al registrar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Processes the login request.
     *
     * Validates credentials, searches for the user in the corresponding tables,
     * and if correct, sets the session variables.
     */
    public function login() {
        // Collects form data from POST.
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        // Validates that fields are not empty.
        if (empty($correo) || empty($contrasena)) {
            $this->redirect_with_error('/views/auth/login.php', 'Correo y contraseña son obligatorios.');
        }

        // Searches for the user by their email address in all role tables.
        $user_data = $this->findUserByEmail($correo);

        // If the user exists and the password is correct.
        if ($user_data && password_verify($contrasena, $user_data['contrasena_hash'])) {
            // ...sets the session variables.
            $_SESSION['user_id'] = $user_data['id_usuario'];
            $_SESSION['user_nombre'] = $user_data['nombre_completo'];
            $_SESSION['user_rol'] = $user_data['rol_codigo']; // Assigns the role code
            // Redirects to the dashboard or main page.
            header('Location: /portal_cursos/public/index.php');
            exit();
        } else {
            // If credentials are incorrect, redirects with an error.
            $this->redirect_with_error('/views/auth/login.php', 'Credenciales incorrectas.');
        }
    }

    /**
     * Logs out the user.
     *
     * Destroys the current session and redirects to the home page.
     */
    public function logout() {
        // Deletes all session variables.
        session_unset();
        // Destroys the session.
        session_destroy();
        // Redirects to the main page.
        header('Location: /portal_cursos/public/index.php');
        exit();
    }

    /**
     * Checks if a user already exists in the database.
     *
     * @param string $correo The email to check.
     * @return bool True if the user exists, false otherwise.
     */
    private function userExists($correo) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch() !== false;
    }

    /**
     * Finds a user by their email address in all role tables.
     *
     * @param string $correo The user's email to search for.
     * @return array|null The user's data if found, or null if not.
     */
    private function findUserByEmail($correo) {
        // Searches for the user in the 'usuarios' table and joins with 'roles' to get the role.
        $stmt = $this->pdo->prepare("\n            SELECT u.*, r.codigo as rol_codigo\n            FROM usuarios u\n            JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario\n            JOIN roles r ON ur.id_rol = r.id_rol\n            WHERE u.correo = ?\n        ");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }

    /**
     * Redirects the user to a specific location with an error message.
     *
     * The error message is stored in the session to be displayed on the destination page.
     *
     * @param string $location The path to redirect to (e.g., '/views/auth/login.php').
     * @param string $message The error message to display.
     */
    private function redirect_with_error($location, $message) {
        $_SESSION['error_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }
}
