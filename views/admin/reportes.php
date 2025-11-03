<?php 
$pageTitle = "Panel de Reportes - Curzilla";

// Asegurarse de que el usuario esté autenticado y sea un administrador
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    // Redirigir al index con mensaje de error
    header('Location: /portal_cursos/public/index.php?error=no_autorizado');
    exit;
}

// Iniciar el buffer de salida
ob_start();
?>

<link rel="stylesheet" href="/portal_cursos/public/assets/css/reportes.css?v=<?php echo time(); ?>">

<main class="reportes-main">
    <header class="reportes-header">
        <div class="reportes-header-content">
            <h1>Reportes</h1>
        </div>
    </header>

    <section class="reportes-container">
        <!-- Filtros -->
        <div class="filtros-section">
            <div class="filtro-grupo">
                <label for="tipoReporte">Tipo de Reporte</label>
                <select id="tipoReporte" class="filtro-select">
                    <option value="">Selecciona un tipo</option>
                    <option value="inscripciones">Inscripciones por Curso</option>
                    <option value="ingresos">Ingresos por Curso</option>
                </select>
            </div>

            <div class="filtro-grupo">
                <label for="categoriaFiltro">Categoría</label>
                <select id="categoriaFiltro" class="filtro-select">
                    <option value="">Todas las categorías</option>
                </select>
            </div>

            <div class="filtro-fecha-grupo">
                <div class="filtro-grupo">
                    <label>Fecha inicio</label>
                    <div class="fecha-inputs">
                        <input type="number" id="diaInicio" placeholder="DD" min="1" max="31" class="fecha-input">
                        <input type="number" id="mesInicio" placeholder="MM" min="1" max="12" class="fecha-input">
                        <input type="number" id="anioInicio" placeholder="AAAA" min="2000" max="2100" class="fecha-input">
                    </div>
                </div>

                <div class="filtro-grupo">
                    <label>Fecha fin</label>
                    <div class="fecha-inputs">
                        <input type="number" id="diaFin" placeholder="DD" min="1" max="31" class="fecha-input">
                        <input type="number" id="mesFin" placeholder="MM" min="1" max="12" class="fecha-input">
                        <input type="number" id="anioFin" placeholder="AAAA" min="2000" max="2100" class="fecha-input">
                    </div>
                </div>
            </div>

            <div class="filtro-acciones">
                <button id="btnFiltrar" class="btn-filtrar">Generar Reporte</button>
                <button id="btnLimpiar" class="btn-limpiar">Limpiar Filtros</button>
                <button id="btnExportar" class="btn-exportar" style="display: none;">Exportar PDF</button>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="estadisticas-section" id="estadisticasSection">
            <h2>Estadísticas Generales</h2>
            <div class="estadisticas-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <h3 id="totalUsuarios">0</h3>
                        <p>Total Usuarios</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <h3 id="totalCursos">0</h3>
                        <p>Total Cursos</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✍️</div>
                    <div class="stat-info">
                        <h3 id="totalInscripciones">0</h3>
                        <p>Total Inscripciones</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3 id="ingresosTotales">$0.00</h3>
                        <p>Ingresos Totales</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Resultados -->
        <div class="tabla-section" id="tablaSection" style="display: none;">
            <h2 id="tituloTabla">Últimos Reportes</h2>
            <div class="tabla-container">
                <table id="tablaReportes">
                    <thead id="tablaHead">
                        <tr>
                            <th>Curso</th>
                            <th>Categoría</th>
                            <th>Instructor</th>
                            <th>Total Inscripciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <tr>
                            <td colspan="4" class="no-data">Selecciona un tipo de reporte y haz clic en "Generar Reporte"</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cursos Más Populares -->
        <div class="populares-section" id="popularesSection">
            <h2>Top 10 Cursos Más Populares</h2>
            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Curso</th>
                            <th>Categoría</th>
                            <th>Instructor</th>
                            <th>Inscripciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPopulares">
                        <tr>
                            <td colspan="5" class="no-data">Cargando...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script src="/portal_cursos/public/assets/js/reportes.js?v=<?php echo time(); ?>"></script>

<?php
// Capturar el contenido del buffer y limpiarlo
$content = ob_get_clean();

// Incluir el layout
include __DIR__ . '/../layouts/layout.php';
?>