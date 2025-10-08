<?php
class Curso {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Crear un nuevo curso
     */
    public function crear($datos) {
        $sql = "INSERT INTO cursos (
                    id_instructor, 
                    titulo, 
                    slug, 
                    descripcion, 
                    duracion, 
                    modalidad, 
                    precio, 
                    fecha_inicio, 
                    cupos, 
                    estado
                ) VALUES (
                    :id_instructor, 
                    :titulo, 
                    :slug, 
                    :descripcion, 
                    :duracion, 
                    :modalidad, 
                    :precio, 
                    :fecha_inicio, 
                    :cupos, 
                    :estado
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_instructor' => $datos['id_instructor'],
            ':titulo' => $datos['titulo'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'],
            ':duracion' => $datos['duracion'],
            ':modalidad' => $datos['modalidad'],
            ':precio' => $datos['precio'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':cupos' => $datos['cupos'],
            ':estado' => $datos['estado']
        ]);
    }

    /**
     * Verificar si existe un título de curso para un instructor específico
     */
    public function existeTituloPorInstructor($titulo, $idInstructor) {
        $sql = "SELECT COUNT(*) FROM cursos WHERE titulo = :titulo AND id_instructor = :id_instructor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':titulo' => $titulo, ':id_instructor' => $idInstructor]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Cambiar el estado de un curso
     */
    public function cambiarEstado($idCurso, $estado) {
        $sql = "UPDATE cursos SET estado = :estado, actualizado_en = CURRENT_TIMESTAMP 
                WHERE id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':estado' => $estado,
            ':id_curso' => $idCurso
        ]);
    }

    /**
     * Eliminar un curso (cambiar estado a ARCHIVADO)
     */
    public function eliminar($idCurso) {
        return $this->cambiarEstado($idCurso, 'ARCHIVADO');
    }

    /**
     * Verificar si un instructor es dueño de un curso
     */
    public function esInstructorDelCurso($idCurso, $idInstructor) {
        $sql = "SELECT COUNT(*) FROM cursos 
                WHERE id_curso = :id_curso AND id_instructor = :id_instructor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_curso' => $idCurso,
            ':id_instructor' => $idInstructor
        ]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtener estadísticas de un curso
     */
    public function obtenerEstadisticas($idCurso) {
        $sql = "SELECT 
                    COUNT(DISTINCT i.id_inscripcion) as total_inscritos,
                    COUNT(DISTINCT r.id_resena) as total_resenas,
                    AVG(r.calificacion) as promedio_calificacion,
                    SUM(CASE WHEN p.estado = 'APROBADO' THEN p.monto ELSE 0 END) as ingresos_totales
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                LEFT JOIN pagos p ON i.id_inscripcion = p.id_inscripcion
                WHERE c.id_curso = :id_curso
                GROUP BY c.id_curso";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener materiales de un curso
     */
    public function obtenerMateriales($idCurso) {
        $sql = "SELECT m.*, u.nombre_completo as subido_por
                FROM materiales m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE m.id_curso = :id_curso
                ORDER BY m.creado_en DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si hay cupos disponibles
     */
    public function hayCuposDisponibles($idCurso) {
        $sql = "SELECT c.cupos, COUNT(i.id_inscripcion) as inscritos
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                WHERE c.id_curso = :id_curso
                GROUP BY c.id_curso, c.cupos";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$resultado) {
            return false;
        }
        
        return $resultado['inscritos'] < $resultado['cupos'];
    }

    /**
     * Obtener todos los cursos para el administrador
     */
    public function obtenerTodosParaAdmin($filtros = []) {
        $sql = "SELECT c.*, 
                    u.nombre_completo as instructor_nombre,
                    u.correo as instructor_correo,
                    COUNT(DISTINCT i.id_inscripcion) as total_inscritos,
                    AVG(r.calificacion) as promedio_calificacion,
                    GROUP_CONCAT(DISTINCT cat.nombre SEPARATOR ', ') as categorias
                FROM cursos c
                INNER JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                LEFT JOIN cursos_categorias cc ON c.id_curso = cc.id_curso
                LEFT JOIN categorias cat ON cc.id_categoria = cat.id_categoria
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['estado'])) {
            $sql .= " AND c.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (c.titulo LIKE :busqueda OR u.nombre_completo LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql .= " GROUP BY c.id_curso ORDER BY c.creado_en DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener estadísticas generales de cursos
     */
    public function obtenerEstadisticasGenerales() {
        $sql = "SELECT 
                    COUNT(*) as total_cursos,
                    SUM(CASE WHEN estado = 'PENDIENTE' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = 'PUBLICADO' THEN 1 ELSE 0 END) as publicados,
                    SUM(CASE WHEN estado = 'ARCHIVADO' THEN 1 ELSE 0 END) as archivados,
                    SUM(CASE WHEN estado = 'BORRADOR' THEN 1 ELSE 0 END) as borradores
                FROM cursos";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Aprobar un curso (cambiar estado a PUBLICADO)
     */
    public function aprobar($idCurso) {
        return $this->cambiarEstado($idCurso, 'PUBLICADO');
    }

    /**
     * Rechazar un curso (cambiar estado a BORRADOR)
     */
    public function rechazar($idCurso) {
        return $this->cambiarEstado($idCurso, 'BORRADOR');
    }

    /**
     * Archivar un curso
     */
    public function archivar($idCurso) {
        return $this->cambiarEstado($idCurso, 'ARCHIVADO');
    }

    /**
     * Obtener categorías de un curso
     */
    public function obtenerCategorias($idCurso) {
        $sql = "SELECT c.id_categoria, c.nombre
                FROM categorias c
                INNER JOIN cursos_categorias cc ON c.id_categoria = cc.id_categoria
                WHERE cc.id_curso = :id_curso";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener estadísticas de un instructor
     */
    public function obtenerEstadisticasInstructor($idInstructor) {
        $sql = "SELECT 
                    COUNT(*) as total_cursos,
                    SUM(CASE WHEN estado = 'PUBLICADO' THEN 1 ELSE 0 END) as cursos_publicados,
                    (SELECT COUNT(DISTINCT i.id_usuario) FROM inscripciones i INNER JOIN cursos c ON i.id_curso = c.id_curso WHERE c.id_instructor = :id_instructor) as total_estudiantes,
                    (SELECT SUM(p.monto) FROM pagos p INNER JOIN inscripciones i ON p.id_inscripcion = i.id_inscripcion INNER JOIN cursos c ON i.id_curso = c.id_curso WHERE c.id_instructor = :id_instructor AND p.estado = 'APROBADO') as ingresos_totales
                FROM cursos
                WHERE id_instructor = :id_instructor";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_instructor' => $idInstructor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener los usuarios inscritos en un curso
     */
    public function obtenerInscritos($idCurso) {
        $sql = "SELECT u.id_usuario, u.nombre_completo, u.correo, i.fecha_inscripcion
                FROM usuarios u
                INNER JOIN inscripciones i ON u.id_usuario = i.id_usuario
                WHERE i.id_curso = :id_curso
                ORDER BY i.fecha_inscripcion DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Eliminar permanentemente un curso y sus relaciones
     */
    public function eliminarPermanentemente($idCurso) {
        try {
            $this->pdo->beginTransaction();

            // Eliminar materiales
            $sql = "DELETE FROM materiales WHERE id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            // Eliminar reseñas
            $sql = "DELETE FROM resenas WHERE id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            // Eliminar pagos relacionados con inscripciones
            $sql = "DELETE p FROM pagos p 
                    INNER JOIN inscripciones i ON p.id_inscripcion = i.id_inscripcion 
                    WHERE i.id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            // Eliminar inscripciones
            $sql = "DELETE FROM inscripciones WHERE id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            // Eliminar relaciones con categorías
            $sql = "DELETE FROM cursos_categorias WHERE id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            // Eliminar el curso
            $sql = "DELETE FROM cursos WHERE id_curso = :id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_curso' => $idCurso]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}