// Variables globales
let datosActuales = []
let tipoReporteActual = ""

const BASE_URL = window.location.origin + "/portal_cursos"
const CONTROLLER_URL = BASE_URL + "/controllers/ReporteController.php"

console.log("BASE_URL detectada:", BASE_URL)
console.log("CONTROLLER_URL:", CONTROLLER_URL)

// Inicializar al cargar la página
document.addEventListener("DOMContentLoaded", () => {
  console.log("Inicializando panel de reportes...")
  cargarCategorias()
  cargarEstadisticasGenerales()
  cargarCursosPopulares()
  cargarGraficosIniciales()

  // Event listeners
  const btnFiltrar = document.getElementById("btnFiltrar")
  const btnLimpiar = document.getElementById("btnLimpiar")
  const btnExportar = document.getElementById("btnExportar")

  if (btnFiltrar) {
    btnFiltrar.addEventListener("click", generarReporte)
    console.log("Event listener agregado a btnFiltrar")
  } else {
    console.error("No se encontró el botón btnFiltrar")
  }

  if (btnLimpiar) {
    btnLimpiar.addEventListener("click", limpiarFiltros)
  }

  if (btnExportar) {
    btnExportar.addEventListener("click", exportarPDF)
  }
})

// Cargar categorías en el select
async function cargarCategorias() {
  console.log("Cargando categorías...")
  try {
    const url = `${CONTROLLER_URL}?accion=categorias`
    console.log("Fetching:", url)
    const response = await fetch(url)
    console.log("Response status:", response.status)

    const contentType = response.headers.get("content-type")
    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text()
      console.error("La respuesta no es JSON:", text)
      throw new Error("La respuesta del servidor no es JSON válida")
    }

    const data = await response.json()
    console.log("Categorías recibidas:", data)

    if (data.success) {
      const select = document.getElementById("categoriaFiltro")
      data.categorias.forEach((cat) => {
        const option = document.createElement("option")
        option.value = cat.id_categoria
        option.textContent = cat.nombre
        select.appendChild(option)
      })
      console.log("Categorías cargadas exitosamente")
    } else {
      console.error("Error al cargar categorías:", data.message)
    }
  } catch (error) {
    console.error("Error al cargar categorías:", error)
    alert("Error al cargar categorías. Verifica la consola para más detalles.")
  }
}

// Cargar estadísticas generales
async function cargarEstadisticasGenerales() {
  console.log("Cargando estadísticas generales...")
  try {
    const url = `${CONTROLLER_URL}?accion=estadisticas`
    console.log("Fetching:", url)
    const response = await fetch(url)
    console.log("Response status:", response.status)

    const contentType = response.headers.get("content-type")
    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text()
      console.error("La respuesta no es JSON:", text)
      throw new Error("La respuesta del servidor no es JSON válida")
    }

    const data = await response.json()
    console.log("Estadísticas recibidas:", data)

    if (data.success) {
      const stats = data.estadisticas
      document.getElementById("totalUsuarios").textContent = stats.total_usuarios
      document.getElementById("totalCursos").textContent = stats.total_cursos
      document.getElementById("totalInscripciones").textContent = stats.total_inscripciones
      document.getElementById("ingresosTotales").textContent =
        "$" + Number.parseFloat(stats.ingresos_totales).toFixed(2)
      console.log("Estadísticas cargadas exitosamente")
    } else {
      console.error("Error al cargar estadísticas:", data.message)
    }
  } catch (error) {
    console.error("Error al cargar estadísticas:", error)
  }
}

