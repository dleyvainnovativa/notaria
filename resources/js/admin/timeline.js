let currentEditId = null;


(() => {
    'use strict'
    const forms = document.querySelectorAll('#timeline-form.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', async event => {
        const submitButton = form.querySelector('button[type="submit"]');

            setButtonLoading(submitButton, true);
            event.preventDefault(); // always prevent native submit

            if (!form.checkValidity()) {
                event.stopPropagation();
                await setButtonLoading(submitButton, false);

            } else {
                await submitTimeline(form);
                await setButtonLoading(submitButton, false);
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

async function submitTimeline(form) {
    const token = localStorage.getItem('selahi_auth_token');

    const payload = {
        title: form.title.value,
        event_date: form.event_date.value,
        description: form.description.value
    };

    const url = currentEditId ?
        `${api_url}${memorial_slug}/timeline/${currentEditId}` :
        `${api_url}${memorial_slug}/timeline`;

    const method = currentEditId ? 'PUT' : 'POST';

    const response = await fetch(url, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
    });

    if (!response.ok) {
        showAlert('Error', 'No se pudo guardar el evento', '', 'danger');
        return;
    }

    bootstrap.Modal.getInstance(
        document.getElementById('accountModal')
    ).hide();

    form.reset();
    currentEditId = null;

    initRequest();
    showAlert('Éxito', 'Evento guardado correctamente', '', 'success');
}

function initRequest() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) return;
    fetch(`${api_url}${memorial_slug}/timeline`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(res => res.json())
        .then(data => {
            buildTimeline(data);
        })
        .catch(console.error);
}

function buildTimeline(items) {
    const container = document.getElementById('timeline_list');
    container.innerHTML = '';

    if (!items || items.length === 0) {
        container.innerHTML = `
            <div class="col-12">
        <div class="card bg-dark border border-dark text-bg-dark h-100">
            <div class="card-body p-4">
                <p class="text-muted mb-0">No hay eventos registrados todavía</p>
            </div>
        </div>
    </div>`;
        return;
    }
    items.forEach(item => {
        container.insertAdjacentHTML('beforeend', timelineCard(item));
    });
}

function timelineCard(item) {
    return `
    <div class="col-12 h-100">
        <div class="card card-dark border border-dark bg-dark h-100 text-dark">
            <div class="card-body px-4 pb-0">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-medium">${item.date}</span>
                        <h5 class="fw-semibold mb-1">${item.title}</h5>
                    </div>

                    <div class="d-flex gap-1">
                        <button
                            class="btn btn-outline-primary"
                            onclick="editTimeline(${item.id})">
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button
                            class="btn btn-outline-danger"
                            onclick="deleteTimeline(${item.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <p class="text-muted mb-0">${item.description ?? ''}</p>
            </div>
        </div>
    </div>`;
}

function editTimeline(id) {
    const token = localStorage.getItem('selahi_auth_token');

    fetch(`${api_url}${memorial_slug}/timeline/${id}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(res => res.json())
        .then(item => {
            currentEditId = id;

            const form = document.getElementById('timeline-form');
            form.title.value = item.title;
            form.event_date.value = item.date;
            form.description.value = item.description ?? '';

            document.getElementById('accountModalLabel').innerText = 'Editar evento';

            new bootstrap.Modal('#accountModal').show();
        });
}

async function deleteTimeline(id) {
    // if (!confirm('¿Eliminar este evento?')) return;
    if(!await confirmModal({
            title: "¿Estás seguro de borrar este registro?",
            text: 'Estás a punto de borrar este registro para siempre',
            mode: 'warning',
            confirmText: 'Borrar registro'
        })) return;

    const token = localStorage.getItem('selahi_auth_token');

    fetch(`${api_url}${memorial_slug}/timeline/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(res => {
            if (!res.ok) throw new Error();
            initRequest();
            showAlert('Eliminado', 'Evento eliminado correctamente', '', 'success');
        })
        .catch(() => {
            showAlert('Error', 'No se pudo eliminar el evento', '', 'danger');
        });
}
document
    .getElementById('accountModal')
    .addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('timeline-form');
        form.reset();
        currentEditId = null;
        document.getElementById('accountModalLabel').innerText = 'Agregar nuevo evento';
        form.classList.remove('was-validated');
    });
initRequest();
window.editTimeline=editTimeline;
window.deleteTimeline=deleteTimeline;
