<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Resena.php';

class ResenaController {
    private $pdo;
    private $resenaModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->resenaModel = new Resena($pdo);
    }

    // Mostrar reseñas de un curso
    public function listarPorCurso($id_curso) {
        return $this->resenaModel->obtenerPorCurso($id_curso);
    }

    public function guardarResena() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_curso = $_POST['id_curso'] ?? null;

            // Helper para compatibilidad de nombres de sesión
            $id_estudiante = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? null;

            if (!$id_estudiante) {
                $_SESSION['mensaje'] = 'Debes iniciar sesión para dejar una reseña.';
                $_SESSION['mensaje_tipo'] = 'danger';
                header("Location: /portal_cursos/views/auth/login.php");
                exit;
            }

            $calificacion = $_POST['calificacion'];
            $comentario = trim($_POST['comentario']);

            if (!$this->resenaModel->puedeComentar($id_curso, $id_estudiante)) {
                $_SESSION['mensaje'] = "Solo puedes dejar reseñas en cursos en los que estás inscrito.";
                $_SESSION['mensaje_tipo'] = 'warning';
                header("Location: /portal_cursos/views/courses/detalleCurso.php?id=$id_curso");
                exit;
            }

            $resultado = $this->resenaModel->crearResena($id_curso, $id_estudiante, $calificacion, $comentario);

            if ($resultado) {
                $_SESSION['mensaje'] = "¡Gracias por tu reseña!";
                $_SESSION['mensaje_tipo'] = 'success';
            } else {
                $_SESSION['mensaje'] = "Hubo un error al guardar tu reseña.";
                $_SESSION['mensaje_tipo'] = 'danger';
            }

            header("Location: /portal_cursos/views/courses/detalleCurso.php?id=$id_curso");
            exit;
        }
    }
}

// Router simple para este controlador
$action = $_GET['action'] ?? '';
if ($action === 'guardarResena') {
    try {
        $pdo = Database::getInstance();
        $controller = new ResenaController($pdo);
        $controller->guardarResena();
    } catch (Exception $e) {
        // Manejar cualquier excepción durante el proceso
        $_SESSION['mensaje'] = 'Ocurrió un error inesperado: ' . $e->getMessage();
        $_SESSION['mensaje_tipo'] = 'danger';

        // Redirigir de vuelta a la página del curso si es posible
        $id_curso = $_POST['id_curso'] ?? null;
        $redirect_url = $id_curso ? "/portal_cursos/views/courses/detalleCurso.php?id=$id_curso" : "/portal_cursos/index.php";
        header('Location: ' . $redirect_url);
        exit;
    }
}
