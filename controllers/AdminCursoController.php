<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Curso.php';

class AdminCursoController {
    private $cursoModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->cursoModel = new Curso($pdo);
    }

    /**
     * Verificar que el usuario sea administrador
     */
    private function verificarAdmin() {
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'mensaje' => 'No tienes permisos para realizar esta acción'
            ]);
            exit;
        }
    }

    /**
     * Aprobar un curso
     */
    public function aprobar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {
            $curso = $this->cursoModel->obtenerPorId($idCurso);
            
            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Curso no encontrado'
                ]);
                exit;
            }

            if ($curso['estado'] !== 'PENDIENTE') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Solo se pueden aprobar cursos en estado PENDIENTE'
                ]);
                exit;
            }

            $this->cursoModel->aprobar($idCurso);

            // Aquí podrías enviar un email al instructor notificando la aprobación
            // $this->enviarNotificacionAprobacion($curso);

            echo json_encode([
                'success' => true,
                'mensaje' => 'Curso aprobado exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al aprobar el curso: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rechazar un curso
     */
    public function rechazar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;
        $motivo = $data['motivo'] ?? '';

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {
            $curso = $this->cursoModel->obtenerPorId($idCurso);
            
            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Curso no encontrado'
                ]);
                exit;
            }

            $this->cursoModel->rechazar($idCurso);

            // Aquí podrías enviar un email al instructor notificando el rechazo
            // $this->enviarNotificacionRechazo($curso, $motivo);

            echo json_encode([
                'success' => true,
                'mensaje' => 'Curso rechazado. El instructor podrá revisarlo y volver a enviarlo'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al rechazar el curso: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Archivar un curso
     */
    public function archivar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {
            $this->cursoModel->archivar($idCurso);

            echo json_encode([
                'success' => true,
                'mensaje' => 'Curso archivado exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al archivar el curso: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Publicar un curso archivado
     */
    public function publicar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {
            $this->cursoModel->cambiarEstado($idCurso, 'PUBLICADO');

            echo json_encode([
                'success' => true,
                'mensaje' => 'Curso publicado exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al publicar el curso: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar permanentemente un curso
     */
    public function eliminar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $idCurso = $data['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {
            $curso = $this->cursoModel->obtenerPorId($idCurso);
            
            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Curso no encontrado'
                ]);
                exit;
            }

            // Verificar si hay inscripciones
            $estadisticas = $this->cursoModel->obtenerEstadisticas($idCurso);
            if ($estadisticas['total_inscritos'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se puede eliminar un curso con estudiantes inscritos. Considera archivarlo en su lugar.'
                ]);
                exit;
            }

            // Eliminar archivos físicos asociados
            $this->eliminarArchivosAsociados($curso);

            // Eliminar el curso y sus relaciones
            $this->cursoModel->eliminarPermanentemente($idCurso);

            echo json_encode([
                'success' => true,
                'mensaje' => 'Curso eliminado permanentemente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al eliminar el curso: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar archivos físicos asociados al curso
     */
    private function eliminarArchivosAsociados($curso) {
        // Eliminar portada si existe
        if (!empty($curso['portada'])) {
            $rutaPortada = $_SERVER['DOCUMENT_ROOT'] . $curso['portada'];
            if (file_exists($rutaPortada)) {
                unlink($rutaPortada);
            }
        }

        // Eliminar materiales físicos
        $materiales = $this->cursoModel->obtenerMateriales($curso['id_curso']);
        foreach ($materiales as $material) {
            // Solo eliminar si no es un enlace de YouTube
            if (strpos($material['ruta_archivo'], 'youtube') === false && 
                strpos($material['ruta_archivo'], 'youtu.be') === false) {
                $rutaMaterial = $_SERVER['DOCUMENT_ROOT'] . $material['ruta_archivo'];
                if (file_exists($rutaMaterial)) {
                    unlink($rutaMaterial);
                }
            }
        }
    }

    /**
     * Obtener detalles de un curso
     */
    public function obtenerDetalles() {
        $this->verificarAdmin();

        $idCurso = $_GET['id_curso'] ?? null;

        if (!$idCurso) {
            http_response_code(400);
            echo json_encode([  
                'success' => false,
                'mensaje' => 'ID de curso no proporcionado'
            ]);
            exit;
        }

        try {  
            $curso = $this->cursoModel->obtenerPorId($idCurso);
            
            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Curso no encontrado'
                ]);
                exit;
            }

            // Obtener materiales
            $materiales = $this->cursoModel->obtenerMateriales($idCurso);

            // Obtener categorías
            $categorias = $this->cursoModel->obtenerCategorias($idCurso);

            // Obtener estadísticas
            $estadisticas = $this->cursoModel->obtenerEstadisticas($idCurso);

            echo json_encode([
                'success' => true,
                'curso' => $curso,
                'materiales' => $materiales,
                'categorias' => $categorias,
                'estadisticas' => $estadisticas
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al obtener los detalles del curso: ' . $e->getMessage()
            ]);
        }
    }
}