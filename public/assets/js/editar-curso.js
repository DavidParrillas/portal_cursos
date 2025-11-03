document.addEventListener('DOMContentLoaded', function() {
    // Manejadores para los contadores
    setupCounterButtons();
    
    // Manejador para agregar videos
    setupVideoManagement();
    
    // Manejadores para la carga de archivos
    setupFileUploads();
    
    // Manejador para eliminar materiales existentes
    setupMaterialDeletion();
});

function setupCounterButtons() {
    document.querySelectorAll('.curzilla-counter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const target = this.dataset.target;
            const input = document.getElementById(target);
            let value = parseInt(input.value);

            if (action === 'increase') {
                value++;
            } else if (action === 'decrease' && value > 0) {
                value--;
            }

            input.value = value;
        });
    });
}

function setupVideoManagement() {
    const container = document.getElementById('videos-container');
    const addButton = document.getElementById('btn-add-video');
    
    // Configurar botones de eliminar existentes
    container.querySelectorAll('.btn-remove-video').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.video-item').remove();
            updateVideoIndices();
        });
    });

    // Agregar nuevo video
    addButton.addEventListener('click', function() {
        const videoCount = container.querySelectorAll('.video-item').length;
        const videoItem = document.createElement('div');
        videoItem.className = 'video-item';
        videoItem.innerHTML = `
            <div class="curzilla-form-group">
                <label>Título del video</label>
                <input type="text" name="videos[${videoCount}][titulo]" class="curzilla-form-input" placeholder="Ejemplo: Introducción al curso">
            </div>
            <div class="curzilla-form-group">
                <label>URL de YouTube</label>
                <input type="url" name="videos[${videoCount}][url]" class="curzilla-form-input" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <button type="button" class="curzilla-btn curzilla-btn-danger btn-remove-video">Eliminar video</button>
        `;

        container.appendChild(videoItem);

        // Agregar event listener al nuevo botón de eliminar
        videoItem.querySelector('.btn-remove-video').addEventListener('click', function() {
            videoItem.remove();
            updateVideoIndices();
        });
    });
}

function updateVideoIndices() {
    const videos = document.querySelectorAll('.video-item');
    videos.forEach((video, index) => {
        video.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/\[\d+\]/, `[${index}]`);
            input.setAttribute('name', newName);
        });
    });
}

function setupFileUploads() {
    // Manejo de archivos de apoyo
    const archivosInput = document.getElementById('archivos');
    const archivosSeleccionados = document.getElementById('archivos-seleccionados');

    archivosInput.addEventListener('change', function() {
        archivosSeleccionados.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'archivo-item';
            fileItem.textContent = file.name;
            archivosSeleccionados.appendChild(fileItem);
        });
    });

    // Manejo de imagen de portada
    const portadaInput = document.getElementById('portada');
    const previewPortada = document.getElementById('preview-portada');

    portadaInput.addEventListener('change', function() {
        previewPortada.innerHTML = '';
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                previewPortada.appendChild(img);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

function setupMaterialDeletion() {
    document.querySelectorAll('.btn-remove-material').forEach(button => {
        button.addEventListener('click', function() {
            const materialId = this.dataset.id;
            if (confirm('¿Estás seguro de que deseas eliminar este material?')) {
                // Crear un input hidden para marcar el material como eliminado
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'materiales_eliminar[]';
                input.value = materialId;
                document.getElementById('form-editar-curso').appendChild(input);
                
                // Eliminar el elemento visual
                this.closest('.archivo-existente').remove();
            }
        });
    });
}