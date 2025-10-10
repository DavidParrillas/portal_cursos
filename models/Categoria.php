<?php
class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtener todas las categorías
     */
    public function obtenerTodas() {
        $sql = "SELECT * FROM categorias ORDER BY nombre ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener una categoría por ID
     */
    public function obtenerPorId($idCategoria) {
        $sql = "SELECT * FROM categorias WHERE id_categoria = :id_categoria";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_categoria' => $idCategoria]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener una categoría por slug
     */
    public function obtenerPorSlug($slug) {
        $sql = "SELECT * FROM categorias WHERE slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crear una nueva categoría
     */
    public function crear($datos) {
        $sql = "INSERT INTO categorias (nombre, slug) VALUES (:nombre, :slug)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug']
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Actualizar una categoría
     */
    public function actualizar($idCategoria, $datos) {
        $sql = "UPDATE categorias SET nombre = :nombre, slug = :slug 
                WHERE id_categoria = :id_categoria";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':slug' => $datos['slug'],
            ':id_categoria' => $idCategoria
        ]);
    }

    /**
     * Eliminar una categoría
     */
    public function eliminar($idCategoria) {
        // Establecer id_categoria a NULL en los cursos que tengan esta categoría
        $sql = "UPDATE cursos SET id_categoria = NULL WHERE id_categoria = :id_categoria";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_categoria' => $idCategoria]);

        // Luego eliminar la categoría
        $sql = "DELETE FROM categorias WHERE id_categoria = :id_categoria";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_categoria' => $idCategoria]);
    }

    /**
     * Obtener la categoría de un curso específico
     */
    public function obtenerPorCurso($idCurso) {
        $sql = "SELECT c.* FROM categorias c
                INNER JOIN cursos cu ON c.id_categoria = cu.id_categoria
                WHERE cu.id_curso = :id_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_curso' => $idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Contar cursos por categoría
     */
    public function contarCursos($idCategoria) {
        $sql = "SELECT COUNT(*) FROM cursos WHERE id_categoria = :id_categoria";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_categoria' => $idCategoria]);
        return $stmt->fetchColumn();
    }

    /**
     * Verificar si existe un slug
     */
    public function existeSlug($slug, $idCategoriaExcluir = null) {
        $sql = "SELECT COUNT(*) FROM categorias WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($idCategoriaExcluir !== null) {
            $sql .= " AND id_categoria != :id_categoria";
            $params[':id_categoria'] = $idCategoriaExcluir;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}