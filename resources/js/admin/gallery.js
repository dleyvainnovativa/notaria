let selectedFiles = [];
let sortableInstance;

document.addEventListener('DOMContentLoaded', () => {
    initRequest();
    initDropzone();
});

/* --------------------------
   INIT REQUEST
--------------------------- */
function initRequest() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) return;

    fetch(`${api_url}${memorial_slug}/gallery`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            }
        })
        .then(res => res.json())
        .then(data => {
            const grid = document.querySelector('.grid-gallery');
            const gridOrder = document.querySelector('.grid-gallery-order');
            grid.innerHTML = '';
            gridOrder.innerHTML = '';

            if (!data.images || data.images.length === 0) {
                gridOrder.innerHTML = `
            <div class="col-12">
        <div class="card bg-dark border border-dark text-bg-dark h-100">
            <div class="card-body p-4">
                <p class="text-muted mb-0">No hay imágenes registradas todavía</p>
            </div>
        </div>
    </div>`;
                grid.innerHTML = `
            <div class="col-12">
        <div class="card bg-dark border border-dark text-bg-dark h-100">
            <div class="card-body p-4">
                <p class="text-muted mb-0">No hay imágenes registradas todavía</p>
            </div>
        </div>
    </div>`;
                return;
            }

            data.images.forEach(img => {
                grid.appendChild(buildGalleryItem(img));
                gridOrder.appendChild(buildGalleryOrderItem(img));

            });
            initSortable();
        })
        .catch(console.error);
}

/* --------------------------
   BUILD GALLERY ITEM
--------------------------- */
function buildGalleryItem(image) {
    const col = document.createElement('div');
    col.className = 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 grid-item';

    col.innerHTML = `
        <div class="card card-image rounded border border-dark position-relative">
            <img
                src="${app_url}${image.url}"
                class="card-img-top rounded"
                alt="Gallery image"
            >

            <button
                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                title="Eliminar"
            >
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    col.querySelector('button').addEventListener('click', () => {
        deleteImage(image.id, col);
    });

    return col;
}

function buildGalleryOrderItem(image) {
    const item = document.createElement('div');
    item.className = 'order-item card mb-2 py-2 ps-2 pe-3 d-flex flex-row align-items-center card-dark border border-dark text-bg-dark ordered-item';

    item.setAttribute('data-id', image.id);

    item.innerHTML = `
        <img 
            src="${app_url}${image.thumbnail_url ?? image.url}" 
            style="width:60px; height:60px; object-fit:cover; border-radius:6px;"
        >
        <div class="ms-3 flex-grow-1">
            <small class="text-muted">${image.caption ?? 'Sin descripción'}</small>
        </div>
        <i class="fas fa-up-down-left-right fa-lg ms-auto text-primary"></i>
    `;

    return item;
}


function initSortable() {
    const container = document.querySelector('.grid-gallery-order');

    if (sortableInstance) {
        sortableInstance.destroy(); // 👈 prevent duplicates
    }

    sortableInstance = new Sortable(container, {
        animation: 150,
        ghostClass: 'bg-primary',
        handle: '.ordered-item',
    });
}

/* --------------------------
   DELETE IMAGE
--------------------------- */
async function deleteImage(id, element) {
    if (!await confirmModal({
            title: "¿Estás seguro de borrar este item?",
            text: 'Estás a punto de borrar este item para siempre',
            mode: 'warning',
            confirmText: 'Borrar item'
        })) return;

    const token = localStorage.getItem('selahi_auth_token');

    fetch(`${api_url}${memorial_slug}/gallery/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            }
        })
        .then(res => {
            if (!res.ok) throw new Error();
            element.remove();
            initRequest();

            showAlert('Imagen eliminada', 'La imagen fue eliminada correctamente', '', 'success');
        })
        .catch(() => {
            showAlert('Error', 'No se pudo eliminar la imagen', '', 'danger');
        });
}

/* --------------------------
   DROPZONE + PREVIEW
--------------------------- */
function initDropzone() {
    const dropzone = document.getElementById('dropzone');
    const input = document.getElementById('gallery_input');
    const preview = document.getElementById('preview_container');
    const form = document.getElementById('gallery-form');

    dropzone.addEventListener('click', () => input.click());

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('border-primary');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-primary');
    });

    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('border-primary');
        handleFiles(e.dataTransfer.files);
    });

    input.addEventListener('change', e => {
        handleFiles(e.target.files);
    });

    form.addEventListener('submit', e => {
        e.preventDefault();
        uploadImages();
    });

    function handleFiles(files) {
        [...files].forEach(file => {
            if (!file.type.startsWith('image/')) return;

            selectedFiles.push(file);

            const col = document.createElement('div');
            col.className = 'col-4';

            col.innerHTML = `
                <div class="position-relative">
                    <img src="${URL.createObjectURL(file)}"
                         class="img-fluid rounded border border-dark">
                </div>
            `;

            preview.appendChild(col);
        });
    }
}

/* --------------------------
   UPLOAD IMAGES
--------------------------- */
function uploadImages() {
    if (selectedFiles.length === 0) return;

    const token = localStorage.getItem('selahi_auth_token');
    const formData = new FormData();

    selectedFiles.forEach(file => {
        formData.append('images[]', file);
    });

    fetch(`${api_url}${memorial_slug}/gallery`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => {
            selectedFiles = [];
            document.getElementById('preview_container').innerHTML = '';
            bootstrap.Modal.getInstance(
                document.getElementById('accountModal')
            ).hide();
            initRequest();
            const form = document.getElementById('gallery-form');
            document.getElementById("preview_container").innerHTML = ``;
            form.reset();
            showAlert('Imágenes agregadas', 'Las imágenes se subieron correctamente', '', 'success');
        })
        .catch(() => {
            showAlert('Error', 'No se pudieron subir las imágenes', '', 'danger');
        });
}

document.getElementById('saveOrderBtn').addEventListener('click', saveOrder);

function saveOrder() {
    const token = localStorage.getItem('selahi_auth_token');
    if (!token) return;

    const items = document.querySelectorAll('.grid-gallery-order .order-item');

    const ordered = Array.from(items).map((el, index) => ({
        id: el.getAttribute('data-id'),
        position: index + 1
    }));

    fetch(`${api_url}${memorial_slug}/gallery/order`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({ items: ordered })
    })
    .then(res => res.json())
    .then(() => {

            showAlert('Orden guardado', 'El orden de las imágenes se ha guardado correctamente', '', 'success');

        // Optional: reload gallery
        initRequest();

        // Close offcanvas (Bootstrap)
        const offcanvasEl = document.getElementById('orderOffcanvas');
        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
        offcanvas.hide();
    })
    .catch(console.error);
}