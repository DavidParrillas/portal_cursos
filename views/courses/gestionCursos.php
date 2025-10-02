<?php 
$pageTitle = "Gestión de Cursos - Curzilla";
// Asegurarse de que el usuario esté autenticado y sea un administrador
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    // Redirigir a una página de acceso no autorizado o a la página de inicio
    header('Location: /portal_cursos/index.php');
    exit;
}

include __DIR__ . '/../layouts/layout.php';
?>
<main>
    <header class="gc-header">
        <div class="gc-header-content">
            <h1>Gestión de cursos</h1>
        </div>
    </header>

    <section class="gc-container">
        <div class="gc-table-container">
            <table>
                <caption>Listado de cursos y su estado</caption>
                <thead>
                    <tr>
                        <th scope="col">Nombre del curso</th>
                        <th scope="col">Fecha de solicitud</th>
                        <th scope="col">Área de aprendizaje</th>
                        <th scope="col">Tutor</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Operaciones</th>
                    </tr>
                </thead>
                <tbody id="courseTable">
                    <!-- Rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>