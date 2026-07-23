import Alpine from 'alpinejs';
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.min.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

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
        range: { min: 0, max: 5000 }
    });

    slider.noUiSlider.on('update', function (values) {
        document.getElementById('min-price').textContent = Math.round(values[0]);
        document.getElementById('max-price').textContent = Math.round(values[1]);
        document.getElementById('min_price').value = Math.round(values[0]);
        document.getElementById('max_price').value = Math.round(values[1]);
    });
});

// FLATPICKR + FULLCALENDAR — rental checkout
document.addEventListener('DOMContentLoaded', function () {

    const startDisplay = document.getElementById('rent_start_display');
    if (!startDisplay) return; // δεν είμαστε στη σελίδα checkout rental

    const rentStartField   = document.getElementById('rent_start');
    const rentEndField     = document.getElementById('rent_end');
    const priceField        = document.getElementById('price');
    const rentalTypeField   = document.getElementById('rental_type_input');
    const priceValue        = document.getElementById('price-value');
    const durationSummary   = document.getElementById('duration-summary');
    const priceSummary      = document.getElementById('price-summary');
    const submitBtn         = document.getElementById('submit-btn');

    const hourMode = document.getElementById('hour-mode');
    const calendarMode = document.getElementById('calendar-mode');
    const durationInput = document.getElementById('rental_duration');
    const calendarEl = document.getElementById('calendar');
    const dayInput = document.getElementById('day_count');
    const dayGroup = document.getElementById('day-count-group');
    const weeksInput = document.getElementById('week_count');
    const weeksGroup = document.getElementById('week-count-group');

    const hourPrice = parseFloat(startDisplay.dataset.hourPrice) || 0;
    const dayPrice  = parseFloat(calendarEl.dataset.dayPrice) || 0;
    const weekPrice = parseFloat(calendarEl.dataset.weekPrice) || 0;

    let selectedType = 'hour';
    let currentStart = new Date(startDisplay.dataset.initialStart);
    let calendarSelection = null;
    let availabilityEvents = [];
    let calendar = null;

    // --- HOUR MODE ---
    const flatpickrInstance = flatpickr(startDisplay, {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        minDate: "today",
        defaultDate: currentStart,
        onChange: function (selectedDates) {
            currentStart = selectedDates[0];
            updateHourPrice();
        }
    });

    durationInput.addEventListener('input', updateHourPrice);

    function updateHourPrice() {

        const hours = parseInt(durationInput.value) || 0;

        const end = new Date(
            currentStart.getTime() + hours * 3600 * 1000
        );

        let unavailable = availabilityEvents.some(event => {

            let eventStart = new Date(event.start);
            let eventEnd   = new Date(event.end);

            return currentStart < eventEnd &&
                end > eventStart;

        });

        if (unavailable) {

            alert('Το ποδήλατο δεν είναι διαθέσιμο αυτή την ώρα.');

            submitBtn.disabled = true;

            priceValue.textContent = '0';
            priceSummary.textContent = '0 €';
            durationSummary.textContent = '-';

            return;

        }

        const price = hours * hourPrice;

        rentStartField.value = currentStart.toISOString();
        rentEndField.value = end.toISOString();
        priceField.value = price;

        priceValue.textContent = price;
        priceSummary.textContent = `${price} €`;
        durationSummary.textContent = `${hours} ώρες`;
        submitBtn.disabled = hours < 1;

    }

    // Φέρνουμε τα unavailable ranges μία φορά, νωρίς, και τα εφαρμόζουμε
    // ΚΑΙ στο flatpickr (hour mode) ΚΑΙ στο calendar (day/week mode).
    fetch(calendarEl.dataset.availabilityUrl)
        .then(response => response.json())
        .then(data => {

            availabilityEvents = data;

            flatpickrInstance.set('disable', data.map(ev => ({
                from: ev.start,
                to: ev.end
            })));

            updateHourPrice();

        });

    // --- CALENDAR (day / week) --- lazy init, ώστε να μη ζωγραφίζεται
    // ενώ ο container είναι display:none (σπάει τα background events/μεγέθη)
    function initCalendar() {
        if (calendar) return;

        calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            selectable: true,
            selectOverlap: false,

            events: function(fetchInfo, successCallback, failureCallback) {

                fetch(calendarEl.dataset.availabilityUrl)
                    .then(response => response.json())
                    .then(data => {

                        successCallback(
                            data.map(event => ({
                                id: 'blocked-' + event.start,
                                title: event.title || 'Κρατημένο',
                                start: event.start,
                                end: event.end,
                                allDay: true,               // <-- ΤΟ ΒΑΣΙΚΟ FIX: χωρίς αυτό, background events με ώρα στο start/end δεν "γεμίζουν" σωστά ολόκληρες τις ημέρες στο dayGridMonth
                                display: 'background',
                                backgroundColor: '#ffcccc',
                                classNames: ['blocked-date']
                            }))
                        );

                    })
                    .catch(error => {
                        console.error(error);
                        failureCallback(error);
                    });

            },

            // ΑΦΑΙΡΕΘΗΚΑΝ eventContent / eventDidMount που έκαναν μόνο console.log
            // (ήταν debug κώδικας — δεν χρειάζονται για background events)

            selectAllow: function (selection) {

                let start = selection.start;
                let end   = selection.end;

                let blocked = false;

                calendar.getEvents().forEach(event => {

                    if (event.display === 'background') {

                        let eventStart = event.start;
                        let eventEnd   = event.end;

                        if (start < eventEnd && end > eventStart) {

                            blocked = true;

                        }

                    }

                });

                return !blocked;

            },

            // DAY MODE: drag-select όσες μέρες θέλει ο χρήστης
            select: function (selection) {

                if (selectedType !== 'day') return;

                const start = selection.start;

                const days = parseInt(dayInput.value) || 1;

                const end = new Date(start);
                end.setDate(end.getDate() + days);

                calendarSelection = { start, end };

                calendar.getEvents()
                    .filter(e => e.id === 'day-preview')
                    .forEach(e => e.remove());

                calendar.addEvent({
                    id: 'day-preview',
                    title: `${days} day(s)`,
                    start,
                    end,
                    allDay: true,
                    color: '#198754'
                });

                updateCalendarPrice();
            },

            // WEEK MODE: ένα κλικ κλειδώνει αυτόματα 7 μέρες από εκείνη την ημέρα
            dateClick: function (info) {
                if (selectedType !== 'week') return;

                const start = info.date;
                const weeks = parseInt(weeksInput.value) || 1;

                const end = new Date(start);
                end.setDate(end.getDate() + (weeks * 7));

                calendarSelection = { start, end };

                // οπτική επιβεβαίωση: highlight το 7ήμερο block
                calendar.getEvents().filter(e => e.id === 'week-preview').forEach(e => e.remove());
                calendar.addEvent({
                    id: 'week-preview',
                    title: 'One Full Week Selected',
                    start,
                    end,
                    allDay: true,
                    color: '#0d6efd'
                });

                updateCalendarPrice();
            }
        });

        calendar.render();
    }

    weeksInput.addEventListener('input', () => {

        if (selectedType !== 'week') return;

        if (!calendarSelection) return;

        const start = calendarSelection.start;

        const weeks = parseInt(weeksInput.value) || 1;

        const end = new Date(start);
        end.setDate(end.getDate() + weeks * 7);

        calendarSelection = { start, end };

        calendar.getEvents()
            .filter(e => e.id === 'week-preview')
            .forEach(e => e.remove());

        calendar.addEvent({
            id: 'week-preview',
            title: `${weeks} week(s)`,
            start,
            end,
            allDay: true,
            color: '#0d6efd'
        });

        updateCalendarPrice();

    });

    dayInput.addEventListener('input', () => {

        if (selectedType !== 'day') return;
        if (!calendarSelection) return;

        const start = calendarSelection.start;

        const days = parseInt(dayInput.value) || 1;

        const end = new Date(start);
        end.setDate(end.getDate() + days);

        calendarSelection = { start, end };

        calendar.getEvents()
            .filter(e => e.id === 'day-preview')
            .forEach(e => e.remove());

        calendar.addEvent({
            id: 'day-preview',
            title: `${days} day(s)`,
            start,
            end,
            allDay: true,
            color: '#198754'
        });

        updateCalendarPrice();

    });

    function clearCalendarSelection() {

        calendarSelection = null;

        // Σβήσε όλα τα preview events
        if (calendar) {
            calendar.getEvents().forEach(event => {
                if (
                    event.id === 'week-preview' ||
                    event.id === 'day-preview'
                ) {
                    event.remove();
                }
            });
        }

        // Καθάρισε hidden inputs
        rentStartField.value = '';
        rentEndField.value = '';
        priceField.value = '';

        // Καθάρισε summary
        durationSummary.textContent = '-';
        priceSummary.textContent = '0 €';
        priceValue.textContent = '0';

        submitBtn.disabled = true;
    }

    function updateCalendarPrice() {
        if (!calendarSelection) return;

        const start = calendarSelection.start;
        const end = calendarSelection.end;

        let units, price, label;
        if (selectedType === 'week') {

            units = parseInt(weeksInput.value) || 1;

            price = units * weekPrice;

            label = `${units} εβδομάδες`;
        } else {
            units = parseInt(dayInput.value) || 1;

            price = units * dayPrice;

            label = `${units} ημέρες`;
        }

        rentStartField.value = start.toISOString();
        rentEndField.value = end.toISOString();
        priceField.value = price;

        priceValue.textContent = price;
        priceSummary.textContent = `${price} €`;
        durationSummary.textContent = label;
        submitBtn.disabled = false;
    }

    // --- RADIO SWITCH ---
    document.querySelectorAll('input[name="rental_type_radio"]').forEach(radio => {
        radio.addEventListener('change', e => {
            selectedType = e.target.value;
            rentalTypeField.value = selectedType;

            // reset προηγούμενης επιλογής όταν αλλάζει mode, ώστε να μη μείνει "κολλημένη" λάθος τιμή
            clearCalendarSelection();

            if (selectedType === 'hour') {
                hourMode.style.display = '';
                dayGroup.style.display = 'none';
                weeksGroup.style.display = 'none';
                calendarMode.style.display = 'none';
                updateHourPrice();
            } else {
                hourMode.style.display = 'none';
                calendarMode.style.display = '';
                dayGroup.style.display  = selectedType === 'day'  ? '' : 'none';
                weeksGroup.style.display = selectedType === 'week' ? '' : 'none';

                initCalendar();       // τώρα που το container είναι ήδη ορατό
                calendar.updateSize(); // πάντα, όχι μόνο πρώτη φορά
                submitBtn.disabled = true;
            }
        });
    });

    // αρχικοποίηση σε hour mode
    updateHourPrice();
});
