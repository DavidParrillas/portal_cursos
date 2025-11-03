<?php
// Controlador único para funcionalidades de instructor (dashboard + endpoints JSON).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/InstructorModel.php';
require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../models/Material.php';

class InstructorController {
    private $pdo;
    private $instructorModel;
    private $cursoModel;
    private $materialModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->instructorModel = new Instructor($pdo);
        $this->cursoModel = new Curso($pdo);
        $this->materialModel = new Material($pdo);
    }

    /**
     * Verifica que el usuario esté autenticado. No obliga a ser instructor.
     */
    private function verificarAutenticacion() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'mensaje' => 'No autenticado']);
            exit;
        }
    }

    /**
     * Dashboard web del instructor (vista HTML).
     */
/**
 * Dashboard web del instructor (vista HTML).
 */
public function dashboard() {
    error_log("=== DASHBOARD INSTRUCTOR INICIADO ===");
        
        // Verificar autenticación mínima (solo user_id). No forzar rol aquí para facilitar depuración.
        if (!isset($_SESSION['user_id'])) {
            error_log("Acceso denegado - No hay sesión de usuario");
            $this->redirect_with_error('/views/auth/login.php', 'Debes iniciar sesión.');
            return;
        }

        $instructor_id = $_SESSION['user_id'];
        error_log("Instructor ID (session): $instructor_id");
        error_log("Session data: " . print_r($_SESSION, true));
        error_log("Session ID: " . session_id());

    try {
            // Comprobar directamente en BD cuántos cursos existen para este id (log)
            try {
                $check = $this->pdo->prepare("SELECT COUNT(*) as total FROM cursos WHERE id_instructor = ?");
                $check->execute([$instructor_id]);
                $info = $check->fetch(PDO::FETCH_ASSOC);
                error_log("Check directo en BD - cursos para instructor $instructor_id: " . print_r($info, true));
            } catch (Exception $e) {
                error_log("Error al verificar cursos directos: " . $e->getMessage());
            }

            // Obtener estadísticas
            $estadisticas = $this->instructorModel->getStatsInstructor($instructor_id);
            error_log("Estadísticas: " . print_r($estadisticas, true));

            // Obtener cursos
            $cursos = $this->instructorModel->getCursosInstructor($instructor_id);
            error_log("Cursos obtenidos (modelo): " . count($cursos));

        // Valores por defecto para estadísticas
        $estadisticas = array_merge([
            'total_cursos' => 0,
            'total_estudiantes' => 0,
            'ingresos_totales' => 0.00
        ], (array)$estadisticas);

        // Asegurar que $cursos sea array
        if (!is_array($cursos)) {
            $cursos = [];
        }

        error_log("Cargando vista con " . count($cursos) . " cursos");

        $pageTitle = "Dashboard Instructor - Curzilla";
        include __DIR__ . '/../views/instructor/dashboard_instructor.php';
        
    } catch (Exception $e) {
        error_log("ERROR en dashboard: " . $e->getMessage());
        error_log($e->getTraceAsString());
        $this->redirect_with_error('/public/index.php', 'Error al cargar el dashboard. Por favor, intenta de nuevo.');
    }
}

    /**
     * Muestra la página para editar un curso existente.
     */
    public function editarCurso() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $this->redirect_with_error('/views/auth/login.php', 'Acceso no válido.');
            return;
        }
        
        // La lógica para cargar los datos del curso ya está en la vista.
        // Simplemente incluimos el archivo de la vista.
        $pageTitle = "Editar Curso - Curzilla";
        include __DIR__ . '/../views/courses/editarCurso.php';
    }

    /**
     * Endpoint JSON para eliminar (archivar) un curso.
     */
    public function eliminarCurso() {
        header('Content-Type: application/json');
        $this->verificarAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;
        $idInstructor = $_SESSION['user_id'];

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'ID de curso no proporcionado.']);
            exit;
        }

        try {
            // Verificar que el instructor es el dueño del curso
            if (!$this->cursoModel->esInstructorDelCurso($idCurso, $idInstructor)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'mensaje' => 'No tienes permiso para eliminar este curso.']);
                exit;
            }

            // Cambiar el estado a 'ARCHIVADO' en lugar de eliminarlo permanentemente
            if ($this->cursoModel->archivar($idCurso)) {
                echo json_encode(['success' => true, 'mensaje' => 'Curso archivado exitosamente.']);
            } else {
                throw new Exception('No se pudo archivar el curso.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
    }

    private function redirect_with_error($location, $message) {
        $_SESSION['error_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }

    private function redirect_with_success($location, $message) {
        $_SESSION['success_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }

    /**
     * Endpoint JSON: obtener resumen de cursos/estadísticas del instructor.
     * Requiere autenticación (cualquier rol).
     */
    public function obtenerResumen() {
        $this->verificarAutenticacion();
        $idInstructor = $_SESSION['user_id'];

        try {
            $cursos = $this->cursoModel->obtenerPorInstructor($idInstructor);
            $estadisticas = $this->cursoModel->obtenerEstadisticasInstructor($idInstructor);

            echo json_encode([
                'success' => true,
                'cursos' => $cursos,
                'estadisticas' => $estadisticas
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'mensaje' => 'Error al obtener el resumen: ' . $e->getMessage()]);
        }
    }

    /**
     * Endpoint JSON: obtener detalles de un curso.
     * Requiere autenticación (cualquier rol).
     */
    public function obtenerDetallesCurso() {
        $this->verificarAutenticacion();
        $idCurso = $_GET['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'ID de curso no proporcionado']);
            exit;
        }

        try {
            $curso = $this->cursoModel->obtenerPorId($idCurso);
            $materiales = $this->materialModel->obtenerPorCurso($idCurso);
            $estadisticas = $this->cursoModel->obtenerEstadisticas($idCurso);
            $inscritos = $this->cursoModel->obtenerInscritos($idCurso);

            echo json_encode([
                'success' => true,
                'curso' => $curso,
                'materiales' => $materiales,
                'estadisticas' => $estadisticas,
                'inscritos' => $inscritos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'mensaje' => 'Error al obtener los detalles: ' . $e->getMessage()]);
        }
    }
}
