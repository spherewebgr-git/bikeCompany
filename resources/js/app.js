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
    if (!startDisplay) return;

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
    let calendarSelection = null; // { start: 'YYYY-MM-DD', end: 'YYYY-MM-DD' } (end exclusive)
    let availabilityEvents = [];
    let calendar = null;

    // ---------- Date-string helpers (καμία μετατροπή timezone) ----------
    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function toDateStr(y, m, d) {
        return `${y}-${pad(m)}-${pad(d)}`;
    }

    // Προσθέτει μέρες σε ένα 'YYYY-MM-DD' string χρησιμοποιώντας UTC ώστε
    // να μην επηρεάζεται από DST/local timezone — δουλεύουμε καθαρά σε επίπεδο ημερολογιακής ημέρας.
    function addDaysToDateStr(dateStr, days) {
        const [y, m, d] = dateStr.split('-').map(Number);
        const utcMs = Date.UTC(y, m - 1, d) + days * 86400000;
        const dt = new Date(utcMs);
        return toDateStr(dt.getUTCFullYear(), dt.getUTCMonth() + 1, dt.getUTCDate());
    }
    // Κάνει parse ένα 'YYYY-MM-DD' ή 'YYYY-MM-DD HH:MM:SS' (ή με 'T') σαν LOCAL time,
    // ώστε να μη μπερδεύεται ποτέ με το UTC parsing που κάνει native το new Date(string).
    function parseLocalDateTime(str) {
        const [datePart, timePart] = str.split(/[T ]/);
        const [y, m, d] = datePart.split('-').map(Number);
        if (!timePart) return new Date(y, m - 1, d); // date-only -> local μεσάνυχτα
        const [hh, mm, ss] = timePart.split(':').map(Number);
        return new Date(y, m - 1, d, hh || 0, mm || 0, ss || 0);
    }

    function renderHourAvailability(dateObj) {
        const container = document.getElementById('hour-availability-bar');
        if (!container) return;

        container.innerHTML = '';

        const track = document.createElement('div');
        track.className = 'hour-track';

        const labels = document.createElement('div');
        labels.className = 'hour-track-labels';

        for (let h = 8; h < 21; h++) {
            const slotStart = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate(), h, 0, 0);
            const slotEnd   = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate(), h + 1, 0, 0);

            const isBlocked = availabilityEvents.some(event => {
                const evStart = parseLocalDateTime(event.start);
                const evEnd   = parseLocalDateTime(event.end);
                return slotStart < evEnd && slotEnd > evStart;
            });

            const segment = document.createElement('div');
            segment.className = 'hour-segment ' + (isBlocked ? 'hour-segment-blocked' : 'hour-segment-available');
            segment.title = `${String(h).padStart(2, '0')}:00 - ${String(h + 1).padStart(2, '0')}:00 — ${isBlocked ? 'Κλεισμένο' : 'Διαθέσιμο'}`;
            track.appendChild(segment);

            // label κάθε 2 ώρες, ώστε να μη στριμώχνονται
            const label = document.createElement('span');
            label.className = 'hour-track-label';
            label.textContent = (h % 2 === 0) ? `${String(h).padStart(2, '0')}h` : '';
            labels.appendChild(label);
        }

        container.appendChild(track);
        container.appendChild(labels);
    }


    function formatDateStr(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }

    function lastInclusiveDayStr(endStr) {
        return addDaysToDateStr(endStr, -1);
    }

    // Ελέγχει αν το [startStr, endStr) (end exclusive) επικαλύπτεται με
    // κάποιο μπλοκαρισμένο διάστημα από τα availabilityEvents.
    function isRangeBlocked(startStr, endStr) {
        return availabilityEvents.some(event => {
            const evStart = (event.start || '').slice(0, 10);
            const evEndRaw = (event.end || '').slice(0, 10);
            const evEnd = (evEndRaw === evStart) ? addDaysToDateStr(evStart, 1) : evEndRaw;

            return startStr < evEnd && endStr > evStart;
        });
    }

    // ---------------- HOUR MODE ----------------
    const flatpickrInstance = flatpickr(startDisplay, {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        minDate: "today",
        defaultDate: currentStart,
        onChange: function (selectedDates) {
            userInteracted = true;
            currentStart = selectedDates[0];
            updateHourPrice();
        },
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dayStart = new Date(dayElem.dateObj);
            dayStart.setHours(0, 0, 0, 0);

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Περασμένες μέρες: καμία κλάση διαθεσιμότητας, μένουν ουδέτερες
            if (dayStart < today) {
                return;
            }

            const dayEnd = new Date(dayStart);
            dayEnd.setDate(dayEnd.getDate() + 1);

            let blockedHours = 0;

            for (let h = 0; h < 24; h++) {
                const slotStart = new Date(dayStart.getFullYear(), dayStart.getMonth(), dayStart.getDate(), h, 0, 0);
                const slotEnd   = new Date(dayStart.getFullYear(), dayStart.getMonth(), dayStart.getDate(), h + 1, 0, 0);

                const isSlotBlocked = availabilityEvents.some(event => {
                    const eventStart = parseLocalDateTime(event.start);
                    const eventEnd   = parseLocalDateTime(event.end);
                    return slotStart < eventEnd && slotEnd > eventStart;
                });

                if (isSlotBlocked) blockedHours++;
            }

            if (blockedHours === 24) {
                dayElem.classList.add('flatpickr-day-reserved');
            } else if (blockedHours > 0) {
                dayElem.classList.add('flatpickr-day-partial');
            } else {
                dayElem.classList.add('flatpickr-day-available');
            }
        }
    });

    durationInput.addEventListener('input', () => {
        userInteracted = true;
        updateHourPrice();
    });

    let userInteracted = false; // true μόνο όταν ο χρήστης αλλάξει ημερομηνία/διάρκεια
    function updateHourPrice() {
        renderHourAvailability(currentStart);

        const hours = parseInt(durationInput.value) || 0;
        const end = new Date(currentStart.getTime() + hours * 3600 * 1000);

        const unavailable = availabilityEvents.some(event => {
            const eventStart = parseLocalDateTime(event.start);
            const eventEnd   = parseLocalDateTime(event.end);
            return currentStart < eventEnd && end > eventStart;
        });

        if (unavailable) {
            if (userInteracted) {
                alert('Το ποδήλατο δεν είναι διαθέσιμο αυτή την ώρα.');
            }
            submitBtn.disabled = true;
            priceValue.textContent = '0 €';
            priceSummary.textContent = '0 €';
            durationSummary.textContent = '-';
            return;
        }

        const price = hours * hourPrice;

        rentStartField.value = flatpickr.formatDate(currentStart, "Y-m-d H:i:S");
        rentEndField.value   = flatpickr.formatDate(end, "Y-m-d H:i:S");
        priceField.value = price;

        console.log('START:', rentStartField.value);
        console.log('END:', rentEndField.value);

        priceValue.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        priceSummary.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        durationSummary.textContent = `${hours} ώρες`;
        submitBtn.disabled = hours < 1;
    }

    console.log('Checkout JS loaded');
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

    // ---------------- CALENDAR (day / week) ----------------
    function initCalendar() {
        if (calendar) return;

        calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            validRange: {
                start: new Date().toISOString().slice(0, 10)
            },

            events: function (fetchInfo, successCallback, failureCallback) {
                fetch(calendarEl.dataset.availabilityUrl)
                    .then(response => response.json())
                    .then(data => {
                        availabilityEvents = data;

                        successCallback(
                            data.map(event => ({
                                id: 'blocked-' + event.start,
                                title: event.title || 'Κρατημένο',
                                start: event.start,
                                end: event.end,
                                allDay: true,
                                display: 'background',
                                classNames: ['blocked-date']
                            }))
                        );
                    })
                    .catch(error => {
                        console.error(error);
                        failureCallback(error);
                    });
            },

            // Τρέχει αυτόματα σε ΚΑΘΕ render (αρχικό + κάθε αλλαγή μήνα) —
            // δεν χρειάζεται κανένα χειροκίνητο re-render.
            dayCellClassNames: function (arg) {
                const dateStr =
                    arg.date.getFullYear() + '-' +
                    String(arg.date.getMonth()+1).padStart(2,'0') + '-' +
                    String(arg.date.getDate()).padStart(2,'0');

                const isBlocked = availabilityEvents.some(event => {
                    const evStart = (event.start || '').slice(0, 10);
                    const evEndRaw = (event.end || '').slice(0, 10);

                    // Αν start/end είναι η ίδια μέρα (κράτηση σε ώρες),
                    // θεωρούμε ότι μπλοκάρει ολόκληρη τη μέρα στο calendar ημερών/εβδομάδων
                    const evEnd = (evEndRaw === evStart) ? addDaysToDateStr(evStart, 1) : evEndRaw;

                    return dateStr >= evStart && dateStr < evEnd;
                });

                return isBlocked ? ['day-cell-blocked'] : [];
            },

            dateClick: function (info) {
                if (selectedType !== 'day' && selectedType !== 'week') return;

                const todayStr = new Date().toISOString().slice(0, 10);
                if (info.dateStr < todayStr) {
                    alert('Δεν μπορείτε να επιλέξετε ημερομηνία πριν από σήμερα.');
                    return;
                }

                const startStr = info.dateStr; // 'YYYY-MM-DD', local ημερολογιακή μέρα, καμία μετατροπή

                const units = selectedType === 'week'
                    ? (parseInt(weeksInput.value) || 1)
                    : (parseInt(dayInput.value) || 1);

                const totalDays = selectedType === 'week' ? units * 7 : units;
                const endStr = addDaysToDateStr(startStr, totalDays);

                if (isRangeBlocked(startStr, endStr)) {
                    alert('Το ποδήλατο δεν είναι διαθέσιμο για κάποια από τις επιλεγμένες ημέρες.');
                    clearCalendarSelection();
                    return;
                }

                calendarSelection = { start: startStr, end: endStr };
                renderPreviewEvent();
                updateCalendarPrice();
            },
        });

        calendar.render();
    }

    function renderPreviewEvent() {
        if (!calendarSelection || !calendar) return;

        calendar.getEvents()
            .filter(e => e.id === 'range-preview')
            .forEach(e => e.remove());

        const lastDay = lastInclusiveDayStr(calendarSelection.end);
        const isWeek = selectedType === 'week';

        calendar.addEvent({
            id: 'range-preview',
            title: `${formatDateStr(calendarSelection.start)} - ${formatDateStr(lastDay)}`,
            start: calendarSelection.start,
            end: calendarSelection.end,
            allDay: true,
            classNames: [isWeek ? 'week-selection' : 'day-selection']
        });
    }

    // Όταν αλλάζει ο αριθμός ημερών/εβδομάδων ΑΦΟΥ έχει ήδη γίνει click σε
    // ημερομηνία, ξαναϋπολογίζουμε το τέλος από το ίδιο start.
    function recalcFromExistingStart() {
        if (!calendarSelection) return;

        const units = selectedType === 'week'
            ? (parseInt(weeksInput.value) || 1)
            : (parseInt(dayInput.value) || 1);

        const totalDays = selectedType === 'week' ? units * 7 : units;
        const newEnd = addDaysToDateStr(calendarSelection.start, totalDays);

        if (isRangeBlocked(calendarSelection.start, newEnd)) {
            alert('Το ποδήλατο δεν είναι διαθέσιμο για τόσες μέρες/εβδομάδες από αυτή την ημερομηνία.');
            clearCalendarSelection();
            return;
        }

        calendarSelection.end = newEnd;
        renderPreviewEvent();
        updateCalendarPrice();
    }

    weeksInput.addEventListener('input', () => {
        if (selectedType !== 'week') return;
        recalcFromExistingStart();
    });

    dayInput.addEventListener('input', () => {
        if (selectedType !== 'day') return;
        recalcFromExistingStart();
    });

    function clearCalendarSelection() {
        calendarSelection = null;

        if (calendar) {
            calendar.getEvents()
                .filter(e => e.id === 'range-preview')
                .forEach(e => e.remove());
        }

        rentStartField.value = '';
        rentEndField.value = '';
        priceField.value = '';

        durationSummary.textContent = '-';
        priceSummary.textContent = '0 €';
        priceValue.textContent = '0 €';

        submitBtn.disabled = true;
    }

    function updateCalendarPrice() {
        if (!calendarSelection) return;

        const { start, end } = calendarSelection;
        const lastDay = lastInclusiveDayStr(end);

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

        // Καθαρά ημερολογιακά strings, χωρίς toISOString() — έτσι δεν
        // υπάρχει καμία απολύτως μετατόπιση timezone στο backend.
        rentStartField.value = start;
        rentEndField.value = end;
        priceField.value = price;

        priceValue.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        priceSummary.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        durationSummary.textContent = `${label} (${formatDateStr(start)} - ${formatDateStr(lastDay)})`;
        submitBtn.disabled = false;
    }

    // ---------------- RADIO SWITCH ----------------
    document.querySelectorAll('input[name="rental_type_radio"]').forEach(radio => {
        radio.addEventListener('change', e => {
            selectedType = e.target.value;
            rentalTypeField.value = selectedType;

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

                initCalendar();
                calendar.updateSize();
                submitBtn.disabled = true;
            }
        });
    });

    updateHourPrice();
});

