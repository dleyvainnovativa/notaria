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
                await updateLifeEntries();
                await setButtonLoading(submitButton, false);

            }
            form.classList.add('was-validated');
        }, false);
    });
})();

function initRequest(params) {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) {
        return;
    }
    fetch(`${api_url}${memorial_slug}/life`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            removeShimmer();
            buildEntries(data);
            buildInfoView(data);

        })
        .catch(error => {
            console.error(error);
        });
}

function buildEntries(entries){
    let container = document.getElementById("life-entries");
container.innerHTML = ``;
if (!entries || entries.paragraphs.length === 0) {
        container.innerHTML = `
            <p class="text-muted pb-0 mb-0">No hay registros todavía</p>`;
        return;
    }

    entries.paragraphs.forEach(element => {
        let text_container = document.createElement("p");
        text_container.textContent = element.content;
        container.appendChild(text_container);
    });
}

function buildInfoView(memorial) {
    const textarea = document.getElementById('life');

    if (!textarea) return;

    if (!memorial.paragraphs || memorial.paragraphs.length === 0) {
        textarea.value = '';
        textarea.classList.remove('shimmer');
        return;
    }

    textarea.value = memorial.paragraphs
        .sort((a, b) => a.position - b.position)
        .map(p => p.content)
        .join("\n\n");

        autoResizeTextarea(textarea);

    textarea.classList.remove('shimmer');
}

function autoResizeTextarea(el) {
  el.style.height = 'auto'; // reset
  el.style.height = el.scrollHeight + 'px';
}

async function updateLifeEntries() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) {
        throw new Error('Auth token not found');
    }

    const textarea = document.getElementById('life');
    const lifeText = textarea.value.trim();
    try {

    const response = await fetch(`${api_url}${memorial_slug}/life`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
            life_text: lifeText
        })
    });

    const data = await response.json();
     if (!response.ok) {
            handleApiError(response.status, data);
            return;
        }
        bootstrap.Modal.getInstance(
        document.getElementById('lifeModal')
    ).hide();
        initRequest();
        showAlert("Registros actualizados","Se han actualizado correctamente los registros","","success")
        return data;

        } catch (error) {
        showAlert("Ha ocurrido un error","No se han actualizado correctamente los registros, intente de nuevo","","danger")

        return error;
    }
}

initRequest();