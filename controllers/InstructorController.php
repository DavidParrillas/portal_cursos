<?php
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
