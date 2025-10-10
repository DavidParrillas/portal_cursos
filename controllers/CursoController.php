<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../models/Material.php';

class CursoController {
    private $cursoModel;
    private $materialModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->cursoModel = new Curso($pdo);
        $this->materialModel = new Material($pdo);
    }

    public function crear() {
        // Verificar que el usuario sea instructor
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'instructor') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear cursos';
            $_SESSION['mensaje_tipo'] = 'danger';
            header('Location: /portal_cursos/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /portal_cursos/views/cursos/crear_curso.php');
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // Validar datos requeridos
            $errores = $this->validarDatos($_POST, $_FILES);
            if (!empty($errores)) {
                throw new Exception(implode('<br>', $errores));
            }

            // Generar slug único
            $slug = $this->generarSlug($_POST['titulo']);

            // Construir la duración
            $duracion = $this->construirDuracion($_POST);

            // CORREGIDO: Tomar solo la primera categoría del array
            $idCategoria = !empty($_POST['categorias']) ? intval($_POST['categorias'][0]) : null;

            // Preparar datos del curso
            $datosCurso = [
                'id_instructor' => $_SESSION['user_id'],
                'titulo' => trim($_POST['titulo']),
                'slug' => $slug,
                'descripcion' => trim($_POST['descripcion']),
                'duracion' => $duracion,
                'modalidad' => $_POST['modalidad'],
                'precio' => floatval($_POST['precio']),
                'fecha_inicio' => !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null,
                'cupos' => intval($_POST['cupos']),
                'estado' => $_POST['estado'] ?? 'PENDIENTE',
                'id_categoria' => $idCategoria
            ];

            // Crear el curso
            $idCurso = $this->cursoModel->crear($datosCurso);

            // Subir imagen de portada
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                $rutaPortada = $this->subirImagen($_FILES['portada'], $idCurso);
                $this->cursoModel->actualizarPortada($idCurso, $rutaPortada);
            }

            // Guardar videos de YouTube
            if (!empty($_POST['videos'])) {
                foreach ($_POST['videos'] as $video) {
                    if (!empty($video['titulo']) && !empty($video['url'])) {
                        $this->materialModel->crearVideo([
                            'id_curso' => $idCurso,
                            'id_usuario' => $_SESSION['user_id'],
                            'titulo' => trim($video['titulo']),
                            'ruta_archivo' => trim($video['url'])
                        ]);
                    }
                }
            }

            // Subir archivos de apoyo
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                $this->subirArchivos($_FILES['archivos'], $idCurso);
            }

            $this->pdo->commit();

            $_SESSION['mensaje'] = 'Curso creado exitosamente';
            $_SESSION['mensaje_tipo'] = 'success';
            header('Location: /portal_cursos/views/courses/cursos.php');
            exit;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['mensaje'] = 'Error al crear el curso: ' . $e->getMessage();
            $_SESSION['mensaje_tipo'] = 'danger';
            header('Location: /portal_cursos/views/courses/crearCurso.php');
            exit;
        }
    }

    private function validarDatos($post, $files) {
        $errores = [];

        if (empty($post['titulo'])) {
            $errores[] = 'El título es obligatorio';
        }

        // Verificar si el título ya existe para este instructor
        if (!empty($post['titulo']) && $this->cursoModel->existeTituloPorInstructor(trim($post['titulo']), $_SESSION['user_id'])) {
            $errores[] = 'Ya tienes un curso con este título. Por favor, elige otro.';
        }

        if (empty($post['descripcion'])) {
            $errores[] = 'La descripción es obligatoria';
        }

        if (empty($post['modalidad'])) {
            $errores[] = 'La modalidad es obligatoria';
        }

        if (!isset($post['precio']) || $post['precio'] < 0) {
            $errores[] = 'El precio debe ser mayor o igual a 0';
        }

        if (!isset($post['cupos']) || $post['cupos'] < 0) {
            $errores[] = 'Los cupos deben ser mayor o igual a 0';
        }

        // CORREGIDO: Validar que haya al menos una categoría
        if (empty($post['categorias']) || !is_array($post['categorias'])) {
            $errores[] = 'Debes seleccionar al menos una categoría';
        }

        if (!isset($files['portada']) || $files['portada']['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'La imagen de portada es obligatoria';
        }

        return $errores;
    }

    private function generarSlug($titulo) {
        $slug = strtolower(trim($titulo));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Asegurar que el slug sea único
        $slugOriginal = $slug;
        $contador = 1;
        while ($this->cursoModel->existeSlug($slug)) {
            $slug = $slugOriginal . '-' . $contador;
            $contador++;
        }
        
        return $slug;
    }

    private function construirDuracion($post) {
        $partes = [];
        
        if (!empty($post['secciones']) && $post['secciones'] > 0) {
            $partes[] = $post['secciones'] . ' sección' . ($post['secciones'] > 1 ? 'es' : '');
        }
        
        if (!empty($post['clases']) && $post['clases'] > 0) {
            $partes[] = $post['clases'] . ' clase' . ($post['clases'] > 1 ? 's' : '');
        }
        
        if (!empty($post['horas']) && $post['horas'] > 0) {
            $partes[] = $post['horas'] . ' hora' . ($post['horas'] > 1 ? 's' : '');
        }
        
        return !empty($partes) ? implode(', ', $partes) : null;
    }

    private function subirImagen($file, $idCurso) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        $tamanoMaximo = 2 * 1024 * 1024; // 2MB

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $extensionesPermitidas)) {
            throw new Exception('Formato de imagen no permitido. Solo JPG, JPEG y PNG');
        }

        if ($file['size'] > $tamanoMaximo) {
            throw new Exception('La imagen es demasiado grande. Máximo 2MB');
        }

        $directorio = __DIR__ . '/../uploads/portadas/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $nombreArchivo = 'portada_' . $idCurso . '_' . time() . '.' . $extension;
        $rutaCompleta = $directorio . $nombreArchivo;

        if (!move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            throw new Exception('Error al subir la imagen de portada');
        }

        return '/portal_cursos/uploads/portadas/' . $nombreArchivo;
    }

    private function subirArchivos($files, $idCurso) {
        $extensionesPermitidas = ['pdf', 'png', 'jpg', 'jpeg'];
        $tamanoMaximo = 5 * 1024 * 1024; // 5MB

        $directorio = __DIR__ . '/../uploads/materiales/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                
                if (!in_array($extension, $extensionesPermitidas)) {
                    continue;
                }

                if ($files['size'][$i] > $tamanoMaximo) {
                    continue;
                }

                $nombreArchivo = 'material_' . $idCurso . '_' . time() . '_' . $i . '.' . $extension;
                $rutaCompleta = $directorio . $nombreArchivo;

                if (move_uploaded_file($files['tmp_name'][$i], $rutaCompleta)) {
                    $this->materialModel->crear([
                        'id_curso' => $idCurso,
                        'id_usuario' => $_SESSION['user_id'],
                        'titulo' => pathinfo($files['name'][$i], PATHINFO_FILENAME),
                        'ruta_archivo' => '/portal_cursos/uploads/materiales/' . $nombreArchivo
                    ]);
                }
            }
        }
    }
}

// Procesar la petición
if (isset($_GET['action'])) {
    $pdo = Database::getInstance();
    $controller = new CursoController($pdo);
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        header('Location: /portal_cursos/index.php');
        exit;
    }
}