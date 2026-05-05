document.addEventListener('DOMContentLoaded', () => {
    initRequest();
});

/* --------------------------
   INIT REQUEST
--------------------------- */
function initRequest() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) return;

    fetch(`${api_url}${memorial_slug}/tributes`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
        }
    })
        .then(res => res.json())
        .then(data => {
            const grid = document.querySelector('.grid-messages');
            grid.innerHTML = '';

            if (!data.tributes || data.tributes.length === 0) {
                grid.innerHTML = `
            <div class="col-12">
        <div class="card bg-dark border border-dark text-bg-dark h-100">
            <div class="card-body p-4">
                <p class="text-muted mb-0">No hay mensajes registrados todavía</p>
            </div>
        </div>
    </div>`;
                return;
            }

            data.tributes.forEach(tribute => {
                grid.appendChild(buildMessageItem(tribute));
            });
        })
        .catch(console.error);
}

/* --------------------------
   BUILD MESSAGE ITEM
--------------------------- */
function buildMessageItem(tribute) {
    const col = document.createElement('div');
    col.className = 'col-12 col-md-6';

    const statusBadge = tribute.is_approved
        ? `<span class="badge text-bg-success">Aprobada</span>`
        : `<span class="badge text-bg-warning">Pendiente</span>`;

    col.innerHTML = `
        <div class="card card-dark border border-dark shadow-sm">
            <div class="card-body p-4 text-dark">
                <div class="row g-3">
                    <div class="col-12">
                        ${statusBadge}
                    </div>

                    <div class="col-12">
                        <p class="mb-0">"${escapeHtml(tribute.message)}"</p>
                    </div>

                    <div class="col-auto">
                        <span class="text-muted fw-bold">
                            ${escapeHtml(tribute.author_name)}
                        </span>
                    </div>

                    <div class="col-auto ms-auto">
                        <span class="text-muted fw-light">
                            ${formatDate(tribute.created_at)}
                        </span>
                    </div>

                    <div class="col-12 text-end">
                        <div class="btn-group">
                            <button class="btn btn-danger btn-sm">
                                Rechazar
                            </button>
                            <button class="btn btn-success btn-sm">
                                Aprobar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    const [rejectBtn, approveBtn] = col.querySelectorAll('button');

    rejectBtn.addEventListener('click', () => {
        updateTributeStatus(tribute.id, 'reject');
    });

    approveBtn.addEventListener('click', () => {
        updateTributeStatus(tribute.id, 'approve');
    });

    return col;
}

/* --------------------------
   APPROVE / REJECT
--------------------------- */
function updateTributeStatus(id, action) {
    const token = localStorage.getItem('selahi_auth_token');

    fetch(`${api_url}${memorial_slug}/tributes/${id}/${action}`, {
        method: 'PATCH',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
        }
    })
        .then(res => {
            if (!res.ok) throw new Error();
            initRequest();
            showAlert(
                'Actualizado',
                `El mensaje fue ${action === 'approve' ? 'aprobado' : 'rechazado'} correctamente`,
                '',
                'success'
            );
        })
        .catch(() => {
            showAlert(
                'Error',
                'No se pudo actualizar el mensaje',
                '',
                'danger'
            );
        });
}

/* --------------------------
   HELPERS
--------------------------- */
function formatDate(date) {
    return new Date(date).toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
