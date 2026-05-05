document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target.closest("form");
    const isValid = form.checkValidity();
    form.classList.add('was-validated');
    if (isValid) {
    // const form = e.target;
    const formData = new FormData(form);

    const payload = {
        deceased_name: formData.get('deceased_name'),
        user_name: formData.get('user_name'),
        user_email: formData.get('user_email'),
    };

    try {
        const response = await fetch(`${api_url}checkout/memorial`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrf,
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            throw new Error('Error al crear la sesión de pago');
        }

        const data = await response.json();

        // 🔥 Stripe redirect
        window.location.href = data.url;

    } catch (err) {
        // alert('No se pudo iniciar el pago. Intenta nuevamente.');
        console.error(err);
    }
    }
});