// Cargar cursos más populares
async function cargarCursosPopulares() {
  console.log("Cargando cursos populares...")
  try {
    const url = `${CONTROLLER_URL}?accion=cursos_populares&limite=10`
    console.log("Fetching:", url)
    const response = await fetch(url)
    console.log("Response status:", response.status)

    const contentType = response.headers.get("content-type")
    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text()
      console.error("La respuesta no es JSON:", text)
      throw new Error("La respuesta del servidor no es JSON válida")
    }

    const data = await response.json()
    console.log("Cursos populares recibidos:", data)

    if (data.success) {
      const tbody = document.getElementById("tablaPopulares")
      tbody.innerHTML = ""

      if (data.datos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="no-data">No hay datos disponibles</td></tr>'
        return
      }

      data.datos.forEach((curso, index) => {
        const tr = document.createElement("tr")
        tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${curso.titulo}</td>
                    <td>${curso.categoria || "Sin categoría"}</td>
                    <td>${curso.instructor || "Sin instructor"}</td>
                    <td>${curso.total_inscripciones}</td>
                `
        tbody.appendChild(tr)
      })
      console.log("Cursos populares cargados exitosamente")
    } else {
      console.error("Error al cargar cursos populares:", data.message)
    }
  } catch (error) {
    console.error("Error al cargar cursos populares:", error)
  }
}

// Cargar gráficos iniciales
async function cargarGraficosIniciales() {
  console.log("Cargando gráficos iniciales...")
  try {
    // Usuarios por rol
    const urlRol = `${CONTROLLER_URL}?accion=usuarios_rol`
    console.log("Fetching usuarios por rol:", urlRol)
    const responseRol = await fetch(urlRol)
    console.log("Response status usuarios_rol:", responseRol.status)

    const contentTypeRol = responseRol.headers.get("content-type")
    if (contentTypeRol && contentTypeRol.includes("application/json")) {
      const dataRol = await responseRol.json()
      console.log("Datos usuarios por rol:", dataRol)

      if (dataRol.success) {
        dibujarGraficoBarras("grafico1", dataRol.datos, "rol", "total_usuarios", "Usuarios")
      }
    } else {
      const text = await responseRol.text()
      console.error("Error en usuarios_rol, respuesta:", text)
    }

    // Cursos por categoría
    const urlCat = `${CONTROLLER_URL}?accion=cursos_categoria`
    console.log("Fetching cursos por categoría:", urlCat)
    const responseCat = await fetch(urlCat)
    console.log("Response status cursos_categoria:", responseCat.status)

    const contentTypeCat = responseCat.headers.get("content-type")
    if (contentTypeCat && contentTypeCat.includes("application/json")) {
      const dataCat = await responseCat.json()
      console.log("Datos cursos por categoría:", dataCat)

      if (dataCat.success) {
        dibujarGraficoBarras("grafico2", dataCat.datos, "categoria", "total_cursos", "Cursos")
      }
    } else {
      const text = await responseCat.text()
      console.error("Error en cursos_categoria, respuesta:", text)
    }

    document.getElementById("graficosSection").style.display = "grid"
    console.log("Gráficos cargados exitosamente")
  } catch (error) {
    console.error("Error al cargar gráficos:", error)
  }
}

// Generar reporte según filtros
async function generarReporte() {
  console.log("Generando reporte...")
  const tipoReporte = document.getElementById("tipoReporte").value
  console.log("Tipo de reporte seleccionado:", tipoReporte)

  if (!tipoReporte) {
    alert("Por favor selecciona un tipo de reporte")
    return
  }

  tipoReporteActual = tipoReporte

  // Obtener filtros
  const params = new URLSearchParams()
  params.append("accion", tipoReporte)

  const idCategoria = document.getElementById("categoriaFiltro").value
  if (idCategoria) {
    params.append("id_categoria", idCategoria)
    console.log("Filtro categoría:", idCategoria)
  }

  const fechaInicio = obtenerFecha("Inicio")
  const fechaFin = obtenerFecha("Fin")

  if (fechaInicio && fechaFin) {
    params.append("fecha_inicio", fechaInicio)
    params.append("fecha_fin", fechaFin)
    console.log("Filtro fechas:", fechaInicio, "-", fechaFin)
  }

  const url = `${CONTROLLER_URL}?${params.toString()}`
  console.log("URL de petición:", url)

  try {
    const response = await fetch(url)
    console.log("Response status:", response.status)

    const contentType = response.headers.get("content-type")
    console.log("Content-Type:", contentType)

    if (!contentType || !contentType.includes("application/json")) {
      const text = await response.text()
      console.error("La respuesta no es JSON:", text)
      alert("Error: El servidor no devolvió una respuesta JSON válida.\nRespuesta: " + text.substring(0, 200))
      return
    }

    const data = await response.json()
    console.log("Datos del reporte recibidos:", data)
    console.log("¿Success?:", data.success)
    console.log("Cantidad de datos:", data.datos ? data.datos.length : "sin datos")

    if (data.success) {
      datosActuales = data.datos
      console.log("Llamando mostrarTablaReporte con:", datosActuales)
      mostrarTablaReporte(data.datos, tipoReporte)
      document.getElementById("btnExportar").style.display = "inline-block"
      console.log("Reporte generado exitosamente")
    } else {
      alert("Error al generar reporte: " + data.message)
      console.error("Error en respuesta:", data.message)
    }
  } catch (error) {
    console.error("Error al generar reporte:", error)
    console.error("Error stack:", error.stack)
    alert("Error al generar reporte. Revisa la consola para más detalles.")
  }
}

// Mostrar tabla de reporte
function mostrarTablaReporte(datos, tipo) {
  console.log("mostrarTablaReporte llamado con tipo:", tipo)
  console.log("datos recibidos:", datos)

  const tablaSection = document.getElementById("tablaSection")
  const tituloTabla = document.getElementById("tituloTabla")
  const thead = document.getElementById("tablaHead")
  const tbody = document.getElementById("tablaBody")

  console.log("[v0] Elementos encontrados:", {
    tablaSection: !!tablaSection,
    tituloTabla: !!tituloTabla,
    thead: !!thead,
    tbody: !!tbody,
  })

  // Actualizar título
  tituloTabla.textContent =
    tipo === "inscripciones" ? "Reporte de Inscripciones por Curso" : "Reporte de Ingresos por Curso"

  // Actualizar encabezados
  if (tipo === "inscripciones") {
    thead.innerHTML = `
            <tr>
                <th>Curso</th>
                <th>Categoría</th>
                <th>Instructor</th>
                <th>Total Inscripciones</th>
                <th>Precio</th>
            </tr>
        `
  } else {
    thead.innerHTML = `
            <tr>
                <th>Curso</th>
                <th>Categoría</th>
                <th>Instructor</th>
                <th>Inscripciones</th>
                <th>Pagos Aprobados</th>
                <th>Ingresos Totales</th>
            </tr>
        `
  }

  // Llenar datos
  tbody.innerHTML = ""

  if (datos.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="' +
      (tipo === "inscripciones" ? "5" : "6") +
      '" class="no-data">No se encontraron datos para los filtros seleccionados</td></tr>'
  } else {
    datos.forEach((fila) => {
      const tr = document.createElement("tr")

      if (tipo === "inscripciones") {
        tr.innerHTML = `
                    <td>${fila.titulo}</td>
                    <td>${fila.categoria || "Sin categoría"}</td>
                    <td>${fila.instructor || "Sin instructor"}</td>
                    <td>${fila.total_inscripciones}</td>
                    <td>$${Number.parseFloat(fila.precio).toFixed(2)}</td>
                `
      } else {
        tr.innerHTML = `
                    <td>${fila.titulo}</td>
                    <td>${fila.categoria || "Sin categoría"}</td>
                    <td>${fila.instructor || "Sin instructor"}</td>
                    <td>${fila.total_inscripciones}</td>
                    <td>${fila.pagos_aprobados}</td>
                    <td>$${Number.parseFloat(fila.ingresos_totales).toFixed(2)}</td>
                `
      }

      tbody.appendChild(tr)
    })
  }

  tablaSection.style.display = "block"
}

// Limpiar filtros
function limpiarFiltros() {
  document.getElementById("tipoReporte").value = ""
  document.getElementById("categoriaFiltro").value = ""
  document.getElementById("diaInicio").value = ""
  document.getElementById("mesInicio").value = ""
  document.getElementById("anioInicio").value = ""
  document.getElementById("diaFin").value = ""
  document.getElementById("mesFin").value = ""
  document.getElementById("anioFin").value = ""

  document.getElementById("tablaSection").style.display = "none"
  document.getElementById("btnExportar").style.display = "none"

  datosActuales = []
  tipoReporteActual = ""
}

// Obtener fecha en formato YYYY-MM-DD
function obtenerFecha(tipo) {
  const dia = document.getElementById("dia" + tipo).value
  const mes = document.getElementById("mes" + tipo).value
  const anio = document.getElementById("anio" + tipo).value

  if (dia && mes && anio) {
    return `${anio}-${mes.padStart(2, "0")}-${dia.padStart(2, "0")}`
  }

  return null
}

// Exportar a PDF
function exportarPDF() {
  if (!tipoReporteActual) {
    alert("No hay reporte para exportar")
    return
  }

  const params = new URLSearchParams()
  params.append("accion", "exportar_pdf")
  params.append("tipo", tipoReporteActual)

  const idCategoria = document.getElementById("categoriaFiltro").value
  if (idCategoria) {
    params.append("id_categoria", idCategoria)
  }

  const fechaInicio = obtenerFecha("Inicio")
  const fechaFin = obtenerFecha("Fin")

  if (fechaInicio && fechaFin) {
    params.append("fecha_inicio", fechaInicio)
    params.append("fecha_fin", fechaFin)
  }

  const url = `${CONTROLLER_URL}?${params.toString()}`
  console.log("[v0] Exportando PDF:", url)
  window.open(url, "_blank")
}

// Dibujar gráfico de barras usando Canvas nativo
function dibujarGraficoBarras(canvasId, datos, labelKey, valueKey, unidad) {
  const canvas = document.getElementById(canvasId)
  const ctx = canvas.getContext("2d")

  // Configurar tamaño del canvas
  canvas.width = canvas.offsetWidth
  canvas.height = 300

  const width = canvas.width
  const height = canvas.height
  const padding = 40
  const chartWidth = width - padding * 2
  const chartHeight = height - padding * 2

  // Limpiar canvas
  ctx.clearRect(0, 0, width, height)

  if (datos.length === 0) {
    ctx.fillStyle = "#999"
    ctx.font = "14px Arial"
    ctx.textAlign = "center"
    ctx.fillText("No hay datos disponibles", width / 2, height / 2)
    return
  }

  // Encontrar valor máximo
  const maxValue = Math.max(...datos.map((d) => Number.parseInt(d[valueKey])))
  const barWidth = chartWidth / datos.length - 10

  // Dibujar barras
  datos.forEach((item, index) => {
    const value = Number.parseInt(item[valueKey])
    const barHeight = (value / maxValue) * chartHeight
    const x = padding + index * (chartWidth / datos.length) + 5
    const y = height - padding - barHeight

    // Barra
    ctx.fillStyle = "#6c63ff"
    ctx.fillRect(x, y, barWidth, barHeight)

    // Valor encima de la barra
    ctx.fillStyle = "#333"
    ctx.font = "12px Arial"
    ctx.textAlign = "center"
    ctx.fillText(value, x + barWidth / 2, y - 5)

    // Etiqueta debajo de la barra
    ctx.save()
    ctx.translate(x + barWidth / 2, height - padding + 15)
    ctx.rotate(-Math.PI / 4)
    ctx.textAlign = "right"
    ctx.fillText(item[labelKey], 0, 0)
    ctx.restore()
  })

  // Línea base
  ctx.strokeStyle = "#ddd"
  ctx.lineWidth = 2
  ctx.beginPath()
  ctx.moveTo(padding, height - padding)
  ctx.lineTo(width - padding, height - padding)
  ctx.stroke()
}
