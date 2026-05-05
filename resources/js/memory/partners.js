let shuffleInstance = null;
let galleryInitialized = false;

document.addEventListener('shown.bs.tab', function (event) {
    const target = event.target.getAttribute('data-bs-target');
    if (target !== '#tab-partners') return;
    const grid_gallery = document.querySelector(".grid-partners");
    if (!grid_gallery) return;
    if (!galleryInitialized) {
        shuffleInstance = new Shuffle(grid_gallery, {
            itemSelector: ".grid-item",
        });

        // ✅ BigPicture (delegated → always works)
        grid_gallery.addEventListener("click", function (e) {
            const img = e.target.closest(".bp-img-partner");
            const video = e.target.closest(".bp-video");

            if (img) {
                BigPicture({
                    el: img,
                    gallery: ".card-image-partner",
                });
            }

            if (video) {
                BigPicture({
                    el: video,
                    vidSrc: video.getAttribute("data-src"),
                });
            }
        });
        galleryInitialized = true;
    }
    requestAnimationFrame(() => {
        setTimeout(() => {
            shuffleInstance.update();
            shuffleInstance.layout();
        }, 50);
    });
});