export function initImageUploadPreview() {
    const imageUpload = document.getElementById('imageUpload');
    if (!imageUpload) return;

    let selectedFiles = [];

    imageUpload.addEventListener('change', function (e) {
        selectedFiles = Array.from(e.target.files).slice(0, 5);
        renderImagePreviews();
    });

    function renderImagePreviews() {
        const preview = document.getElementById('imagePreview');
        if (!preview) return;
        preview.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${ev.target.result}" class="w-full h-24 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                    <button type="button" data-index="${index}" class="remove-img-btn absolute top-1 right-1 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-rose-600">×</button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-img-btn')) {
            const index = parseInt(e.target.getAttribute('data-index'));
            selectedFiles.splice(index, 1);

            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imageUpload.files = dataTransfer.files;

            renderImagePreviews();
        }
    });
}