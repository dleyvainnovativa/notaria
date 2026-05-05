
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

window.ajaxRequest=ajaxRequest
window.statusFormatter=statusFormatter
window.actionsFormatter=actionsFormatter

document.addEventListener('DOMContentLoaded', function() {
    const $table = $('#memorials-table');
    Object.assign(tableOptions,{
   onClickRow: function (row, $element, field) {
        window.location.href = `${admin_url}memorial/${row.slug}`;
    }  
});
    if ($table.length) {
        $table.bootstrapTable(tableOptions);
    }
});