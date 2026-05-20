import '../firebase/firebase-listener';
import { auth } from "../firebase/firebase-init";

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebar-overlay');
    toggleBtn?.addEventListener('click', function () {
        document.body.classList.toggle('sb-sidenav-toggled');
        if(document.querySelector("body").classList.contains("sb-sidenav-toggled")){
            overlay.classList.remove("d-none");
        }else{
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
        localStorage.setItem("selahi_auth_token", freshToken);
        console.log("Token refreshed on visibility change");
    } catch (err) {
        console.error("Failed to refresh token on visibility", err);
    }
});

let memorial_slug = document.getElementById("memorial_slug").value || '';

window.memorial_slug=memorial_slug;
window.removeShimmer=removeShimmer;
window.handleApiError=handleApiError;
