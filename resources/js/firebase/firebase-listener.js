import { onIdTokenChanged } from "firebase/auth";
import { auth } from "./firebase-init";
import { sendTokenToBackend } from "./firebase-token";

let syncing = false;

onIdTokenChanged(auth, async (user) => {
    if (!user || syncing) return;

    const token = await user.getIdToken();
    const lastToken = localStorage.getItem("selahi_auth_token");
    if (token === lastToken) return;
    syncing = true;
    try {
        console.log("token to send: Listener");
        await sendTokenToBackend(user);
    } finally {
        syncing = false;
    }
});
