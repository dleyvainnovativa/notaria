export async function sendTokenToBackend(user) {
    const token = await user.getIdToken(true);
    console.log("token to send");
    localStorage.setItem("selahi_auth_token", token);
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    await fetch(`${app_url}auth/firebase`, {
        method: "POST",
        credentials: "same-origin", // 👈🔥 CLAVE
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf,
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ token })
    });
}
