function ajaxRequest(params) {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) {
        // window.location.href = `${app_url}login`;
        return;
    }
    const url = new URL(params.url);
    url.searchParams.set('search', params.data.search || '');
    url.searchParams.set('page', (params.data.offset / params.data.limit) + 1);

    fetch(url, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            // if (response.status === 401) { logout(); }
            return response.json();
        })
        .then(data => {
            // console.log(data);
            params.success({
                total: data.length,
                rows: data // Note: It's data.data now, not data.payments.data
            });
        })
        .catch(error => {
            console.error(error);
            params.error();
        });
}

function statusFormatter(value, row) {
    return value ? '<span class="badge bg-success">Público</span>' : '<span class="badge bg-danger">Privado</span>';
}

function actionsFormatter(value, row) {
    return `<a class="btn btn-success" href="${admin_url}memorial/${value}"><i class="fas fa-eye"></i></span>`;
}

function openEditModal(row) {

    // Save ID
    document.getElementById('edit_invitation_id').value = row.id;

    // Email
    document.getElementById('edit_email').value = row.email;

    // Permissions (map from backend fields)
    document.getElementById('edit_perm_info').checked = !!row.can_edit_info;
    document.getElementById('edit_perm_timeline').checked = !!row.can_edit_timeline;
    document.getElementById('edit_perm_life').checked = !!row.can_edit_life;
    document.getElementById('edit_perm_gallery').checked = !!row.can_edit_gallery;
    document.getElementById('edit_perm_messages').checked = !!row.can_edit_messages;

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('editInviteModal'));
    modal.show();
}

async function editPermissions() {
    let submitButton = document.getElementById('edit_permissions_submit');
    const token = localStorage.getItem('selahi_auth_token');
    const invitationId = document.getElementById('edit_invitation_id').value;
                await setButtonLoading(submitButton, true);


    const permissions = {
        info: document.getElementById('edit_perm_info').checked,
        timeline: document.getElementById('edit_perm_timeline').checked,
        life: document.getElementById('edit_perm_life').checked,
        gallery: document.getElementById('edit_perm_gallery').checked,
        messages: document.getElementById('edit_perm_messages').checked,
    };

    try {
        const response = await fetch(`${api_url}invitations/${invitationId}/permissions`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                permissions: permissions
            })
        });

        const data = await response.json();

        if (!response.ok) {
            await setButtonLoading(submitButton, false);
            handleApiError(response.status, data);
            return;
        }

        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('editInviteModal')).hide();

        // Refresh table
        $('#invitations-table').bootstrapTable('refresh');
        await setButtonLoading(submitButton, false);

        showAlert("Permisos actualizados", "Los cambios se guardaron correctamente", "", "success");

    } catch (error) {
        await setButtonLoading(submitButton, false);
        console.error(error);
        showAlert("Error", "No se pudieron actualizar los permisos", "", "danger");
    }
}

window.ajaxRequest = ajaxRequest;
window.statusFormatter = statusFormatter;
window.actionsFormatter = actionsFormatter;
window.editPermissions = editPermissions;

document.addEventListener('DOMContentLoaded', function () {
    const $table = $('#invitations-table');
    Object.assign(tableOptions,{
        onClickRow: function (row, $element, field) {
            openEditModal(row);
        }  
    });
    if ($table.length) {
        $table.bootstrapTable(tableOptions);
    }
});

(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', async event => {
            const submitButton = form.querySelector('button[type="submit"]');

            setButtonLoading(submitButton, true);
            event.preventDefault(); // always prevent native submit

            if (!form.checkValidity()) {
                event.stopPropagation();
                await setButtonLoading(submitButton, false);

            } else {
                await invite();
                await setButtonLoading(submitButton, false);

            }
            form.classList.add('was-validated');
        }, false);
    });
})();
document.getElementById('perm_all').addEventListener('change', function () {
    const checked = this.checked;
    document.querySelectorAll('input[name^="permissions"]').forEach(el => {
        el.checked = checked;
    });
});

async function invite() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) {
        throw new Error('Auth token not found');
    }

    const email = document.getElementById('invitation_email');
    const permissions = {
        info: document.getElementById('perm_info')?.checked || false,
        timeline: document.getElementById('perm_timeline')?.checked || false,
        life: document.getElementById('perm_life')?.checked || false,
        gallery: document.getElementById('perm_gallery')?.checked || false,
        messages: document.getElementById('perm_messages')?.checked || false,
    };

    try {

        const response = await fetch(`${api_url}${memorial_slug}/invite`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                email: email.value.trim(),
                permissions: permissions

            })
        });
        const data = await response.json();

        if (!response.ok) {
            handleApiError(response.status, data);
            return;
        }
        bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
        const $table = $('#invitations-table');
        $table.bootstrapTable('refresh');
        document.getElementById('invite-form').reset();
        showAlert("Registros actualizados", "Se han actualizado correctamente los registros", "", "success")
        return data;

    } catch (error) {
        console.error(error);
        showAlert("Ha ocurrido un error", "No se ha enviado la invitación, intente de nuevo", "", "danger")

        return error;
    }
}
