function ejecutarAccion(accion, idCurso) {
    fetch(`/portal_cursos/controllers/AdminCursoController.php?action=${accion}`,
    {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_curso: idCurso
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alertify.success(data.mensaje);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            alertify.error(data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al procesar la solicitud.');
    });
}

function aprobarCurso(id) {
    alertify.confirm('Aprobar Curso', '¿Estás seguro de que deseas aprobar el curso?', function(){
        ejecutarAccion('aprobar', id);
    }, function(){
        alertify.error('Acción cancelada');
    });
}

function rechazarCurso(id) {
    alertify.confirm('Rechazar Curso', '¿Estás seguro de que deseas rechazar el curso?', function(){
        ejecutarAccion('rechazar', id);
    }, function(){
        alertify.error('Acción cancelada');
    });
}

function archivarCurso(id) {
    alertify.confirm('Archivar Curso', '¿Estás seguro de que deseas archivar el curso?', function(){
        ejecutarAccion('archivar', id);
    }, function(){
        alertify.error('Acción cancelada');
    });
}

function publicarCurso(id) {
    alertify.confirm('Publicar Curso', '¿Estás seguro de que deseas publicar el curso?', function(){
        ejecutarAccion('publicar', id);
    }, function(){
        alertify.error('Acción cancelada');
    });
}

function eliminarCurso(id) {
    alertify.confirm('Eliminar Curso', '¿Estás seguro de que deseas eliminar permanentemente el curso? Esta acción no se puede deshacer.', function(){
        ejecutarAccion('eliminar', id);
    }, function(){
        alertify.error('Acción cancelada');
    });
}