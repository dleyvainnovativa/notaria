let rotation = 0;

function toggleIcon(el) {
    // rotation += 360;

    // el.querySelectorAll('.icon').forEach(icon => {
    //     icon.style.transform = `rotate(${rotation}deg)`;
    // });

    // el.classList.toggle('flipped');
    switchTheme();
}
window.toggleIcon = toggleIcon;


document.addEventListener('DOMContentLoaded', () => {
    const offcanvas = document.querySelector('#timelineBottomSheet');
    const body = offcanvas.querySelector('#timelineBottomSheet .offcanvas-body');

    const maxTop = -20; // %
    const maxSide = 4; // %
    const minTop = 0;
    const minSide = 0;

    const maxRadius = 50; // px
    const minRadius = 0;

    body.addEventListener('scroll', () => {
        const scrollTop = body.scrollTop;
        const maxScroll = 250; // tweak sensitivity

        // Clamp progress between 0 and 1
        const progress = Math.min(scrollTop / maxScroll, 1);

        // Interpolate margins
        const currentTop = maxTop + (minTop - maxTop) * progress;
        const currentSide = maxSide + (minSide - maxSide) * progress;

        // Interpolate radius
        const currentRadius = maxRadius + (minRadius - maxRadius) * progress;

        offcanvas.style.marginBottom = `${currentTop}%`;
        offcanvas.style.marginLeft = `${currentSide}%`;
        offcanvas.style.marginRight = `${currentSide}%`;

        offcanvas.style.borderTopLeftRadius = `${currentRadius}px`;
        offcanvas.style.borderTopRightRadius = `${currentRadius}px`;
    });
});

(() => {
    'use strict';

    const form = document.getElementById('tribute-form');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        const submitButton = form.querySelector('button[type="submit"]');
        setButtonLoading(submitButton, true);
        event.preventDefault();
        event.stopPropagation();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
                await setButtonLoading(submitButton, false);

            return;
        }else{
            try {
            await submitTribute(form);
                await setButtonLoading(submitButton, false);

            form.reset();
            form.classList.remove('was-validated');

            confirmModal({
            title: "Tu mensaje fue enviado",
            text: 'Tu mensaje será revisado por el administrador para su aprobación',
            mode: 'confirm',
            confirmText: 'Cerrar',
    cancelButton:false
        })

            const offcanvasEl = document.getElementById('messageBottomSheet');
            bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();

        } catch (error) {
            console.log(error);
            showAlert(
                "Error",
                "No se pudo enviar el mensaje. Intenta de nuevo.",
                "",
                "danger"
            );
        }
            
        }

        
    });
})();

async function submitTribute(form) {
    const memorialSlug =
        document.getElementById('memorial_slug')?.value || window.memorial_slug;

    if (!memorialSlug) {
        throw new Error('Memorial slug not found');
    }

    const payload = {
        author_name: form.author_name.value.trim(),
        message: form.message.value.trim(),
    };

    const response = await fetch(`${api_url}${memorialSlug}/tributes`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        throw new Error('Failed to submit tribute');
    }

    return response.json();
}
const messageInput = document.getElementById('message');
const counter = document.getElementById('count_message');

if (messageInput && counter) {
    messageInput.addEventListener('input', () => {
        counter.textContent = `${messageInput.value.length} / 200`;
    });
}

function switchTheme(){
    const root = document.documentElement; // 👈 html
    const metaThemeColor = document.querySelector('meta[name="theme-color"]');
    function applyThemeColor(isLight) {
        metaThemeColor?.setAttribute(
            "content",
            isLight ? "var(--dark-color)" : "var(--dark-color)"
        );
    }
        root.classList.toggle("theme-light");
        const light = root.classList.contains("theme-light");
        localStorage.setItem("theme", light ? "light" : "dark");
        applyThemeColor(light);
        updateLogos();

}
window.switchTheme=switchTheme;