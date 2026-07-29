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
        onAdd: function () {
            syncState();
        }
    });

    new Sortable(selectedList, {
        group: 'bikes',
        animation: 150,
        onMove: function (evt) {
            // Επιτρέπουμε πάντα reordering μέσα στη λίστα,
            // μπλοκάρουμε μόνο προσθήκη νέου στοιχείου όταν είναι ήδη γεμάτη
            const isReorder = evt.from === evt.to;

        },
        onAdd: function (evt) {
            if (selectedList.children.length > MAX) {
                availableList.appendChild(evt.item);
            }
            syncState();
        },
        onUpdate: syncState,
    });

    form.addEventListener('submit', (e) => {
        if (!hiddenInput.value) {
            e.preventDefault();
            alert('Choose at least 1 bike to show');
        }
    });

    syncState();
});
