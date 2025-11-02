<?php
require_once 'BaseModel.php';

class Resena extends BaseModel {

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    // Obtener reseñas por curso
    public function obtenerPorCurso($id_curso) {
        $stmt = $this->pdo->prepare("SELECT * FROM resenas WHERE id_curso = :id_curso");
        $stmt->execute(['id_curso' => $id_curso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear reseña
    public function crearResena($id_curso, $id_estudiante, $calificacion, $comentario) {
        $stmt = $this->pdo->prepare("INSERT INTO resenas (id_curso, id_estudiante, calificacion, comentario) VALUES (:id_curso, :id_estudiante, :calificacion, :comentario)");
        return $stmt->execute([
            'id_curso' => $id_curso,
            'id_estudiante' => $id_estudiante,
            'calificacion' => $calificacion,
            'comentario' => $comentario
        ]);
    }

    // Validar si puede comentar
    public function puedeComentar($id_curso, $id_estudiante) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_curso = :id_curso AND id_estudiante = :id_estudiante");
        $stmt->execute(['id_curso' => $id_curso, 'id_estudiante' => $id_estudiante]);
        return $stmt->fetchColumn() > 0;
    }
}
