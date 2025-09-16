<?php include __DIR__ . '/../layouts/layout.php'; ?>

<!-- Main Content -->
    <!-- Updated main content classes to be more specific -->
    <main class="curzilla-main-content">
        <div class="curzilla-container">
            <h1 class="curzilla-page-title">Aquí puedes agregar el contenido de tu proyecto</h1>
            
            <form class="curzilla-course-form">
                <!-- Course Title and Max Participants -->
                <!-- Updated form classes to avoid conflicts -->
                <div class="curzilla-form-row">
                    <div class="curzilla-form-group">
                        <label for="course-title">Título del curso</label>
                        <input type="text" id="course-title" class="curzilla-form-input">
                    </div>
                    <div class="curzilla-form-group">
                        <label for="max-participants">Máximo de participantes</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="max-participants">-</button>
                            <input type="number" id="max-participants" value="0" class="curzilla-counter-value">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="max-participants">+</button>
                        </div>
                    </div>
                </div>

                <!-- Course Description -->
                <div class="curzilla-form-group">
                    <label for="course-description">Descripción del Curso</label>
                    <textarea id="course-description" class="curzilla-form-textarea" rows="6"></textarea>
                </div>

                <!-- Duration Section -->
                <div class="curzilla-duration-section">
                    <h3 class="curzilla-section-title">
                        <span class="curzilla-clock-icon">🕐</span>
                        Duración del curso
                    </h3>
                    
                    <div class="curzilla-duration-controls">
                        <div class="curzilla-form-group">
                            <label>Secciones</label>
                            <div class="curzilla-counter-input">
                                <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="sections">-</button>
                                <input type="number" id="sections" value="0" class="curzilla-counter-value curzilla-purple">
                                <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="sections">+</button>
                            </div>
                        </div>
                        
                        <div class="curzilla-form-group">
                            <label>Clases</label>
                            <div class="curzilla-counter-input">
                                <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="classes">-</button>
                                <input type="number" id="classes" value="0" class="curzilla-counter-value curzilla-purple">
                                <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="classes">+</button>
                            </div>
                        </div>
                        
                        <div class="curzilla-form-group">
                            <label>Horas</label>
                            <div class="curzilla-counter-input">
                                <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="hours">-</button>
                                <input type="number" id="hours" value="0" class="curzilla-counter-value curzilla-purple">
                                <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="hours">+</button>
                            </div>
                        </div>
                        
                        <div class="curzilla-form-group">
                            <label>Fecha de Inicio</label>
                            <div class="curzilla-date-input">
                                <input type="number" placeholder="02" class="curzilla-date-part" min="1" max="31">
                                <input type="number" placeholder="8" class="curzilla-date-part" min="1" max="12">
                                <input type="number" placeholder="2025" class="curzilla-date-part" min="2024">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modality and Price -->
                <div class="curzilla-form-row">
                    <div class="curzilla-form-group">
                        <label for="modality">Modalidad</label>
                        <div class="curzilla-select-container">
                            <select id="modality" class="curzilla-form-select">
                                <option value="">Selecciona</option>
                                <option value="presencial">Presencial</option>
                                <option value="online">En línea</option>
                            </select>
                        </div>
                    </div>
                    <div class="curzilla-form-group">
                        <label for="price">
                            <span class="curzilla-price-icon">💰</span>
                            Agrega el precio
                        </label>
                        <input type="text" id="price" placeholder="$0.00" class="curzilla-form-input curzilla-price-input">
                    </div>
                </div>

                <!-- File Upload Sections -->
                <!-- Updated upload section classes -->
                <div class="curzilla-upload-section">
                    <h3 class="curzilla-upload-title">+ Agregar video</h3>
                    <div class="curzilla-upload-area">
                        <div class="curzilla-upload-content">
                            <div class="curzilla-upload-icon">☁️</div>
                            <p class="curzilla-upload-text">Máximo: 1GB</p>
                            <button type="button" class="curzilla-upload-btn">+ Subir video</button>
                        </div>
                    </div>
                </div>

                <div class="curzilla-upload-section">
                    <h3 class="curzilla-upload-title">+ Adjuntar archivos</h3>
                    <div class="curzilla-upload-area">
                        <div class="curzilla-upload-content">
                            <div class="curzilla-upload-icon">📁</div>
                            <p class="curzilla-upload-text">Archivos permitidos: PDF, PNG, JPG</p>
                            <button type="button" class="curzilla-upload-btn">+ Subir archivo</button>
                        </div>
                    </div>
                </div>

                <div class="curzilla-upload-section">
                    <h3 class="curzilla-upload-title">+ Adjuntar una imagen para la portada</h3>
                    <div class="curzilla-upload-area">
                        <div class="curzilla-upload-content">
                            <div class="curzilla-upload-icon">🖼️</div>
                            <p class="curzilla-upload-text">Archivos permitidos: PDF, PNG, JPG</p>
                            <button type="button" class="curzilla-upload-btn">+ Subir archivo</button>
                        </div>
                    </div>
                </div>

                <!-- Course Content Section -->
                <!-- Updated course content section classes -->
                <div class="curzilla-course-content-section">
                    <h2 class="curzilla-content-title">Contenido del Curso</h2>
                    <div class="curzilla-content-item">
                        <div class="curzilla-content-icon">📺</div>
                        <div class="curzilla-content-details">
                            <span class="curzilla-content-name">Introducción curso python</span>
                            <span class="curzilla-content-duration">Duración: 05:45</span>
                        </div>
                        <div class="curzilla-content-files">
                            <span>Archivos subidos:</span>
                            <a href="#" class="curzilla-file-link">Archivo adjunto</a>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <!-- Updated button classes to avoid conflicts -->
                <div class="curzilla-form-actions">
                    <button type="button" class="curzilla-btn curzilla-btn-secondary">Limpiar</button>
                    <button type="submit" class="curzilla-btn curzilla-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </main>


