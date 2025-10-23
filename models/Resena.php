<?php
require_once 'BaseModel.php';

class Resena extends BaseModel {
    protected $id_resena;
    protected $id_curso;
    protected $id_estudiante;
    protected $calificacion; // de 1 a 5 estrellas
    protected $comentario;
    protected $creado_en;

    
    public function obtenerPorCurso($id_curso) {
        $query = "SELECT r.*, u.nombre AS estudiante 
                  FROM resenas r
                  INNER JOIN usuarios u ON r.id_estudiante = u.id_usuario
                  WHERE r.id_curso = :id_curso
                  ORDER BY r.creado_en DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function puedeComentar($id_curso, $id_estudiante) {
        $query = "SELECT COUNT(*) FROM inscripciones 
                  WHERE id_curso = :id_curso AND id_estudiante = :id_estudiante";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':id_estudiante', $id_estudiante);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    
    public function crearResena($id_curso, $id_estudiante, $calificacion, $comentario) {
        $query = "INSERT INTO resenas (id_curso, id_estudiante, calificacion, comentario, creado_en)
                  VALUES (:id_curso, :id_estudiante, :calificacion, :comentario, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':id_estudiante', $id_estudiante);
        $stmt->bindParam(':calificacion', $calificacion);
        $stmt->bindParam(':comentario', $comentario);
        return $stmt->execute();
    }
}
