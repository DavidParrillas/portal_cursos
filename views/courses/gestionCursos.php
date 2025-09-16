<?php 
$pageTitle = "Gestión de Cursos - Curzilla";
include __DIR__ . '/../layouts/layout.php';
?>
<main>
    <div class="gc-header">
        <div class="gc-header-content">
        <h1>Gestión de cursos</h1>
        </div>
    </div>

    <div class="gc-container">
        <div class="gc-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre del curso</th>
                        <th>Fecha de solicitud</th>
                        <th>Área de aprendizaje</th>
                        <th>Tutor</th>
                        <th>Estado</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>
                <tbody id="courseTable">
                    <tr>
                        <td class="gc-course-name">Python desde cero</td>
                        <td class="gc-date">27/09/2025</td>
                        <td class="gc-area">Programación</td>
                        <td class="gc-tutor">Fernando Herrera</td>
                        <td>
                            <div class="gc-status">
                                <div class="gc-status-indicator" style="background-color: #ffc107;"></div>
                                <span class="gc-status-text">Pendiente</span>
                            </div>
                        </td>
                        <td>
                            <div class="gc-operations">
                                <button class="gc-operation-btn" onclick="viewCourse(0)" title="Ver curso">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="gc-operation-btn" onclick="editCourse(0)" title="Editar curso">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
    <script>
        // Sample course data
        const courses = [
            {
                name: "Python desde cero",
                date: "27/09/2025",
                area: "Programación",
                tutor: "Fernando Herrera",
                status: "Pendiente",
                statusColor: "#ffc107"
            }
        ];

        // Function to render courses
        function renderCourses() {
            const tableBody = document.getElementById('courseTable');
            tableBody.innerHTML = '';

            courses.forEach((course, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="gc-course-name">${course.name}</td>
                    <td class="gc-date">${course.date}</td>
                    <td class="gc-area">${course.area}</td>
                    <td class="gc-tutor">${course.tutor}</td>
                    <td>
                        <div class="gc-status">
                            <div class="gc-status-indicator" style="background-color: ${course.statusColor}"></div>
                            <span class="gc-status-text">${course.status}</span>
                        </div>
                    </td>
                    <td>
                        <div class="gc-operations">
                            <button class="gc-operation-btn" onclick="viewCourse(${index})" title="Ver curso">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="gc-operation-btn" onclick="editCourse(${index})" title="Editar curso">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Operation functions
        function viewCourse(index) {
            const course = courses[index];
            alert(`Ver curso: ${course.name}\nTutor: ${course.tutor}\nÁrea: ${course.area}`);
        }

        function editCourse(index) {
            const course = courses[index];
            alert(`Editar curso: ${course.name}`);
        }

        // Add new course function (for demonstration)
        function addCourse(name, date, area, tutor, status, statusColor) {
            courses.push({ name, date, area, tutor, status, statusColor });
            renderCourses();
        }

        // Initialize the table
        renderCourses();

        // Example of adding a new course (uncomment to test)
        // setTimeout(() => {
        //     addCourse("JavaScript Avanzado", "28/09/2025", "Programación", "Carlos Mendez", "Aprobado", "#28a745");
        // }, 3000);
    </script>
</body>
</html>