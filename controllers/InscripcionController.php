<?php
session_start();
require_once __DIR__ . '/../config/database.php';

class InstructorController {
    private $pdo;
    private string $BASE = '/portal_cursos';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function verificarInstructor() {
        $rol = $_SESSION['user_rol'] ?? '';
        if (!in_array($rol, ['docente', 'instructor'], true)) {
            http_response_code(403);
            echo 'No tienes permisos para esta acción';
            exit;
        }
    }

    public function index() {
        $this->verificarInstructor();
        header('Location: ' . $this->BASE . '/views/inscripcion/confirmacion.php');
        exit;
    }

    public function goConfirmacion() {
    $this->verificarInstructor();

    $curso = [
        'titulo' => $_GET['titulo'] ?? 'Curso de Ejemplo',
        'inicio' => $_GET['inicio'] ?? date('Y-m-d'),
        'precio' => $_GET['precio'] ?? '0.00',
    ];

    require __DIR__ . '/../views/inscripcion/confirmacion.php';
    exit;
}


    public function goValidarCupos() {
        $this->verificarInstructor();
        header('Location: ' . $this->BASE . '/views/inscripcion/validar-cupos.php');
        exit;
    }

    public function goVerificarPrevia() {
        $this->verificarInstructor();
        header('Location: ' . $this->BASE . '/views/inscripcion/verificar-previa.php');
        exit;
    }

    public function selectInscripcionAction() {
        $this->verificarInstructor();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $action = $data['action'] ?? '';

        switch ($action) {
            case 'confirmacion':
                $redirect = $this->BASE . '/views/inscripcion/confirmacion.php';
                break;
            case 'validarCupos':
                $redirect = $this->BASE . '/views/inscripcion/validar-cupos.php';
                break;
            case 'verificarPrevia':
                $redirect = $this->BASE . '/views/inscripcion/verificar-previa.php';
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
                exit;
        }

        echo json_encode(['success' => true, 'redirect' => $redirect]);
        exit;
    }
}

$pdo = Database::getInstance();
$controller = new InstructorController($pdo);

$action = $_GET['action'] ?? 'index';
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    http_response_code(400);
    echo 'Acción no válida';
}
