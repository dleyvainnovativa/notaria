 let modalEl = document.getElementById("privacyModal");
 let bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

 
     const infoForm = document.getElementById('info-form');
     //  if (!infoForm) return;
     const privacyForm = document.getElementById('privacy-form');
     //  if (!privacyForm) return;

     console.log(infoForm);
     console.log(privacyForm);

     infoForm.addEventListener('submit', async function (event) {
         event.preventDefault(); // always prevent native submit
         const submitButton = infoForm.querySelector('button[type="submit"]');
         setButtonLoading(submitButton, true);
         if (!infoForm.checkValidity()) {
             event.stopPropagation();
             await setButtonLoading(submitButton, false);
         } else {

             await updateMemorialInfo();
             await setButtonLoading(submitButton, false);

         }
         infoForm.classList.add('was-validated');
     }, false);
     privacyForm.addEventListener('submit', async function (event) {
         const submitButton = privacyForm.querySelector('button[type="submit"]');
         setButtonLoading(submitButton, true);
         event.preventDefault(); // always prevent native submit
         if (!privacyForm.checkValidity()) {
             event.stopPropagation();
             await setButtonLoading(submitButton, false);
         } else {
             const password = document.getElementById('privacy_password').value;
             const is_public = document.getElementById('privacy_is_public').value == "true" ? 1 : 0;
             try {
     const token = localStorage.getItem('selahi_auth_token');
                 const payload = {
                     access_password: password,
                     is_public: is_public,
                 };
                 const response = await fetch(`${api_url}${memorial_slug}/privacy`, {
                     method: 'PUT',
                     headers: {
                         'Accept': 'application/json',
                         'Content-Type': 'application/json',
                         'Authorization': `Bearer ${token}`
                     },
                     body: JSON.stringify(payload)
                 });

                 if (!response.ok) {
                     setButtonLoading(submitButton, false);
                     const data = await response.json();
                     showAlert("No se logró enviar la solicitud", data.message, "", "danger");
                     return;
                 }
                 const data = await response.json();
                 console.log(data);
                 setButtonLoading(submitButton, false);
                 if (data.success) {
                    initRequest();
                    showAlert("La página ha cambiado de estatus", data.message, "", "success");
                    bsModal.hide();
                    privacyForm.reset();
                 } else {
                     showAlert("No se logró enviar la solicitud", data.message, "", "danger");
                 }

             } catch (error) {
                 console.log(error);
                 setButtonLoading(submitButton, false);
                 showAlert("No se logró enviar la solicitud", "Intente de nuevo", "", "danger");
                 return error;
             }

         }
         privacyForm.classList.add('was-validated');
     }, false);


 function initRequest(params) {
     const token = localStorage.getItem('selahi_auth_token');
     if (!token) {
         return;
     }
     fetch(`${api_url}${memorial_slug}/info`, {
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
             buildInfoView(data);

         })
         .catch(error => {
             console.error(error);
         });
 }

 function buildInfoView(memorial) {

     // Imagen de perfil
     const profileImg = document.querySelector('#profile-photo');
     profileImg.src = memorial.profile_image_url ?
         `${app_url}${memorial.profile_image_url}` :
         ``;

     // Inputs
     document.getElementById('deceased_name').value = memorial.deceased_name ?? '';
     document.getElementById('birthday').value = formatDate(memorial.birth_date);
     document.getElementById('deathday').value = formatDate(memorial.death_date);
     document.getElementById('playlist').value = memorial.playlist ?? '';
     document.getElementById('epitaph').value = memorial.biography ?? '';
     document.getElementById('privacy_is_public').value = !memorial.is_public;
     
     document.getElementById('privacy_icon').className = memorial.is_public_icon;
     document.getElementById('privacy_title').textContent = memorial.is_public_title;
     document.getElementById('privacy_text').textContent = memorial.is_public_text;

     if(!memorial.is_public){
        document.getElementById('privacy_password_container').classList.add("d-none");
    }else{
         document.getElementById('privacy_password_container').classList.remove("d-none");
     }

     // Estado público / privado
     const privacyBtn = document.querySelector('#privacy_btn');

     if (memorial.is_public) {
         privacyBtn.textContent = 'Cambiar a Privado';
         privacyBtn.classList.remove('btn-outline-success');
         privacyBtn.classList.add('btn-outline-danger');
     } else {
         privacyBtn.textContent = 'Cambiar a Público';
         privacyBtn.classList.remove('btn-outline-danger');
         privacyBtn.classList.add('btn-outline-success');
     }
     window.currentMemorial = memorial;
 }

 function openPrivacy() {
     bsModal.show();
 }

 window.openPrivacy = openPrivacy

 function formatDate(date) {
     if (!date) return '';
     return date.split('T')[0];
 }

 async function updateMemorialInfo() {
     const token = localStorage.getItem('selahi_auth_token');
     if (!token) return;

     const payload = {
         deceased_name: deceased_name.value,
         biography: epitaph.value,
         birth_date: birthday.value || null,
         death_date: deathday.value || null,
         playlist: playlist.value || null
     };

     try {
         const response = await fetch(`${api_url}${memorial_slug}/info`, {
             method: 'PUT',
             headers: {
                 'Accept': 'application/json',
                 'Content-Type': 'application/json',
                 'Authorization': `Bearer ${token}`
             },
             body: JSON.stringify(payload)
         });

         const data = await response.json();
         if (!response.ok) {
             handleApiError(response.status, data);
             return;
         }

         showAlert("Perfil actualizado", "Se han actualizado correctamente los datos", "", "success")
         return data;

     } catch (error) {
         showAlert("Ha ocurrido un error", "No se han actualizado correctamente los datos, intente de nuevo", "", "danger")

         return error;
     }
 }

 document.querySelector(".profile-image-wrapper").addEventListener("click", () => {
     document.getElementById("profileImageInput").click();
 });

 const profileInput = document.getElementById("profileImageInput");
 const profilePhoto = document.getElementById("profile-photo");

 profileInput.addEventListener("change", async () => {
     if (!profileInput.files.length) return;

     const file = profileInput.files[0];
     const token = localStorage.getItem('selahi_auth_token');

     // Instant preview
     const previewUrl = URL.createObjectURL(file);
     profilePhoto.src = previewUrl;
     const formData = new FormData();
     formData.append("photo", file);
     try {
         profilePhoto.classList.add("shimmer");

         const response = await fetch(
             `${api_url}${memorial_slug}/photo`, {
                 method: "POST",
                 headers: {
                     'Accept': 'application/json',
                     'Authorization': `Bearer ${token}`,
                     "X-CSRF-TOKEN": document
                         .querySelector('meta[name="csrf-token"]')
                         .getAttribute("content")
                 },
                 body: formData
             }
         );

         const data = await response.json();

         if (!response.ok) {
             throw data;
         }

         profilePhoto.src = `${app_url}${data.image_url}`;

         showAlert(
             "Foto actualizada",
             "La imagen se ha guardado correctamente",
             "",
             "success"
         );

     } catch (error) {
         showAlert(
             "Error",
             error.message || "No se pudo subir la imagen",
             "",
             "danger"
         );
     } finally {
         profilePhoto.classList.remove("shimmer");
     }
 });




 initRequest();
