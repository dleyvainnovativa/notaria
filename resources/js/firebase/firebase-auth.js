import {
    signInWithEmailAndPassword,
    signInWithPopup,
    GoogleAuthProvider,
    signOut,
    createUserWithEmailAndPassword,
    updateProfile
} from "firebase/auth";

import {
    auth
} from "./firebase-init";
import {
    sendTokenToBackend
} from "./firebase-token";

export async function loginWithEmail(email, password) {
    const result = await signInWithEmailAndPassword(auth, email, password);
    await sendTokenToBackend(result.user);
    // window.location.href = `${admin_url}`;
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');

    if (redirect) {
        window.location.href = `${admin_url}${redirect}`;
    } else {
        window.location.href = `${admin_url}`;
    }
}

export async function registerWithEmail(name, email, password) {
    const result = await createUserWithEmailAndPassword(auth, email, password);
    await updateProfile(result.user, {
        displayName: name
    });
    await sendTokenToBackend(result.user);
    // window.location.href = `${admin_url}`;
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');

    if (redirect) {
        window.location.href = `${admin_url}${redirect}`;
    } else {
        window.location.href = `${admin_url}`;
    }
}

export async function loginWithGoogle() {
    const provider = new GoogleAuthProvider();
    const result = await signInWithPopup(auth, provider);
    await sendTokenToBackend(result.user);
    // window.location.href = `${admin_url}`;
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');

    if (redirect) {
        window.location.href = `${admin_url}${redirect}`;
    } else {
        window.location.href = `${admin_url}`;
    }
}

export async function logoutUser() {
    await signOut(auth);

    await fetch(`${app_url}logout`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": window.csrf
        }
    });

    window.location.href = `${app_url}login`;
}
