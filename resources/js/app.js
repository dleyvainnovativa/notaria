import 'bootstrap';
import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import 'bootstrap-table';
import 'bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.js';
import 'bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js';
import AOS from 'aos';
import 'aos/dist/aos.css';
import BigPicture from 'bigpicture';
import Shuffle from 'shufflejs';
import Sortable from 'sortablejs';


window.bootstrap = bootstrap;
window.BigPicture = BigPicture;
window.Shuffle = Shuffle;
window.Sortable = Sortable;
window.jQuery = $;
window.$ = $;

let app_url = document.querySelector('meta[name="app-url"]').getAttribute('content');
let api_url = document.querySelector('meta[name="api-url"]').getAttribute('content');
let admin_url = document.querySelector('meta[name="admin-url"]').getAttribute('content');
window.app_url=app_url;
window.admin_url=admin_url;
window.api_url=api_url;

AOS.init(); // Initialize AOS

function parseFirebaseError(error) {
    console.log(error);
    console.log({
    code: error.code,
    message: error.message,
    name: error.name,
});
    if (!error.code) return 'Ocurrió un error inesperado';

    switch (error.code) {
        case 'auth/email-already-in-use':
            return 'Este correo ya está registrado';
        case 'auth/invalid-credential':
            return 'El usuario o la contraseña es incorrecta';
        case 'auth/weak-password':
            return 'La contraseña debe tener al menos 6 caracteres';
        case 'auth/invalid-email':
            return 'Correo electrónico no válido';
        default:
            return 'No se pudo crear la cuenta';
    }
}
window.parseFirebaseError = parseFirebaseError;


async function confirmModal({ title, text, mode = "confirm", confirmText = "Ok", cancelButton = true }) {
    return new Promise((resolve) => {

        let modalEl = document.getElementById("popupModal");
        let bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

        // DOM elements
        let titleEl = document.getElementById("popup_title");
        let textEl = document.getElementById("popup_text");
        let cancelBtn = document.getElementById("cancel_btn");

        let confirmIcon = document.getElementById("confirm_popup_icon");
        let warningIcon = document.getElementById("warning_popup_icon");

        let confirmBtn = document.getElementById("confirm_popup_btn");
        let warningBtn = document.getElementById("warning_popup_btn");

        if(cancelButton){
            cancelBtn.hidden=false;
        }else{
            cancelBtn.hidden=true;


        }

        // Reset icons and buttons
        confirmIcon.classList.add("d-none");
        warningIcon.classList.add("d-none");
        confirmBtn.classList.add("d-none");
        warningBtn.classList.add("d-none");
        
        // Apply content
        titleEl.innerText = title;
        textEl.innerText = text;
        confirmBtn.textContent = confirmText;
        warningBtn.textContent = confirmText;

        // Apply mode
        if (mode === "confirm") {
            confirmIcon.classList.remove("d-none");
            confirmBtn.classList.remove("d-none");
        } else {
            warningIcon.classList.remove("d-none");
            warningBtn.classList.remove("d-none");
        }
        // Button handlers
        const confirmHandler = () => {
            cleanup();
            resolve(true);
            bsModal.hide();
        };
        const cancelHandler = () => {
            cleanup();
            resolve(false);
        };
        confirmBtn.addEventListener("click", confirmHandler, { once: true });
        warningBtn.addEventListener("click", confirmHandler, { once: true });
        modalEl.addEventListener("hidden.bs.modal", cancelHandler, { once: true });

        function cleanup() {
            modalEl.removeEventListener("hidden.bs.modal", cancelHandler);
        }
        // Show modal
        bsModal.show();
    });
}

const root = document.documentElement;
function updateLogos() {
    const logos = document.querySelectorAll(".appLogo");
    console.log(logos);

    const isLight = root.classList.contains("theme-light");
    const src = isLight
        ? `${app_url}img/logo.png`
        :`${app_url}img/logo_dark.png`
    if(isLight){
        document.getElementById("themeIcon").classList.add("fa-sun");
        document.getElementById("themeIcon").classList.remove("fa-moon");
    }else{
        document.getElementById("themeIcon").classList.add("fa-moon");
        document.getElementById("themeIcon").classList.remove("fa-sun");
    }
    logos.forEach(logo => {
        logo.src = src;
    });
}
window.updateLogos=updateLogos;

document.addEventListener("DOMContentLoaded", () => {
    const root = document.documentElement;
    const toggle = document.getElementById("themeToggle");
    const metaThemeColor = document.querySelector('meta[name="theme-color"]');

    function applyThemeColor(isLight) {
        metaThemeColor?.setAttribute(
            "content",
            isLight ? "#3b5df6" : "#0b0b18"
        );
    }

    // 🔹 Load saved theme
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
        root.classList.add("theme-light");
        toggle.checked = true;
    } else {
        root.classList.remove("theme-light");
        toggle.checked = false;
    }
    applyThemeColor(toggle.checked);
    updateLogos();

    // 🔹 Listen to switch change
    toggle.addEventListener("change", () => {
        const isLight = toggle.checked;
        root.classList.toggle("theme-light", isLight);
        localStorage.setItem("theme", isLight ? "light" : "dark");
        applyThemeColor(isLight);
    updateLogos();

    });
});

