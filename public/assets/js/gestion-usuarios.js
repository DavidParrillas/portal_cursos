document.addEventListener('DOMContentLoaded', function () {
    const modalUsuarioElement = document.getElementById('modalUsuario');
    if (!modalUsuarioElement) return; // Salir si el modal no está en la página

    const modalUsuario = new bootstrap.Modal(modalUsuarioElement);
    const formUsuario = document.getElementById('formUsuario');
    const modalUsuarioLabel = document.getElementById('modalUsuarioLabel');
    const userIdField = document.getElementById('userId');
    const accionField = document.getElementById('accionForm');
    const helpPassword = document.getElementById('helpPassword');
    const asteriscoPassword = document.getElementById('asteriscoPassword');
    const divContrasena = document.getElementById('divContrasena');
    const contrasenaField = document.getElementById('contrasena');
    const nombreField = document.getElementById('nombre_completo');
    const correoField = document.getElementById('correo');
    const rolField = document.getElementById('id_rol');

    window.abrirModalUsuario = function(accion, id_usuario = null) {
        formUsuario.reset();
        formUsuario.classList.remove('was-validated');
        
        if (accion === 'crear') {
            modalUsuarioLabel.textContent = 'Crear Usuario';
            accionField.value = 'crear';
            userIdField.value = '';
            divContrasena.style.display = 'block';
            contrasenaField.required = true;
            asteriscoPassword.style.display = 'inline';
            helpPassword.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            modalUsuario.show();
        } else if (accion === 'editar') {
            modalUsuarioLabel.textContent = 'Editar Usuario';
            accionField.value = 'editar';
            userIdField.value = id_usuario;
            divContrasena.style.display = 'block';
            contrasenaField.required = false;
            asteriscoPassword.style.display = 'none';
            helpPassword.textContent = 'Deje en blanco para no cambiar la contraseña.';

            fetch(`/portal_cursos/controllers/UsuarioController.php?accion=obtener&id=${id_usuario}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nombreField.value = data.usuario.nombre_completo;
                        correoField.value = data.usuario.correo;
                        rolField.value = data.usuario.id_rol;
                        modalUsuario.show();
                    } else {
                        alert('Error al cargar datos del usuario: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);
                    alert('No se pudo conectar con el servidor.');
                });
        }
    }

    window.confirmarEliminar = function(id_usuario, nombre) {
        if (confirm(`¿Está seguro de que desea eliminar al usuario "${nombre}"?`)) {
            const formData = new FormData();
            formData.append('accion', 'eliminar');
            formData.append('id_usuario', id_usuario);

            fetch('/portal_cursos/controllers/UsuarioController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error al eliminar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                alert('No se pudo conectar con el servidor.');
            });
        }
    }

    formUsuario.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (formUsuario.checkValidity()) {
            const formData = new FormData(formUsuario);
            
            fetch('/portal_cursos/controllers/UsuarioController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    modalUsuario.hide();
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                alert('No se pudo conectar con el servidor.');
            });
        }

        formUsuario.classList.add('was-validated');
    }, false);
});
