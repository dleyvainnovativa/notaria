let shuffleInstance = null;
let galleryInitialized = false;

document.addEventListener('shown.bs.tab', function (event) {
    const target = event.target.getAttribute('data-bs-target');

    // 🎯 Only react to gallery tab
    if (target !== '#tab-gallery') return;

    const grid_gallery = document.querySelector(".grid-gallery");
    if (!grid_gallery) return;

    // ✅ INIT ONLY ONCE
    if (!galleryInitialized) {
        shuffleInstance = new Shuffle(grid_gallery, {
            itemSelector: ".grid-item",
        });

        // ✅ BigPicture (delegated → always works)
        grid_gallery.addEventListener("click", function (e) {
            const img = e.target.closest(".bp-img");
            const video = e.target.closest(".bp-video");

            if (img) {
                BigPicture({
                    el: img,
                    gallery: ".card-image",
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

    // ✅ FORCE layout AFTER tab becomes visible
    requestAnimationFrame(() => {
        setTimeout(() => {
            shuffleInstance.update();
            shuffleInstance.layout();
        }, 50);
    });
});