function setButtonLoading(button, isLoading) {
    if (isLoading) {
        const originalWidth = button.offsetWidth;
        if (!button.dataset.originalText) {
            button.dataset.originalText = button.textContent.trim();
        }
        button.innerHTML = `
        <div class="row g-3 justify-content-center align-items-center">
            <div class="col-auto">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
            <div id='text-loading' class="col-auto">
                <small class= btn-text">Procesando...</small>
            </div>
        </div>
        `;
        button.disabled = true;
        requestAnimationFrame(() => {
            const newWidth = button.offsetWidth;
            const small = button.querySelector("#text-loading");
            if (small && newWidth > originalWidth) {
                small.classList.add("d-none"); // hide text
            }
        });
    } else {
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
        }
        button.disabled = false;
    }
}

// window.addEventListener('DOMContentLoaded', event => {
//     const sidebarToggle = document.body.querySelector('#sidebarToggle');
//     if (sidebarToggle) {
//         sidebarToggle.addEventListener('click', event => {
//             event.preventDefault();
//             document.body.classList.toggle('sb-sidenav-toggled');
//         });
//     }
// });

function showAlert(
    title,
    message,
    subtitle = "",
    status = "success" // success | danger | warning | info
) {
    const toastEl = document.getElementById("liveToast");

    const titleEl = toastEl.querySelector("#alertTitle");
    const messageEl = toastEl.querySelector("#alertMessage");
    const subtitleEl = toastEl.querySelector("#alertSubtitle");
    const iconEl = toastEl.querySelector("#alertIcon");

    // Map status → classes & icons
    const statusMap = {
        success: {
            class: 'text-bg-success',
            icon: 'fa-solid fa-circle-check'
        },
        danger: {
            class: 'text-bg-danger',
            icon: 'fa-solid fa-circle-xmark'
        },
        warning: {
            class: 'text-bg-warning',
            icon: 'fa-solid fa-triangle-exclamation'
        },
        info: {
            class: 'text-bg-info',
            icon: 'fa-solid fa-circle-info'
        }
    };

    const current = statusMap[status] || statusMap.success;

    // Clean previous bg classes
    toastEl.classList.remove(
        'text-bg-success',
        'text-bg-danger',
        'text-bg-warning',
        'text-bg-info'
    );

    // Apply new classes
    toastEl.classList.add(current.class);

    // Header & body background sync
    toastEl.querySelector('.toast-header').className = `toast-header ${current.class}`;
    toastEl.querySelector('.toast-body').className = `toast-body ${current.class} rounded`;
    toastEl.querySelector('.toast-header a').className = `${current.class} me-2`;

    // Content
    titleEl.textContent = title;
    messageEl.textContent = message;
    subtitleEl.textContent = subtitle;
    iconEl.className = current.icon;

    const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
    toast.show();
}


window.confirmModal = confirmModal;
window.setButtonLoading = setButtonLoading;
window.showAlert = showAlert;


document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('scroll-navbar');
    const trigger = document.getElementById('back-trigger');

    if (!navbar || !trigger) return;
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                // Back button visible → hide navbar
                navbar.classList.add('d-none');
            } else {
                // Back button hidden → show navbar
                navbar.classList.remove('d-none');
            }
        },
        {
            root: null,
            threshold: 0,
        }
    );
    observer.observe(trigger);
});

document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('scroll-navbar-messages');
    const trigger = document.getElementById('messages-trigger');

    if (!navbar || !trigger) return;
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                // Back button visible → hide navbar
                navbar.classList.add('d-none');
            } else {
                // Back button hidden → show navbar
                navbar.classList.remove('d-none');
            }
        },
        {
            root: null,
            threshold: 0,
        }
    );
    observer.observe(trigger);
});

document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('scroll-navbar-gallery');
    const trigger = document.getElementById('gallery-trigger');

    if (!navbar || !trigger) return;
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                // Back button visible → hide navbar
                navbar.classList.add('d-none');
            } else {
                // Back button hidden → show navbar
                navbar.classList.remove('d-none');
            }
        },
        {
            root: null,
            threshold: 0,
        }
    );
    observer.observe(trigger);
});
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('scroll-navbar-partners');
    const trigger = document.getElementById('partners-trigger');

    if (!navbar || !trigger) return;
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                // Back button visible → hide navbar
                navbar.classList.add('d-none');
            } else {
                // Back button hidden → show navbar
                navbar.classList.remove('d-none');
            }
        },
        {
            root: null,
            threshold: 0,
        }
    );
    observer.observe(trigger);
});