<?php
/**
 * Modelo para manejar la lógica de negocio relacionada con instructores.
 * Extiende de BaseModel para heredar la conexión PDO.
 */

require_once __DIR__ . '/BaseModel.php';

class Instructor extends BaseModel {
    
    /**
     * Obtiene estadísticas generales de un instructor específico.
     * 
     * @param int $instructor_id ID del instructor
     * @return array Arreglo asociativo con las estadísticas del instructor
     */
    public function getStatsInstructor($instructor_id) {
        try {
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
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            error_log("STATS INSTRUCTOR $instructor_id: " . print_r($result, true));
            
            return $result ?: [
                'total_cursos' => 0,
                'total_estudiantes' => 0,
                'ingresos_totales' => 0
            ];
            
        } catch (PDOException $e) {
            error_log("ERROR en getStatsInstructor: " . $e->getMessage());
            return [
                'total_cursos' => 0,
                'total_estudiantes' => 0,
                'ingresos_totales' => 0
            ];
        }
    }

    /**
     * Obtiene todos los cursos de un instructor con información detallada.
     * 
     * @param int $instructor_id ID del instructor
     * @return array Arreglo de cursos con sus detalles
     */
    public function getCursosInstructor($instructor_id) {
        try {
            error_log("getCursosInstructor - ID: $instructor_id");
            
            $query = $this->pdo->prepare("
                SELECT 
                    c.id_curso,
                    c.titulo,
                    c.descripcion,
                    c.duracion,
                    c.precio,
                    c.estado,
                    c.modalidad,
                    c.cupos,
                    c.portada,
                    c.fecha_inicio,
                    COUNT(DISTINCT i.id_inscripcion) as total_inscritos,
                    COALESCE(AVG(r.calificacion), 0) as calificacion_promedio,
                    u.nombre_completo as nombre_instructor,
                    cat.nombre as categoria
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
                WHERE c.id_instructor = ?
                GROUP BY c.id_curso
                ORDER BY c.creado_en DESC
            ");
            
            $query->execute([$instructor_id]);
            $cursos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Cursos encontrados: " . count($cursos));
            
            // Agregar campo 'publicado' basado en el estado
            foreach ($cursos as &$curso) {
                $curso['publicado'] = ($curso['estado'] === 'PUBLICADO') ? 1 : 0;
            }
            
            return $cursos;
            
        } catch (PDOException $e) {
            error_log("ERROR en getCursosInstructor: " . $e->getMessage());
            return [];
        }
    }
}
?>