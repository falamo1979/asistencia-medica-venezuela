// Formulario de consulta: wizard por pasos + filas dinámicas de medicamentos.
// El envío es un POST normal de Laravel (validación en servidor + flash),
// no interceptamos el submit final.

document.addEventListener('DOMContentLoaded', () => {
    initMedicamentos();
    initWizard();
    initModals();
});

/* ============================================================
   MODALES (popup de edición)
   ============================================================ */
function openModal(modal) {
    if (!modal) return;
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    const first = modal.querySelector('input:not([type=hidden]), select, textarea');
    if (first) setTimeout(() => first.focus(), 50);
}

function closeModal(el) {
    const overlay = el.classList && el.classList.contains('modal-overlay') ? el : el.closest('.modal-overlay');
    if (!overlay) return;
    overlay.classList.remove('is-open');
    document.body.style.overflow = '';
}

function initModals() {
    document.addEventListener('click', (e) => {
        // Abrir modal desde un disparador con data-modal-target
        const trigger = e.target.closest('[data-modal-target]');
        if (trigger) {
            e.preventDefault();
            const modal = document.getElementById(trigger.dataset.modalTarget);
            if (!modal) return;

            // Solo manipulamos el formulario cuando el disparador trae data-action
            // (modales genéricos rellenados por JS). Los modales con el formulario
            // ya precargado en el servidor se abren tal cual, sin tocar sus campos.
            const form = trigger.dataset.action ? modal.querySelector('form') : null;
            if (form) {
                form.action = trigger.dataset.action;

                const methodField = form.querySelector('[name="_method"]');
                if (methodField) methodField.value = trigger.dataset.method || 'POST';

                const urlField = form.querySelector('[name="editing_url"]');
                if (urlField) urlField.value = trigger.dataset.action || '';

                // Limpiar campos
                form.querySelectorAll('input, select, textarea').forEach((f) => {
                    if (['_token', '_method', 'editing_url'].includes(f.name)) return;
                    if (f.type === 'checkbox' || f.type === 'radio') { f.checked = false; return; }
                    if (f.tagName === 'SELECT') { f.selectedIndex = 0; return; }
                    f.value = '';
                });

                // Rellenar desde data-values (JSON)
                if (trigger.dataset.values) {
                    const vals = JSON.parse(trigger.dataset.values);
                    Object.entries(vals).forEach(([k, v]) => {
                        const f = form.querySelector(`[name="${k}"]`);
                        if (f) f.value = v ?? '';
                    });
                }

                // Marcar campos required condicionales (ej: contraseña al crear)
                form.querySelectorAll('[data-required-on]').forEach((f) => {
                    f.required = f.dataset.requiredOn === (trigger.dataset.mode || 'edit');
                });
            }

            const title = modal.querySelector('.modal-title');
            if (title && trigger.dataset.title) title.textContent = trigger.dataset.title;

            openModal(modal);
            return;
        }

        // Cerrar
        if (e.target.closest('[data-close]')) { closeModal(e.target.closest('[data-close]')); return; }
        if (e.target.classList.contains('modal-overlay')) closeModal(e.target);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const open = document.querySelector('.modal-overlay.is-open');
            if (open) closeModal(open);
        }
    });
}

/* ============================================================
   MEDICAMENTOS (agregar / eliminar)
   ============================================================ */
let contadorMedicamentos = 1;

function initMedicamentos() {
    const btnAgregar = document.getElementById('agregar-medicamento');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', agregarMedicamento);
    }

    const container = document.getElementById('medicamentos-container');
    if (container) {
        // El próximo índice arranca después de los items ya renderizados
        // (importante al editar: evita colisionar con medicamentos existentes).
        contadorMedicamentos = container.querySelectorAll('.medicamento-item').length || 1;

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-eliminar-medicamento')) {
                e.target.closest('.medicamento-item').remove();
            }
        });
    }
}

function agregarMedicamento() {
    const container = document.getElementById('medicamentos-container');
    const i = contadorMedicamentos++;

    const item = document.createElement('div');
    item.className = 'medicamento-item';
    item.innerHTML = `
        <div class="form-group">
            <label>Nombre del Medicamento</label>
            <input type="text" name="medicamentos[${i}][nombre]">
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>Dosis</label>
                <input type="text" name="medicamentos[${i}][dosis]" placeholder="Ej: 500mg">
            </div>
            <div class="form-group">
                <label>Frecuencia</label>
                <input type="text" name="medicamentos[${i}][frecuencia]" placeholder="Ej: Cada 8 horas">
            </div>
            <div class="form-group">
                <label>Duración</label>
                <input type="text" name="medicamentos[${i}][duracion]" placeholder="Ej: 7 días">
            </div>
        </div>
        <div class="form-group">
            <label>Indicaciones</label>
            <textarea name="medicamentos[${i}][indicaciones]" rows="2"></textarea>
        </div>
        <button type="button" class="btn-danger btn-eliminar-medicamento">Eliminar</button>
    `;
    container.appendChild(item);
}

/* ============================================================
   WIZARD (formulario por pasos)
   ============================================================ */
function initWizard() {
    const wizard = document.getElementById('wizard');
    if (!wizard) return;

    const steps = Array.from(wizard.querySelectorAll('.form-step'));
    const items = Array.from(wizard.querySelectorAll('.stepper-item'));
    const btnPrev = document.getElementById('wizard-prev');
    const btnNext = document.getElementById('wizard-next');
    const btnSubmit = document.getElementById('wizard-submit');

    let current = 0;

    function render(scroll = false) {
        steps.forEach((s, i) => s.classList.toggle('is-active', i === current));
        items.forEach((it, i) => {
            it.classList.toggle('is-active', i === current);
            it.classList.toggle('is-done', i < current);
        });

        btnPrev.style.visibility = current === 0 ? 'hidden' : 'visible';
        const isLast = current === steps.length - 1;
        btnNext.hidden = isLast;
        btnSubmit.hidden = !isLast;

        if (scroll) {
            wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Valida solo los campos del paso actual usando la validación nativa.
    function stepIsValid() {
        const fields = steps[current].querySelectorAll('input, select, textarea');
        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    }

    btnNext.addEventListener('click', () => {
        if (stepIsValid() && current < steps.length - 1) {
            current++;
            render(true);
        }
    });

    btnPrev.addEventListener('click', () => {
        if (current > 0) {
            current--;
            render(true);
        }
    });

    // El stepper permite retroceder libremente (hacia adelante solo con "Siguiente").
    items.forEach((item, i) => {
        item.addEventListener('click', () => {
            if (i < current) {
                current = i;
                render(true);
            }
        });
    });

    // Si el servidor devolvió errores, saltar al paso del primer campo inválido.
    const firstInvalid = wizard.querySelector('.is-invalid');
    if (firstInvalid) {
        const step = firstInvalid.closest('.form-step');
        const idx = steps.indexOf(step);
        if (idx >= 0) current = idx;
    }

    render(false);
}
