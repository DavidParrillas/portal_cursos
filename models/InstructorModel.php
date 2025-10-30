<?php
/**
 * Modelo para manejar la lógica de negocio relacionada con instructores.
 * Extiende de BaseModel para heredar la conexión PDO.
 */

// Ya que BaseModel.php ya tiene una conexión PDO, se heredará desde ese modelo. 
class Instructor extends BaseModel
{
    /**
     * Obtiene estadísticas generales de un instructor específico.
     * 
     * @param int $instructor_id ID del instructor
     * @return array Arreglo asociativo con las estadísticas del instructor
     */
    public function getStatsInstructor($instructor_id) {
        $query = $this->pdo->prepare("
            SELECT 
                COUNT(DISTINCT c.id_curso) as total_cursos,
                COUNT(DISTINCT i.id_inscripcion) as total_estudiantes,
                COALESCE(SUM(CASE 
                    WHEN p.estado = 'APROBADO' THEN p.monto 
                    ELSE 0 
                END), 0) as ingresos_totales
            FROM cursos c
            LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN pagos p ON i.id_inscripcion = p.id_inscripcion
            WHERE c.id_instructor = ?
        ");
        
        $query->execute([$instructor_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los cursos de un instructor con información detallada.
     * 
     * @param int $instructor_id ID del instructor
     * @return array Arreglo de cursos con sus detalles
     */
    public function getCursosInstructor($instructor_id) {
        $query = $this->pdo->prepare("
            SELECT 
                c.id_curso as id,
                c.titulo,
                c.descripcion,
                c.duracion,
                c.precio,
                c.estado,
                c.modalidad,
                c.cupos,
                c.fecha_inicio,
                COUNT(DISTINCT i.id_inscripcion) as total_inscritos,
                COALESCE(AVG(r.calificacion), 0) as calificacion_promedio,
                u.nombre_completo as nombre_instructor,
                GROUP_CONCAT(DISTINCT cat.nombre SEPARATOR ', ') as categorias
            FROM cursos c
            LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN resenas r ON c.id_curso = r.id_curso
            LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario
            LEFT JOIN cursos_categorias cc ON c.id_curso = cc.id_curso
            LEFT JOIN categorias cat ON cc.id_categoria = cat.id_categoria
            WHERE c.id_instructor = ?
            GROUP BY c.id_curso
            ORDER BY c.creado_en DESC
        ");
        
        $query->execute([$instructor_id]);
        $cursos = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Agregar campo 'publicado' basado en el estado
        foreach ($cursos as &$curso) {
            $curso['publicado'] = ($curso['estado'] === 'PUBLICADO') ? 1 : 0;
        }
        
        return $cursos;
    }
}
?>