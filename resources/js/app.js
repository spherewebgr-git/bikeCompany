

import Alpine from 'alpinejs';
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.min.css';

window.Alpine = Alpine;

Alpine.start();

// Price filter slider
document.addEventListener('DOMContentLoaded', () => {

    const slider = document.getElementById('price-slider');

    if (!slider) return;

    noUiSlider.create(slider, {
        start: [
            Number(document.getElementById('min_price').value),
            Number(document.getElementById('max_price').value)
        ],
        connect: true,
        step: 50,
        range: {
            min: 0,
            max: 5000
        }
    });

    slider.noUiSlider.on('update', function(values) {

        document.getElementById('min-price').textContent = Math.round(values[0]);
        document.getElementById('max-price').textContent = Math.round(values[1]);

        document.getElementById('min_price').value = Math.round(values[0]);
        document.getElementById('max_price').value = Math.round(values[1]);
    });

});
