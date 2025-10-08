// Funcionalidad para los botones de incremento/decremento
document.addEventListener('DOMContentLoaded', function() {
    
    // Manejar botones de contador
    const counterButtons = document.querySelectorAll('.curzilla-counter-btn');
    counterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            let value = parseInt(input.value) || 0;
            
            if (action === 'increase') {
                value++;
            } else if (action === 'decrease' && value > 0) {
                value--;
            }
            
            input.value = value;
        });
    });

    // Manejar agregar videos
    let videoCount = 1;
    const btnAddVideo = document.getElementById('btn-add-video');
    const videosContainer = document.getElementById('videos-container');

    // Función para actualizar la visibilidad de los botones de eliminar
    function actualizarVisibilidadBotonesEliminar() {
        const videoItems = videosContainer.querySelectorAll('.video-item');
        videoItems.forEach((item, index) => {
            const btnRemove = item.querySelector('.btn-remove-video');
            btnRemove.style.display = (videoItems.length > 1) ? 'inline-block' : 'none';
        });
    }

    btnAddVideo.addEventListener('click', function() {
        const videoItem = document.createElement('div');
        videoItem.className = 'video-item';
        videoItem.innerHTML = `
            <div class="curzilla-form-group">
                <label>Título del video</label>
                <input type="text" name="videos[${videoCount}][titulo]" class="curzilla-form-input" placeholder="Ejemplo: Lección ${videoCount + 1}">
            </div>
            <div class="curzilla-form-group">
                <label>URL de YouTube</label>
                <input type="url" name="videos[${videoCount}][url]" class="curzilla-form-input" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <button type="button" class="curzilla-btn curzilla-btn-danger btn-remove-video">Eliminar video</button>
        `;
        
        videosContainer.appendChild(videoItem);
        videoCount++;
        
        // Agregar evento para eliminar
        videoItem.querySelector('.btn-remove-video').addEventListener('click', function() {
            this.closest('.video-item').remove();
            actualizarVisibilidadBotonesEliminar();
        });

        actualizarVisibilidadBotonesEliminar();
    });

    // Añadir evento al botón de eliminar del primer video
    const primerBotonEliminar = videosContainer.querySelector('.btn-remove-video');
    if (primerBotonEliminar) {
        primerBotonEliminar.addEventListener('click', function() {
            this.closest('.video-item').remove();
            actualizarVisibilidadBotonesEliminar();
        });
    }

    // Manejar vista previa de archivos adjuntos
    const inputArchivos = document.getElementById('archivos');
    const archivosSeleccionados = document.getElementById('archivos-seleccionados');

    inputArchivos.addEventListener('change', function() {
        archivosSeleccionados.innerHTML = '';
        
        if (this.files.length > 0) {
            const lista = document.createElement('ul');
            lista.className = 'lista-archivos';
            
            Array.from(this.files).forEach(file => {
                const item = document.createElement('li');
                item.textContent = `${file.name} (${formatFileSize(file.size)})`;
                lista.appendChild(item);
            });
            
            archivosSeleccionados.appendChild(lista);
        }
    });

    // Manejar preview de imagen de portada
    const inputPortada = document.getElementById('portada');
    const previewPortada = document.getElementById('preview-portada');

    inputPortada.addEventListener('change', function() {
        previewPortada.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validar tamaño
            if (file.size > 2 * 1024 * 1024) {
                alert('La imagen es demasiado grande. Máximo 2MB');
                this.value = '';
                return;
            }
            
            // Validar tipo
            if (!file.type.match('image/(jpeg|jpg|png)')) {
                alert('Solo se permiten imágenes JPG, JPEG o PNG');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'preview-imagen';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Vista previa" style="max-width: 300px; max-height: 200px; margin-top: 10px;">
                    <p>${file.name} (${formatFileSize(file.size)})</p>
                `;
                previewPortada.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Validación del formulario antes de enviar
    const form = document.getElementById('form-crear-curso');
    form.addEventListener('submit', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
            alert('Por favor completa todos los campos obligatorios');
        }
    });
});

// Función para guardar como borrador
function guardarComo(estado) {
    const estadoInput = document.getElementById('estado-curso');
    estadoInput.value = estado;
    document.getElementById('form-crear-curso').submit();
}

// Función para limpiar el formulario
function limpiarFormulario() {
    if (confirm('¿Estás seguro de que deseas limpiar el formulario? Se perderá toda la información ingresada.')) {
        document.getElementById('form-crear-curso').reset();
        document.getElementById('archivos-seleccionados').innerHTML = '';
        document.getElementById('preview-portada').innerHTML = '';
        
        // Limpiar videos adicionales (dejar solo el primero)
        const videosContainer = document.getElementById('videos-container');
        const videoItems = videosContainer.querySelectorAll('.video-item');
        videoItems.forEach((item, index) => {
            if (index > 0) {
                item.remove();
            }
        });
    }
}

// Función para validar el formulario
function validarFormulario() {
    const titulo = document.getElementById('course-title').value.trim();
    const descripcion = document.getElementById('course-description').value.trim();
    const modalidad = document.getElementById('modality').value;
    const precio = document.getElementById('price').value;
    const cupos = document.getElementById('max-participants').value;
    const categorias = document.getElementById('categorias').selectedOptions.length;
    const portada = document.getElementById('portada').files.length;
    
    if (!titulo || !descripcion || !modalidad || precio === '' || cupos === '' || categorias === 0 || portada === 0) {
        return false;
    }
    
    return true;
}

// Función auxiliar para formatear tamaño de archivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Validación de URLs de YouTube
document.addEventListener('DOMContentLoaded', function() {
    const videosContainer = document.getElementById('videos-container');
    
    videosContainer.addEventListener('blur', function(e) {
        if (e.target.name && e.target.name.includes('url')) {
            const url = e.target.value.trim();
            if (url && !isValidYouTubeUrl(url)) {
                alert('Por favor ingresa una URL válida de YouTube');
                e.target.value = '';
            }
        }
    }, true);
});

function isValidYouTubeUrl(url) {
    const pattern = /^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[\w-]{11}/;
    return pattern.test(url);
}

// Formateo automático del precio
document.addEventListener('DOMContentLoaded', function() {
    const precioInput = document.getElementById('price');
    
    precioInput.addEventListener('blur', function() {
        let value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });
});