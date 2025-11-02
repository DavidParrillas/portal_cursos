<?php

require_once __DIR__ . '/../models/InstructorModel.php';

class InstructorController {
    
    /**
     * @var PDO Instancia de la conexión a la base de datos.
     */
    private $pdo;

    /**
     * Constructor de la clase InstructorController.
     *
     * @param PDO $pdo Objeto PDO para manejar la conexión a la base de datos.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Muestra el dashboard del instructor con sus estadísticas y cursos.
     */
    public function dashboard() {
        // Validar que el usuario esté autenticado y sea instructor
        if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'instructor') {
            $this->redirect_with_error('/views/auth/login.php', 'Acceso denegado. Debes ser instructor.');
            return;
        } 

        $instructor_id = $_SESSION['user_id'];
        
        try {
            // Crear instancia del modelo
            $model = new Instructor($this->pdo);
            
            // Obtener datos del instructor
            $estadisticas = $model->getStatsInstructor($instructor_id);
            $cursos = $model->getCursosInstructor($instructor_id);
            
            // Verificar que se obtuvieron los datos correctamente
            if ($estadisticas === false) {
                throw new Exception('No se pudieron obtener las estadísticas del instructor.');
            }
            
            // Asegurar que las estadísticas tengan valores por defecto
            $estadisticas = array_merge([
                'total_cursos' => 0,
                'total_estudiantes' => 0,
                'ingresos_totales' => 0.00
            ], $estadisticas);
            
            // Asegurar que cursos sea un array
            if (!is_array($cursos)) {
                $cursos = [];
            }
            
            // Definir el título de la página
            $pageTitle = "Dashboard Instructor - Curzilla";
            
            // Incluir la vista del dashboard
            include __DIR__ . '/../views/instructor/dashboard_instructor.php';
            
        } catch (Exception $e) {
            // Registrar el error en el log
            error_log("Error en dashboard instructor: " . $e->getMessage());
            
            // Redirigir con mensaje de error
            $this->redirect_with_error(
                '/public/index.php', 
                'Error al cargar el dashboard. Por favor, intenta de nuevo.'
            );
        }
    }

    /**
     * Redirige al usuario a una ubicación específica con un mensaje de error.
     *
     * @param string $location Ruta a la que se redirigirá.
     * @param string $message Mensaje de error a mostrar.
     */
    private function redirect_with_error($location, $message) {
        $_SESSION['error_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }

    /**
     * Redirige al usuario con un mensaje de éxito.
     *
     * @param string $location Ruta a la que se redirigirá.
     * @param string $message Mensaje de éxito a mostrar.
     */
    private function redirect_with_success($location, $message) {
        $_SESSION['success_message'] = $message;
        header('Location: /portal_cursos' . $location);
        exit();
    }
}
?>
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../models/Material.php';

class InstructorController {
    private $cursoModel;
    private $materialModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->cursoModel = new Curso($pdo);
        $this->materialModel = new Material($pdo);
    }

    private function verificarInstructor() {
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'instructor') {
            http_response_code(403);
            echo json_encode(['success' => false, 'mensaje' => 'Acceso denegado']);
            exit;
        }
    }

    public function obtenerResumen() {
        $this->verificarInstructor();
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

    public function obtenerDetallesCurso() {
        $this->verificarInstructor();
        $idInstructor = $_SESSION['user_id'];
        $idCurso = $_GET['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode(['success' => false, 'mensaje' => 'ID de curso no proporcionado']);
            exit;
        }

        try {
            // Verificar que el instructor sea dueño del curso
            if (!$this->cursoModel->esInstructorDelCurso($idCurso, $idInstructor)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'mensaje' => 'No tienes permiso para ver este curso']);
                exit;
            }

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
