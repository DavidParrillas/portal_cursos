<?php
class Curso {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorId($idCurso) {
        $sql = "SELECT * FROM cursos WHERE id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorInstructor($idInstructor) {
        $sql = "SELECT * FROM cursos WHERE id_instructor = :id_instructor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_instructor' => $idInstructor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimos($limit = 10) {
        $sql = "SELECT c.*, u.nombre_completo as instructor, cat.nombre as categoria,
                       COUNT(i.id_inscripcion) as total_inscritos,
                       AVG(r.calificacion) as promedio_calificacion,
                       COUNT(r.id_resena) as total_resenas
                FROM cursos c
                JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                WHERE c.estado = 'PUBLICADO'
                GROUP BY c.id_curso
                ORDER BY c.creado_en DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCategoria($idCategoria) {
        $sql = "SELECT c.*, u.nombre_completo as instructor, cat.nombre as categoria,
                       COUNT(i.id_inscripcion) as total_inscritos,
                       AVG(r.calificacion) as promedio_calificacion,
                       COUNT(r.id_resena) as total_resenas
                FROM cursos c
                JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                WHERE c.estado = 'PUBLICADO' AND c.id_categoria = :id_categoria
                GROUP BY c.id_curso
                ORDER BY c.creado_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_categoria', $idCategoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodosConDetalles() {
        $sql = "SELECT c.*, u.nombre_completo as instructor, cat.nombre as categoria,
                       COUNT(i.id_inscripcion) as total_inscritos,
                       AVG(r.calificacion) as promedio_calificacion,
                       COUNT(r.id_resena) as total_resenas
                FROM cursos c
                JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                WHERE c.estado = 'PUBLICADO'
                GROUP BY c.id_curso
                ORDER BY c.creado_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crear un nuevo curso
     * CORREGIDO: Incluye id_categoria directamente en el INSERT
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
                    estado,
                    id_categoria
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
                    :estado,
                    :id_categoria
                )";

        $stmt = $this->pdo->prepare($sql);
        $resultado = $stmt->execute([
            ':id_instructor' => $datos['id_instructor'],
            ':titulo' => $datos['titulo'],
            ':slug' => $datos['slug'],
            ':descripcion' => $datos['descripcion'],
            ':duracion' => $datos['duracion'],
            ':modalidad' => $datos['modalidad'],
            ':precio' => $datos['precio'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':cupos' => $datos['cupos'],
            ':estado' => $datos['estado'],
            ':id_categoria' => $datos['id_categoria']
        ]);

        if (!$resultado) {
            throw new Exception("Error al insertar curso: " . implode(", ", $stmt->errorInfo()));
        }

        return $this->pdo->lastInsertId();
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
                    cat.nombre as categoria,
                    cat.id_categoria as id_categoria
                FROM cursos c
                INNER JOIN usuarios u ON c.id_instructor = u.id_usuario
                LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
                LEFT JOIN resenas r ON c.id_curso = r.id_curso
                LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['estado'])) {
            $sql .= " AND c.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (c.titulo LIKE :busqueda_titulo OR u.nombre_completo LIKE :busqueda_instructor)";
            $params[':busqueda_titulo'] = '%' . $filtros['busqueda'] . '%';
            $params[':busqueda_instructor'] = '%' . $filtros['busqueda'] . '%';
        }

        if (!empty($filtros['categoria'])) {
            $sql .= " AND c.id_categoria = :categoria";
            $params[':categoria'] = $filtros['categoria'];
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
     * CORREGIDO: Cambio de imagen_portada a portada
     */
    public function actualizarPortada($idCurso, $rutaPortada) {
        $sql = "UPDATE cursos SET portada = :ruta_portada WHERE id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ruta_portada' => $rutaPortada, 
            ':id_curso' => $idCurso
        ]);
    }

    public function existeSlug($slug) {
        $sql = "SELECT COUNT(*) FROM cursos WHERE slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Eliminar permanentemente un curso y sus relaciones
     */
    public function eliminarPermanentemente($idCurso) {
        try {
            $this->pdo->beginTransaction();

            $queries = [
                "DELETE FROM materiales WHERE id_curso = :id_curso",
                "DELETE FROM resenas WHERE id_curso = :id_curso",
                "DELETE FROM pagos WHERE id_inscripcion IN (SELECT id_inscripcion FROM inscripciones WHERE id_curso = :id_curso)",
                "DELETE FROM inscripciones WHERE id_curso = :id_curso",
                "DELETE FROM cursos WHERE id_curso = :id_curso"
            ];

            foreach ($queries as $sql) {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id_curso' => $idCurso]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>