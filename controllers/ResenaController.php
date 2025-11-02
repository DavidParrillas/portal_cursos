<?php
require_once 'C:/xampp/htdocs/portal_cursos/aula_virtual/lib/db.php';
require_once __DIR__ . '/../models/Resena.php';

class ResenaController {

    // Mostrar reseñas de un curso
    public function listarPorCurso($id_curso) {
        global $db; // conexión PDO
        $resenaModel = new Resena($db);
        return $resenaModel->obtenerPorCurso($id_curso);
    }

    public function guardarResena() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            if (!isset($_SESSION['usuario_id'])) {
                header("Location: index.php?controller=Usuario&action=login");
                exit;
            }

            $id_estudiante = $_SESSION['usuario_id'];
            $id_curso = $_POST['id_curso'];
            $calificacion = $_POST['calificacion'];
            $comentario = trim($_POST['comentario']);

            global $db;
            $resenaModel = new Resena($db);

            if (!$resenaModel->puedeComentar($id_curso, $id_estudiante)) {
                $_SESSION['mensaje_error'] = "Solo puedes dejar reseñas en cursos en los que estás inscrito.";
                header("Location: index.php?controller=Curso&action=detalle&id=$id_curso");
                exit;
            }

            $resultado = $resenaModel->crearResena($id_curso, $id_estudiante, $calificacion, $comentario);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = "¡Gracias por tu reseña!";
            } else {
                $_SESSION['mensaje_error'] = "Hubo un error al guardar tu reseña.";
            }

            header("Location: index.php?controller=Curso&action=detalle&id=$id_curso");
            exit;
        }
    }
}
