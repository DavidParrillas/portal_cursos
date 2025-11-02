<?php
require_once __DIR__ . '/BaseModel.php';

class ReporteModel extends BaseModel {
    
    /**
     * Obtiene el reporte de inscripciones por curso con filtros opcionales
     * @param string|null $fechaInicio Fecha de inicio del filtro
     * @param string|null $fechaFin Fecha de fin del filtro
     * @param int|null $idCategoria ID de la categoría para filtrar
     * @return array Lista de cursos con sus inscripciones
     */
    public function getInscripcionesPorCurso($fechaInicio = null, $fechaFin = null, $idCategoria = null) {
        $sql = "
            SELECT 
                c.id_curso,
                c.titulo,
                cat.nombre as categoria,
                COUNT(i.id_inscripcion) as total_inscripciones,
                c.precio,
                c.fecha_inicio,
                u.nombre_completo as instructor
            FROM cursos c
            LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN cursos_categorias cc ON c.id_curso = cc.id_curso
            LEFT JOIN categorias cat ON cc.id_categoria = cat.id_categoria
            LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND i.fecha_inscrito BETWEEN ? AND ?";
            $params[] = $fechaInicio . ' 00:00:00';
            $params[] = $fechaFin . ' 23:59:59';
        }
        
        if ($idCategoria) {
            $sql .= " AND cat.id_categoria = ?";
            $params[] = $idCategoria;
        }
        
        $sql .= " GROUP BY c.id_curso, c.titulo, cat.nombre, c.precio, c.fecha_inicio, u.nombre_completo";
        $sql .= " ORDER BY total_inscripciones DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene el reporte de ingresos por curso (solo pagos aprobados)
     * @param string|null $fechaInicio Fecha de inicio del filtro
     * @param string|null $fechaFin Fecha de fin del filtro
     * @param int|null $idCategoria ID de la categoría para filtrar
     * @return array Lista de cursos con sus ingresos
     */
    public function getIngresosPorCurso($fechaInicio = null, $fechaFin = null, $idCategoria = null) {
        $sql = "
            SELECT 
                c.id_curso,
                c.titulo,
                cat.nombre as categoria,
                COUNT(DISTINCT i.id_inscripcion) as total_inscripciones,
                COUNT(DISTINCT CASE WHEN p.estado = 'APROBADO' THEN p.id_pago END) as pagos_aprobados,
                COALESCE(SUM(CASE WHEN p.estado = 'APROBADO' THEN p.monto ELSE 0 END), 0) as ingresos_totales,
                c.precio,
                u.nombre_completo as instructor
            FROM cursos c
            LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN pagos p ON i.id_inscripcion = p.id_inscripcion
            LEFT JOIN cursos_categorias cc ON c.id_curso = cc.id_curso
            LEFT JOIN categorias cat ON cc.id_categoria = cat.id_categoria
            LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND p.creado_en BETWEEN ? AND ?";
            $params[] = $fechaInicio . ' 00:00:00';
            $params[] = $fechaFin . ' 23:59:59';
        }
        
        if ($idCategoria) {
            $sql .= " AND cat.id_categoria = ?";
            $params[] = $idCategoria;
        }
        
        $sql .= " GROUP BY c.id_curso, c.titulo, cat.nombre, c.precio, u.nombre_completo";
        $sql .= " ORDER BY ingresos_totales DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene el total de usuarios por rol
     * @return array Lista de roles con su cantidad de usuarios
     */
    public function getTotalUsuariosPorRol() {
        $sql = "
            SELECT 
                r.nombre as rol,
                COUNT(ur.id_usuario) as total_usuarios
            FROM roles r
            LEFT JOIN usuarios_roles ur ON r.id_rol = ur.id_rol
            GROUP BY r.id_rol, r.nombre
            ORDER BY total_usuarios DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene el total de cursos por categoría
     * @return array Lista de categorías con su cantidad de cursos
     */
    public function getTotalCursosPorCategoria() {
        $sql = "
            SELECT 
                cat.nombre as categoria,
                COUNT(DISTINCT cc.id_curso) as total_cursos
            FROM categorias cat
            LEFT JOIN cursos_categorias cc ON cat.id_categoria = cc.id_categoria
            GROUP BY cat.id_categoria, cat.nombre
            ORDER BY total_cursos DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene los cursos más populares (por número de inscripciones)
     * @param int $limite Número máximo de cursos a retornar
     * @return array Lista de cursos más populares
     */
    public function getCursosMasPopulares($limite = 10) {
        $sql = "
            SELECT 
                c.id_curso,
                c.titulo,
                cat.nombre as categoria,
                COUNT(i.id_inscripcion) as total_inscripciones,
                c.precio,
                u.nombre_completo as instructor,
                c.fecha_inicio
            FROM cursos c
            LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN cursos_categorias cc ON c.id_curso = cc.id_curso
            LEFT JOIN categorias cat ON cc.id_categoria = cat.id_categoria
            LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario
            GROUP BY c.id_curso, c.titulo, cat.nombre, c.precio, u.nombre_completo, c.fecha_inicio
            ORDER BY total_inscripciones DESC
            LIMIT ?
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene los ingresos totales del sistema (solo pagos aprobados)
     * @param string|null $fechaInicio Fecha de inicio del filtro
     * @param string|null $fechaFin Fecha de fin del filtro
     * @return float Total de ingresos
     */
    public function getIngresosTotales($fechaInicio = null, $fechaFin = null) {
        $sql = "
            SELECT COALESCE(SUM(monto), 0) as ingresos_totales
            FROM pagos
            WHERE estado = 'APROBADO'
        ";
        
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND creado_en BETWEEN ? AND ?";
            $params[] = $fechaInicio . ' 00:00:00';
            $params[] = $fechaFin . ' 23:59:59';
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['ingresos_totales'];
    }
    
    /**
     * Obtiene todas las categorías disponibles
     * @return array Lista de categorías
     */
    public function getCategorias() {
        $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene estadísticas generales del sistema
     * @param string|null $fechaInicio Fecha de inicio del filtro
     * @param string|null $fechaFin Fecha de fin del filtro
     * @return array Estadísticas generales
     */
    public function getEstadisticasGenerales($fechaInicio = null, $fechaFin = null) {
        $stats = [];
        
        // Total de usuarios
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stats['total_usuarios'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de cursos
        $sql = "SELECT COUNT(*) as total FROM cursos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stats['total_cursos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de inscripciones
        $sql = "SELECT COUNT(*) as total FROM inscripciones";
        if ($fechaInicio && $fechaFin) {
            $sql .= " WHERE fecha_inscrito BETWEEN ? AND ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        $stats['total_inscripciones'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Ingresos totales
        $stats['ingresos_totales'] = $this->getIngresosTotales($fechaInicio, $fechaFin);
        
        return $stats;
    }
}
