import Alpine from 'alpinejs';
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.min.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import './admin-featured-bikes.js'
import Swiper from 'swiper';
import { Navigation, Pagination, Thumbs } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/thumbs';

Swiper.use([Navigation, Pagination, Thumbs]);

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

    function roundUpToWholeHour(date) {
        const d = new Date(date);
        if (d.getMinutes() > 0 || d.getSeconds() > 0 || d.getMilliseconds() > 0) {
            d.setHours(d.getHours() + 1);
        }
        d.setMinutes(0, 0, 0);
        return d;
    }

    let selectedType = 'hour';
    let currentStart = roundUpToWholeHour(new Date(startDisplay.dataset.initialStart));
    let calendarSelection = null;
    let availabilityEvents = [];
    let calendar = null;

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function toDateStr(y, m, d) {
        return `${y}-${pad(m)}-${pad(d)}`;
    }

    function addDaysToDateStr(dateStr, days) {
        const [y, m, d] = dateStr.split('-').map(Number);
        const utcMs = Date.UTC(y, m - 1, d) + days * 86400000;
        const dt = new Date(utcMs);
        return toDateStr(dt.getUTCFullYear(), dt.getUTCMonth() + 1, dt.getUTCDate());
    }

    function parseLocalDateTime(str) {
        const [datePart, timePart] = str.split(/[T ]/);
        const [y, m, d] = datePart.split('-').map(Number);
        if (!timePart) return new Date(y, m - 1, d);
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
        noCalendar: false,
        dateFormat: "d/m/Y H:i",
        minDate: "today",
        minTime: "08:00",
        maxTime: "21:00",
        minuteIncrement: 60,
        time_24hr: true,
        defaultDate: currentStart,
        onChange: function (selectedDates) {
            userInteracted = true;
            currentStart = selectedDates[0];
            updateDurationLimit();
            updateHourPrice();
        },
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dayStart = new Date(dayElem.dateObj);
            dayStart.setHours(0, 0, 0, 0);

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (dayStart < today) {
                return;
            }

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
        },

        onOpen: function (selectedDates, dateStr, instance) {
            const timeContainer = instance.calendarContainer.querySelector('.flatpickr-time');
            if (!timeContainer) return;

            instance.calendarContainer
                .querySelectorAll('.flatpickr-time-label')
                .forEach(el => el.remove());

            const label = document.createElement('div');
            label.className = 'flatpickr-time-label';
            label.innerHTML = '<i class="fa-regular fa-clock"></i> Select start time of your ride';

            timeContainer.parentNode.insertBefore(label, timeContainer);
        }
    });

    durationInput.addEventListener('input', () => {
        userInteracted = true;
        updateHourPrice();
    });

    let userInteracted = false;
    function updateHourPrice() {
        renderHourAvailability(currentStart);

        const hours = parseInt(durationInput.value) || 0;
        const maxAllowed = getMaxHoursForStart(currentStart);

        if (hours > maxAllowed) {
            // ΝΕΟ: alert μόνο εδώ, μόνο αν το προκάλεσε ο χρήστης
            if (userInteracted) {
                alert(`Sorry but we close at 21:00 — you can select up to ${maxAllowed} hours from your start time.`);
            }
            durationInput.value = maxAllowed;
            updateHourPrice();
            return;
        }

        const end = new Date(currentStart.getTime() + hours * 3600 * 1000);

        rentStartField.value = flatpickr.formatDate(currentStart, "Y-m-d H:i:S");
        rentEndField.value   = flatpickr.formatDate(end, "Y-m-d H:i:S");

        const unavailable = availabilityEvents.some(event => {
            const eventStart = parseLocalDateTime(event.start);
            const eventEnd   = parseLocalDateTime(event.end);
            return currentStart < eventEnd && end > eventStart;
        });

        if (unavailable) {
            submitBtn.disabled = true;
            priceValue.textContent = '0 €';
            priceSummary.textContent = '0 €';
            durationSummary.textContent = '-';
            priceField.value = '';
            return;
        }

        const price = hours * hourPrice;
        priceField.value = price;

        priceValue.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        priceSummary.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        durationSummary.textContent = `${hours} ώρες`;
        submitBtn.disabled = hours < 1;
    }

    function getMaxHoursForStart(startDate) {
        const closingTime = new Date(
            startDate.getFullYear(),
            startDate.getMonth(),
            startDate.getDate(),
            21, 0, 0
        );

        const diffMs = closingTime.getTime() - startDate.getTime();
        const maxHours = Math.floor(diffMs / 3600000);

        return Math.max(0, maxHours);
    }

    function updateDurationLimit() {
        const maxAllowed = Math.min(getMaxHoursForStart(currentStart), 72);

        durationInput.max = maxAllowed;

        const currentValue = parseInt(durationInput.value) || 0;
        if (currentValue > maxAllowed) {
            durationInput.value = maxAllowed;
        }
    }

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
                                title: event.title || 'Booked',
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

            dayCellClassNames: function (arg) {
                const dateStr =
                    arg.date.getFullYear() + '-' +
                    String(arg.date.getMonth()+1).padStart(2,'0') + '-' +
                    String(arg.date.getDate()).padStart(2,'0');

                const isBlocked = availabilityEvents.some(event => {
                    const evStart = (event.start || '').slice(0, 10);
                    const evEndRaw = (event.end || '').slice(0, 10);
                    const evEnd = (evEndRaw === evStart) ? addDaysToDateStr(evStart, 1) : evEndRaw;

                    return dateStr >= evStart && dateStr < evEnd;
                });

                return isBlocked ? ['day-cell-blocked'] : [];
            },

            dateClick: function (info) {
                if (selectedType !== 'day' && selectedType !== 'week') return;

                // ΑΦΑΙΡΕΘΗΚΕ το alert — αγνοούμε σιωπηλά κλικ σε περασμένη μέρα
                const todayStr = new Date().toISOString().slice(0, 10);
                if (info.dateStr < todayStr) {
                    return;
                }

                const startStr = info.dateStr;

                const units = selectedType === 'week'
                    ? (parseInt(weeksInput.value) || 1)
                    : (parseInt(dayInput.value) || 1);

                const totalDays = selectedType === 'week' ? units * 7 : units;
                const endStr = addDaysToDateStr(startStr, totalDays);

                // ΑΦΑΙΡΕΘΗΚΕ το alert — καθαρίζουμε σιωπηλά την επιλογή αν είναι μπλοκαρισμένη
                if (isRangeBlocked(startStr, endStr)) {
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

    function recalcFromExistingStart() {
        if (!calendarSelection) return;

        const units = selectedType === 'week'
            ? (parseInt(weeksInput.value) || 1)
            : (parseInt(dayInput.value) || 1);

        const totalDays = selectedType === 'week' ? units * 7 : units;
        const newEnd = addDaysToDateStr(calendarSelection.start, totalDays);

        // ΑΦΑΙΡΕΘΗΚΕ το alert
        if (isRangeBlocked(calendarSelection.start, newEnd)) {
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

        rentStartField.value = start;
        rentEndField.value = end;
        priceField.value = price;

        priceValue.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        priceSummary.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        durationSummary.textContent = `${label} (${formatDateStr(start)} - ${formatDateStr(lastDay)})`;
        submitBtn.disabled = false;
    }

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

    updateDurationLimit();
    updateHourPrice();

});

// ============================================================
// ADMIN — BLOCKED DATES CALENDAR (drag-select block/unblock)
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    const adminCalendarEl = document.getElementById('admin-calendar');
    if (!adminCalendarEl) return; // δεν είμαστε στη σελίδα admin blocked-dates, skip

    const bikeFilter = document.getElementById('bike-filter');
    const modal = document.getElementById('block-reason-modal');
    const reasonInput = document.getElementById('block-reason');

    let pendingSelection = null;

    const adminCalendar = new Calendar(adminCalendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        selectMinDistance: 0, // επιτρέπει selection και με ένα click, όχι μόνο drag

        events: function (info, successCallback, failureCallback) {
            const bikeId = bikeFilter.value;
            const url = adminCalendarEl.dataset.eventsUrl + (bikeId ? `?bike_id=${bikeId}` : '');

            fetch(url)
                .then(r => r.json())
                .then(data => successCallback(data.map(ev => ({
                    id: ev.id,
                    title: ev.title,
                    start: ev.start,
                    end: ev.end,
                    display: 'background',
                    color: '#ffcccc'
                }))))
                .catch(failureCallback);
        },

        select: function (info) {
            pendingSelection = { start: info.startStr, end: info.endStr };
            modal.style.display = 'block';
            adminCalendar.unselect();
        },

        eventClick: function (info) {
            if (!confirm(`Unblock "${info.event.title}"?`)) return;

            fetch(`/admin/blocked-dates/${info.event.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(() => adminCalendar.refetchEvents());
        }
    });

    adminCalendar.render();

    document.getElementById('confirm-block').addEventListener('click', function () {
        if (!pendingSelection) return;

        fetch(adminCalendarEl.dataset.storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                bike_id: bikeFilter.value || null,
                start_date: pendingSelection.start,
                end_date: pendingSelection.end,
                reason: reasonInput.value || null,
            })
        })
            .then(r => r.json())
            .then(() => {
                modal.style.display = 'none';
                reasonInput.value = '';
                pendingSelection = null;
                adminCalendar.refetchEvents();
            });
    });

    document.getElementById('cancel-block').addEventListener('click', function () {
        modal.style.display = 'none';
        pendingSelection = null;
    });

    bikeFilter.addEventListener('change', () => adminCalendar.refetchEvents());
});

// ============================================================
// SWIPER - BIKE GALLERY
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    const gallery = document.querySelector('.bikeGallery');
    const thumbsEl = document.querySelector('.bikeGalleryThumbs');

    if (!gallery || !thumbsEl) return;

    const galleryThumbs = new Swiper(thumbsEl, {
        direction: 'vertical',
        loop: false,
        spaceBetween: 10,
        slidesPerView: 5,
        watchSlidesProgress: true,
        observer: true,
        observeParents: true,
    });

    new Swiper(gallery, {
        loop: true,
        slidesPerView: 1,
        observer: true,
        observeParents: true,
        pagination: {
            el: gallery.querySelector('.swiper-pagination'),
            clickable: true,
        },
        navigation: {
            nextEl: '.bike-gallery-next',
            prevEl: '.bike-gallery-prev',
        },
        thumbs: {
            swiper: galleryThumbs,
        },
    });

});

// ============================================================
// SCROLL REVEAL — About Us section
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const revealEls = document.querySelectorAll('.about-us .reveal, .about-us .reveal-up');
    if (!revealEls.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    revealEls.forEach(el => observer.observe(el));
});
