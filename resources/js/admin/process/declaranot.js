const exportButton = document.getElementById('export_button');

document.querySelectorAll('.upload-wrapper').forEach(wrapper => {
    console.log(wrapper);
    const dropZone = wrapper.querySelector('.dropZone');
    const exportButton = wrapper.querySelector('.export_button');
    const fileInput = wrapper.querySelector('.documentInput');
    const fileName = wrapper.querySelector('.fileName');

    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
        handleFile(e.target.files[0], wrapper);
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-primary', 'bg-light');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.classList.remove('border-primary', 'bg-light');
    });

    dropZone.addEventListener('drop', (e) => {

        const file = e.dataTransfer.files[0];

        if (file) {
            fileInput.files = e.dataTransfer.files;
            handleFile(file, wrapper);
        }

    });

});
function handleFile(file, wrapper) {

    const type = wrapper.dataset.type;

    const fileName = wrapper.querySelector('.fileName');

    fileName.textContent = file.name;
    fileName.classList.remove('d-none');

    console.log(type);

}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('create_form');
    form.addEventListener('submit', async event => {
        if (await validateForm(form, event)) {
            const submitButton = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (data[key]) {
                    data[key] = [].concat(data[key], value);
                } else {
                    data[key] = value;
                }
            });
            await manualNavigate("tab-process");
            let response = await postRequestFormData('declaranot/extract', formData);
            await setButtonLoading(submitButton, false);
            // console.log("Button Loading false");
            if (response) {
                let schema = response.data.schema;
                let result = response.data.json;
                renderExtractedData(result, "auto-build", schema);
                await manualNavigate("tab-result");
            } else {
                await manualNavigate("tab-info");
            }
        }
    }, false);
});


exportButton.addEventListener('click', async () => {
    let extract =  extractFormData("auto-build");
    await setButtonLoading(exportButton, true);
    let payload = {"data" : extract};
    let response = await postRequest('declaranot/export', payload);
    if (response) {

    const text = response.data.text;
    const fileName = response.data.fileName;

    // Create TXT blob
    const blob = new Blob([text], { type: 'text/plain' });

    // Create temporary URL
    const url = window.URL.createObjectURL(blob);

    // Create download link
    const a = document.createElement('a');
    a.href = url;
    a.download = fileName;

    // Trigger download
    document.body.appendChild(a);
    a.click();

    // Cleanup
    a.remove();
    window.URL.revokeObjectURL(url);

}
    await setButtonLoading(exportButton, false);



});