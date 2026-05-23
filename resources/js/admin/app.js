import '../firebase/firebase-listener';
import {
    auth
} from "../firebase/firebase-init";

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebar-overlay');
    toggleBtn?.addEventListener('click', function () {
        document.body.classList.toggle('sb-sidenav-toggled');
        if (document.querySelector("body").classList.contains("sb-sidenav-toggled")) {
            overlay.classList.remove("d-none");
        } else {
            overlay.classList.add("d-none");
        }
    });
    overlay.addEventListener('click', function () {
        document.body.classList.remove('sb-sidenav-toggled');
        overlay.classList.add("d-none");

    });
});

function removeShimmer() {
    document.querySelectorAll('.shimmer').forEach(el => {
        el.classList.remove('shimmer', 'input-shimmer', 'image-shimmer');
        el.removeAttribute('disabled');
    });
}

function handleApiError(status, data) {
    // Laravel validation error
    if (status === 422 && data.errors) {
        const messages = Object.values(data.errors)
            .flat()
            .join("<br>");

        showAlert(
            "Error de validación",
            messages,
            "",
            "danger"
        );
        return;
    }

    // Forbidden
    if (status === 403) {
        showAlert(
            "Acceso denegado",
            "No tienes permisos para modificar este memorial",
            "",
            "danger"
        );
        return;
    }

    // Generic fallback
    showAlert(
        "Ha ocurrido un error",
        data.message || "No se han actualizado correctamente los datos, intente de nuevo",
        "",
        "danger"
    );
}

document.addEventListener("visibilitychange", async () => {
    if (document.visibilityState !== "visible") return;
    console.log("visibility");
    const user = auth.currentUser;
    if (!user) return;

    try {
        const freshToken = await user.getIdToken(true);
        localStorage.setItem("notaria_auth_token", freshToken);
        console.log("Token refreshed on visibility change");
    } catch (err) {
        console.error("Failed to refresh token on visibility", err);
    }
});

let memorial_slug = document.getElementById("memorial_slug").value || '';


async function getRequest(url) {
    const token = localStorage.getItem('notaria_auth_token');
    if (!token) return;
    try {
        const response = await fetch(`${url}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
        });

        const data = await response.json();

        if (!data.success) {
            showAlert("Error", data.message || "Ha ocurrido un error", "", "danger");
            return null;
        }
        return data;
    } catch (error) {
        showAlert("Ha ocurrido un error", `${error}`, "", "danger");
        console.error(error);
        return null;
    }
}
async function postRequest(url, payload) {
    const token = localStorage.getItem('notaria_auth_token');
    if (!token) return;
    try {
        const response = await fetch(`${api_url}${url}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!data.success) {
            showAlert("Error", data.message || "Ha ocurrido un error", "", "danger");
            return null;
        }

        return data;

    } catch (error) {
        showAlert("Ha ocurrido un error", `${error}`, "", "danger");
        console.error(error);
        return null;
    }
}
async function postRequestFormData(url, formData) {
    const token = localStorage.getItem('notaria_auth_token');
    if (!token) return;
    try {
        const response = await fetch(`${api_url}${url}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            showAlert("Error", data.message || "Ha ocurrido un error", "", "danger");
            return null;
        }

        return data;

    } catch (error) {
        showAlert("Ha ocurrido un error", `${error}`, "", "danger");
        console.error(error);
        return null;
    }
}


async function validateForm(form, event) {
    try {
        const submitButton = form.querySelector('button[type="submit"]');
        console.log(submitButton);
        await setButtonLoading(submitButton, true);
        console.log("Button Loading true");
        event.preventDefault();
        if (!form.checkValidity()) {
            event.stopPropagation();
            await setButtonLoading(submitButton, false);
            console.log("Button Loading false");
            form.classList.add('was-validated');
            return false;
        } else {
            form.classList.add('was-validated');
            return true;
        }
    } catch (error) {
        await setButtonLoading(submitButton, false);
        return false;
    }
}


window.validateForm = validateForm;
window.getRequest = getRequest;
window.postRequest = postRequest;
window.postRequestFormData = postRequestFormData;
window.handleApiError = handleApiError;
