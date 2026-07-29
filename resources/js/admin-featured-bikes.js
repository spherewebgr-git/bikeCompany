// resources/js/admin-featured-bikes.js
import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const availableList = document.getElementById('available-list');
    const selectedList  = document.getElementById('selected-list');
    const hiddenInput    = document.getElementById('bike-ids-input');
    const countLabel     = document.getElementById('selected-count');
    const form           = document.getElementById('featured-form');

    const MAX = 6;

    function syncState() {
        const ids = [...selectedList.querySelectorAll('.bike-sort-item')].map(el => el.dataset.id);
        hiddenInput.value = ids.join(',');
        countLabel.textContent = ids.length;
        selectedList.classList.toggle('is-full', ids.length >= MAX);
    }

    new Sortable(availableList, {
        group: 'bikes',
        animation: 150,
        onAdd: syncState,
    });

    new Sortable(selectedList, {
        group: 'bikes',
        animation: 150,
        filter: '.remove-bike-btn',
        preventOnFilter: false,
        onMove: function (evt) {
            // Επιτρέπουμε πάντα reordering μέσα στη λίστα,
            // μπλοκάρουμε μόνο προσθήκη νέου στοιχείου όταν είναι ήδη γεμάτη
            const isReorder = evt.from === evt.to;
            if (!isReorder && selectedList.children.length >= MAX) {
                return false;
            }
        },
        onAdd: function (evt) {
            if (selectedList.children.length > MAX) {
                availableList.appendChild(evt.item);
            }
            syncState();
        },
        onUpdate: syncState,
    });

    // Αφαίρεση bike από τα featured με κλικ στο κουμπί ×
    selectedList.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-bike-btn');
        if (!btn) return;

        const li = btn.closest('.bike-sort-item');
        if (!li) return;

        availableList.insertBefore(li, availableList.firstChild);
        syncState();
    });

    form.addEventListener('submit', (e) => {
        if (!hiddenInput.value) {
            e.preventDefault();
            alert('Επίλεξε τουλάχιστον 1 ποδήλατο.');
        }
    });

    syncState();
});
