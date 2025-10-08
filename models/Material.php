<?php
class Material {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Crear un nuevo material (archivo o video)
     */
    public function crear($datos) {
        $sql = "INSERT INTO materiales (id_curso, id_usuario, titulo, ruta_archivo) 
                VALUES (:id_curso, :id_usuario, :titulo, :ruta_archivo)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_curso' => $datos['id_curso'],
            ':id_usuario' => $datos['id_usuario'],
            ':titulo' => $datos['titulo'],
            ':ruta_archivo' => $datos['ruta_archivo']
        ]);

        return $this->pdo->lastInsertId();
    }

    /**
     * Crear un video de YouTube
     */
    public function crearVideo($datos) {
        return $this->crear($datos);
    }

    /**
     * Obtener todos los materiales de un curso
     */
    public function obtenerPorCurso($idCurso) {
        $sql = "SELECT m.*, u.nombre_completo as subido_por
                FROM materiales m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE m.id_curso = :id_curso
                ORDER BY m.creado_en ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener un material por ID
     */
    public function obtenerPorId($idMaterial) {
        $sql = "SELECT m.*, u.nombre_completo as subido_por
                FROM materiales m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE m.id_material = :id_material";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_material' => $idMaterial]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si un material es un video de YouTube
     */
    public function esVideoYouTube($rutaArchivo) {
        return (
            strpos($rutaArchivo, 'youtube.com') !== false || 
            strpos($rutaArchivo, 'youtu.be') !== false
        );
    }

    /**
     * Extraer ID de video de YouTube desde una URL
     */
    public function extraerIdYouTube($url) {
        $patron = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($patron, $url, $coincidencias)) {
            return $coincidencias[1];
        }
        return null;
    }

    /**
     * Actualizar un material
     */
    public function actualizar($idMaterial, $datos) {
        $sql = "UPDATE materiales SET 
                    titulo = :titulo,
                    ruta_archivo = :ruta_archivo
                WHERE id_material = :id_material";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':ruta_archivo' => $datos['ruta_archivo'],
            ':id_material' => $idMaterial
        ]);
    }

    /**
     * Eliminar un material
     */
    public function eliminar($idMaterial) {
        // Obtener información del material antes de eliminarlo
        $material = $this->obtenerPorId($idMaterial);
        
        if (!$material) {
            return false;
        }

        // Si es un archivo local (no YouTube), eliminar el archivo físico
        if (!$this->esVideoYouTube($material['ruta_archivo'])) {
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $material['ruta_archivo'];
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }
        }

        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM materiales WHERE id_material = :id_material";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_material' => $idMaterial]);
    }

    /**
     * Obtener materiales por tipo (videos o archivos)
     */
    public function obtenerPorTipo($idCurso, $tipo = 'video') {
        $sql = "SELECT m.*, u.nombre_completo as subido_por
                FROM materiales m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE m.id_curso = :id_curso";
        
        if ($tipo === 'video') {
            $sql .= " AND (m.ruta_archivo LIKE '%youtube.com%' OR m.ruta_archivo LIKE '%youtu.be%')";
        } else {
            $sql .= " AND m.ruta_archivo NOT LIKE '%youtube.com%' AND m.ruta_archivo NOT LIKE '%youtu.be%'";
        }
        
        $sql .= " ORDER BY m.creado_en ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar materiales de un curso
     */
    public function contarPorCurso($idCurso) {
        $sql = "SELECT COUNT(*) FROM materiales WHERE id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetchColumn();
    }
}