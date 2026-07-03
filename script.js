// Al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    inicializarFormulario();
});

function inicializarFormulario() {
    // Botón para agregar medicamentos
    document.getElementById('agregar-medicamento').addEventListener('click', agregarMedicamento);
    
    // Envío del formulario con AJAX (Fetch)
    document.getElementById('formularioAsistencia').addEventListener('submit', enviarFormulario);
}

let contadorMedicamentos = 1;

function agregarMedicamento() {
    const container = document.getElementById('medicamentos-container');
    const nuevoItem = document.createElement('div');
    nuevoItem.className = 'medicamento-item';
    nuevoItem.innerHTML = `
        <div class="form-group">
            <label>Nombre del Medicamento *</label>
            <input type="text" name="medicamentos[${contadorMedicamentos}][nombre]" required>
        </div>
        <div class="form-group">
            <label>Dosis</label>
            <input type="text" name="medicamentos[${contadorMedicamentos}][dosis]" placeholder="Ej: 500mg">
        </div>
        <div class="form-group">
            <label>Frecuencia</label>
            <input type="text" name="medicamentos[${contadorMedicamentos}][frecuencia]" placeholder="Ej: Cada 8 horas">
        </div>
        <div class="form-group">
            <label>Duración</label>
            <input type="text" name="medicamentos[${contadorMedicamentos}][duracion]" placeholder="Ej: 7 días">
        </div>
        <div class="form-group">
            <label>Indicaciones</label>
            <textarea name="medicamentos[${contadorMedicamentos}][indicaciones]" rows="2"></textarea>
        </div>
        <button type="button" class="btn-secondary" onclick="eliminarMedicamento(this)">Eliminar</button>
    `;
    container.appendChild(nuevoItem);
    contadorMedicamentos++;
}

function eliminarMedicamento(boton) {
    boton.parentElement.remove();
}

// FUNCIÓN NUEVA: Envía el formulario con Fetch (AJAX) y muestra el cartel
function enviarFormulario(e) {
    e.preventDefault(); // Evita que se recargue la página
    
    const formulario = e.target;
    const formData = new FormData(formulario);
    
    // Enviar con Fetch
    fetch('procesar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar cartel de éxito
            mostrarMensaje('✅ ' + data.message, 'exito');
            formulario.reset(); // Limpiar el formulario
        } else {
            // Mostrar cartel de error
            mostrarMensaje('❌ Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('❌ Error de conexión con el servidor', 'error');
    });
}

// Función para mostrar mensajes bonitos
function mostrarMensaje(texto, tipo) {
    // Crear el cartel
    const cartel = document.createElement('div');
    cartel.className = `mensaje-${tipo}`;
    cartel.textContent = texto;
    cartel.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease-out;
        background-color: ${tipo === 'exito' ? '#28a745' : '#dc3545'};
    `;
    
    document.body.appendChild(cartel);
    
    // Auto-eliminar después de 4 segundos
    setTimeout(() => {
        cartel.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => cartel.remove(), 300);
    }, 4000);
}