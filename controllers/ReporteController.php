<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario sea administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No autorizado. Debe ser administrador para acceder a esta sección.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ReporteModel.php';

header('Content-Type: application/json');

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
    $pdo = Database::getInstance();
    $reporteModel = new ReporteModel($pdo);
    
    switch ($accion) {
        case 'inscripciones':
            obtenerInscripciones($reporteModel);
            break;
            
        case 'ingresos':
            obtenerIngresos($reporteModel);
            break;
            
        case 'estadisticas':
            obtenerEstadisticas($reporteModel);
            break;
            
        case 'usuarios_rol':
            obtenerUsuariosPorRol($reporteModel);
            break;
            
        case 'cursos_categoria':
            obtenerCursosPorCategoria($reporteModel);
            break;
            
        case 'cursos_populares':
            obtenerCursosPopulares($reporteModel);
            break;
            
        case 'categorias':
            obtenerCategorias($reporteModel);
            break;
            
        case 'exportar_pdf':
            exportarPDF($reporteModel);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

function obtenerInscripciones($reporteModel) {
    $fechaInicio = $_GET['fecha_inicio'] ?? null;
    $fechaFin = $_GET['fecha_fin'] ?? null;
    $idCategoria = $_GET['id_categoria'] ?? null;
    
    $datos = $reporteModel->getInscripcionesPorCurso($fechaInicio, $fechaFin, $idCategoria);
    
    echo json_encode([
        'success' => true,
        'datos' => $datos
    ]);
}

function obtenerIngresos($reporteModel) {
    $fechaInicio = $_GET['fecha_inicio'] ?? null;
    $fechaFin = $_GET['fecha_fin'] ?? null;
    $idCategoria = $_GET['id_categoria'] ?? null;
    
    $datos = $reporteModel->getIngresosPorCurso($fechaInicio, $fechaFin, $idCategoria);
    
    echo json_encode([
        'success' => true,
        'datos' => $datos
    ]);
}

function obtenerEstadisticas($reporteModel) {
    $fechaInicio = $_GET['fecha_inicio'] ?? null;
    $fechaFin = $_GET['fecha_fin'] ?? null;
    
    $estadisticas = $reporteModel->getEstadisticasGenerales($fechaInicio, $fechaFin);
    
    echo json_encode([
        'success' => true,
        'estadisticas' => $estadisticas
    ]);
}

function obtenerUsuariosPorRol($reporteModel) {
    $datos = $reporteModel->getTotalUsuariosPorRol();
    
    echo json_encode([
        'success' => true,
        'datos' => $datos
    ]);
}

function obtenerCursosPorCategoria($reporteModel) {
    $datos = $reporteModel->getTotalCursosPorCategoria();
    
    echo json_encode([
        'success' => true,
        'datos' => $datos
    ]);
}

function obtenerCursosPopulares($reporteModel) {
    $limite = $_GET['limite'] ?? 10;
    $datos = $reporteModel->getCursosMasPopulares($limite);
    
    echo json_encode([
        'success' => true,
        'datos' => $datos
    ]);
}

function obtenerCategorias($reporteModel) {
    $datos = $reporteModel->getCategorias();
    
    echo json_encode([
        'success' => true,
        'categorias' => $datos
    ]);
}

function exportarPDF($reporteModel) {
    $tipoReporte = $_GET['tipo'] ?? 'inscripciones';
    $fechaInicio = $_GET['fecha_inicio'] ?? null;
    $fechaFin = $_GET['fecha_fin'] ?? null;
    $idCategoria = $_GET['id_categoria'] ?? null;
    
    // Obtener datos según el tipo de reporte
    if ($tipoReporte === 'inscripciones') {
        $datos = $reporteModel->getInscripcionesPorCurso($fechaInicio, $fechaFin, $idCategoria);
        $titulo = 'Reporte de Inscripciones por Curso';
    } else {
        $datos = $reporteModel->getIngresosPorCurso($fechaInicio, $fechaFin, $idCategoria);
        $titulo = 'Reporte de Ingresos por Curso';
    }
    
    // Generar HTML para el PDF
    $html = generarHTMLParaPDF($titulo, $datos, $tipoReporte, $fechaInicio, $fechaFin);
    
    // Configurar headers para descarga de PDF
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: inline; filename="reporte_' . $tipoReporte . '_' . date('Y-m-d') . '.html"');
    
    echo $html;
}

function generarHTMLParaPDF($titulo, $datos, $tipo, $fechaInicio, $fechaFin) {
    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>' . htmlspecialchars($titulo) . '</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #6c63ff; text-align: center; }
            .info { text-align: center; margin-bottom: 20px; color: #666; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            th { background-color: #6c63ff; color: white; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <h1>' . htmlspecialchars($titulo) . '</h1>
        <div class="info">
            <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>';
    
    if ($fechaInicio && $fechaFin) {
        $html .= '<p>Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)) . '</p>';
    }
    
    $html .= '</div><table><thead><tr>';
    
    if ($tipo === 'inscripciones') {
        $html .= '
            <th>Curso</th>
            <th>Categoría</th>
            <th>Instructor</th>
            <th>Total Inscripciones</th>
            <th>Precio</th>
        ';
    } else {
        $html .= '
            <th>Curso</th>
            <th>Categoría</th>
            <th>Instructor</th>
            <th>Inscripciones</th>
            <th>Pagos Aprobados</th>
            <th>Ingresos Totales</th>
        ';
    }
    
    $html .= '</tr></thead><tbody>';
    
    foreach ($datos as $fila) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($fila['titulo']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['categoria'] ?? 'Sin categoría') . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['instructor'] ?? 'Sin instructor') . '</td>';
        
        if ($tipo === 'inscripciones') {
            $html .= '<td>' . $fila['total_inscripciones'] . '</td>';
            $html .= '<td>$' . number_format($fila['precio'], 2) . '</td>';
        } else {
            $html .= '<td>' . $fila['total_inscripciones'] . '</td>';
            $html .= '<td>' . $fila['pagos_aprobados'] . '</td>';
            $html .= '<td>$' . number_format($fila['ingresos_totales'], 2) . '</td>';
        }
        
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    $html .= '<div class="footer"><p>Curzilla - Sistema de Gestión de Cursos</p></div>';
    $html .= '</body></html>';
    
    return $html;
}