<script>
    // Counter functionality
    document.addEventListener("DOMContentLoaded", () => {
    // Handle counter buttons
    const counterButtons = document.querySelectorAll(".counter-btn")

    counterButtons.forEach((button) => {
        button.addEventListener("click", function () {
        const action = this.dataset.action
        const targetId = this.dataset.target
        const input = document.getElementById(targetId)
        let currentValue = Number.parseInt(input.value) || 0

        if (action === "increase") {
            currentValue++
        } else if (action === "decrease" && currentValue > 0) {
            currentValue--
        }

        input.value = currentValue
        })
    })

    // Handle file uploads
    const uploadButtons = document.querySelectorAll(".upload-btn")

    uploadButtons.forEach((button) => {
        button.addEventListener("click", () => {
        const input = document.createElement("input")
        input.type = "file"
        input.accept = ".pdf,.png,.jpg,.jpeg,.mp4,.mov"

        input.addEventListener("change", (e) => {
            const file = e.target.files[0]
            if (file) {
            console.log("[v0] File selected:", file.name)
            // Here you would handle the file upload
            alert(`Archivo seleccionado: ${file.name}`)
            }
        })

        input.click()
        })
    })

    // Handle form submission
    const form = document.querySelector(".course-form")

    form.addEventListener("submit", (e) => {
        e.preventDefault()

        // Collect form data
        const formData = {
        title: document.getElementById("course-title").value,
        description: document.getElementById("course-description").value,
        maxParticipants: document.getElementById("max-participants").value,
        sections: document.getElementById("sections").value,
        classes: document.getElementById("classes").value,
        hours: document.getElementById("hours").value,
        modality: document.getElementById("modality").value,
        price: document.getElementById("price").value,
        }

        console.log("[v0] Form data:", formData)
        alert("Curso guardado exitosamente!")
    })

    // Handle clear button
    const clearButton = document.querySelector(".btn-secondary")

    clearButton.addEventListener("click", () => {
        if (confirm("¿Estás seguro de que quieres limpiar todos los campos?")) {
        form.reset()

        // Reset counters to 0
        const counters = document.querySelectorAll(".counter-value")
        counters.forEach((counter) => {
            counter.value = "0"
        })
        }
    })

    // Handle modality dropdown display
    const modalitySelect = document.getElementById("modality")

    modalitySelect.addEventListener("change", function () {
        console.log("[v0] Modality selected:", this.value)
    })

    // Format price input
    const priceInput = document.getElementById("price")

    priceInput.addEventListener("input", function () {
        const value = this.value.replace(/[^\d.]/g, "")
        if (value) {
        this.value = "$" + value
        }
    })
    })

</script>