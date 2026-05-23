document.querySelectorAll('[data-next],[data-prev]').forEach(btn => {
    btn.addEventListener('click', e => {
        const targetId = btn.dataset.next || btn.dataset.prev;
        const triggerEl = document.getElementById(targetId);
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();
        window.scrollTo(0, 0);
    });
});

async function manualNavigate(targetId){
  const triggerEl = document.getElementById(targetId);
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();
        window.scrollTo(0, 0);
}

window.manualNavigate=manualNavigate;