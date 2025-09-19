<?php 
$pageTitle = "Gestión de Cursos - Curzilla";
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