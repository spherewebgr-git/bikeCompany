@extends('layouts.admin')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6/index.global.min.js"></script><div class="page-body container" style="padding: 40px 0;">
        <div class="page-body container" style="padding: 40px 0;">
            <h2>Manage Blocked Dates</h2>

            <div class="form-group" style="max-width: 300px; margin-bottom: 20px;">
                <label>Apply to</label>
                <select id="bike-filter" class="form-control">
                    <option value="">All bikes (global block)</option>
                    @foreach($bikes as $bike)
                        <option value="{{ $bike->id }}">{{ $bike->id }} - {{ $bike->SKU }} — {{ $bike->brand->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 30px; align-items: flex-start;">
                <div style="flex: 1; min-width: 0;">
                    <div id="admin-calendar"
                         data-events-url="{{ route('blocked-dates.events') }}"
                         data-store-url="{{ route('blocked-dates.store') }}"
                         data-destroy-url-base="{{ url('/dashboard/management/blocked-dates') }}">
                    </div>
                </div>

                <div style="width: 320px; flex-shrink: 0;">
                    <h4 style="margin-bottom: 15px;">Blocked Dates List</h4>
                    <div id="blocked-list" style="display: flex; flex-direction: column; gap: 10px;"></div>
                </div>
            </div>

            <div id="block-reason-modal" style="display:none; margin-top: 20px;">
                <input type="text" id="block-reason" class="form-control" placeholder="Reason (π.χ. Εθνική Εορτή)">
                <button id="confirm-block" class="btn btn-fill">Block Selected Range</button>
                <button id="cancel-block" class="btn btn-default">Cancel</button>
            </div>
        </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const adminCalendarEl = document.getElementById('admin-calendar');
                if (!adminCalendarEl) return;

                const bikeFilter = document.getElementById('bike-filter');
                const modal = document.getElementById('block-reason-modal');
                const reasonInput = document.getElementById('block-reason');
                const blockedListEl = document.getElementById('blocked-list');

                let pendingSelection = null;
                let blockedEvents = [];

                function addDaysToDateStr(dateStr, days) {
                    const [y, m, d] = dateStr.split('-').map(Number);
                    const utcMs = Date.UTC(y, m - 1, d) + days * 86400000;
                    const dt = new Date(utcMs);
                    return dt.getUTCFullYear() + '-' +
                        String(dt.getUTCMonth() + 1).padStart(2, '0') + '-' +
                        String(dt.getUTCDate()).padStart(2, '0');
                }

                function formatDateStr(dateStr) {
                    const [y, m, d] = dateStr.split('-');
                    return `${d}/${m}/${y}`;
                }

                function csrfToken() {
                    return document.querySelector('meta[name="csrf-token"]').content;
                }

                function deleteBlockedDate(id) {
                    return fetch(`${adminCalendarEl.dataset.destroyUrlBase}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken(),
                            'Accept': 'application/json',
                        }
                    })
                        .then(r => {
                            if (!r.ok) throw new Error('Delete failed: ' + r.status);
                            return adminCalendar.refetchEvents();
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Something went wrong unblocking this date.');
                        });
                }

                // ΝΕΟ: render της λίστας δίπλα στο calendar
                function renderBlockedList() {
                    blockedListEl.innerHTML = '';

                    if (blockedEvents.length === 0) {
                        blockedListEl.innerHTML = '<p style="color:#999; font-size:14px;">No blocked dates.</p>';
                        return;
                    }

                    blockedEvents.forEach(ev => {
                        const lastDay = addDaysToDateStr(ev.end, -1);
                        const row = document.createElement('div');
                        row.style.cssText = 'display:flex; justify-content:space-between; align-items:center; padding:10px 14px; border:1px solid #eee; border-radius:8px; background:#fff;';

                        row.innerHTML = `
                <div>
                    <div style="font-weight:700; font-size:13px; color:#222;">${ev.title || 'Blocked'}</div>
                    <div style="font-size:12px; color:#777;">${formatDateStr(ev.start)} - ${formatDateStr(lastDay)}</div>
                </div>
                <button type="button" class="btn btn-default btn-sm unblock-btn" data-id="${ev.id}" style="font-size:12px; padding:6px 12px;">Unblock</button>
            `;

                        blockedListEl.appendChild(row);
                    });

                    blockedListEl.querySelectorAll('.unblock-btn').forEach(btn => {
                        btn.addEventListener('click', function () {
                            if (!confirm('Unblock this date range?')) return;
                            deleteBlockedDate(this.dataset.id);
                        });
                    });
                }

                const adminCalendar = new FullCalendar.Calendar(adminCalendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    selectMinDistance: 0,

                    events: function (info, successCallback, failureCallback) {
                        const bikeId = bikeFilter.value;
                        const url = adminCalendarEl.dataset.eventsUrl + (bikeId ? `?bike_id=${bikeId}` : '');

                        fetch(url)
                            .then(r => r.json())
                            .then(data => {
                                blockedEvents = data;
                                renderBlockedList(); // ΝΕΟ

                                successCallback(data.map(ev => ({
                                    id: ev.id,
                                    title: ev.title,
                                    start: ev.start,
                                    end: ev.end,
                                    display: 'background',
                                    color: '#ffcccc'
                                })));
                            })
                            .catch(failureCallback);
                    },

                    dayCellClassNames: function (arg) {
                        const dateStr =
                            arg.date.getFullYear() + '-' +
                            String(arg.date.getMonth() + 1).padStart(2, '0') + '-' +
                            String(arg.date.getDate()).padStart(2, '0');

                        const isBlocked = blockedEvents.some(event => {
                            const evStart = (event.start || '').slice(0, 10);
                            const evEndRaw = (event.end || '').slice(0, 10);
                            const evEnd = (evEndRaw === evStart) ? addDaysToDateStr(evStart, 1) : evEndRaw;
                            return dateStr >= evStart && dateStr < evEnd;
                        });

                        return isBlocked ? ['day-cell-blocked'] : [];
                    },

                    select: function (info) {
                        // ΝΕΟ: καθαρίζουμε προηγούμενο preview αν υπάρχει
                        adminCalendar.getEvents()
                            .filter(e => e.id === 'selection-preview')
                            .forEach(e => e.remove());

                        pendingSelection = { start: info.startStr, end: info.endStr };

                        const lastDay = addDaysToDateStr(info.endStr, -1);

                        // ΝΕΟ: προσθέτουμε ξεκάθαρο πράσινο preview πάνω στις επιλεγμένες μέρες
                        adminCalendar.addEvent({
                            id: 'selection-preview',
                            title: `${formatDateStr(info.startStr)} - ${formatDateStr(lastDay)}`,
                            start: info.startStr,
                            end: info.endStr,
                            allDay: true,
                            classNames: ['admin-selection-preview']
                        });

                        modal.style.display = 'block';
                        adminCalendar.unselect();
                    },

                    eventClick: function (info) {
                        if (info.event.id === 'selection-preview') return; // δεν κάνει delete το preview
                        if (!confirm(`Unblock "${info.event.title}"?`)) return;
                        deleteBlockedDate(info.event.id);
                    }
                });

                adminCalendar.render();

                document.getElementById('confirm-block').addEventListener('click', function () {
                    if (!pendingSelection) return;

                    fetch(adminCalendarEl.dataset.storeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
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

                            // ΝΕΟ: καθαρίζουμε το preview μετά την επιβεβαίωση
                            adminCalendar.getEvents()
                                .filter(e => e.id === 'selection-preview')
                                .forEach(e => e.remove());

                            adminCalendar.refetchEvents();
                        });
                });

                document.getElementById('cancel-block').addEventListener('click', function () {
                    modal.style.display = 'none';
                    pendingSelection = null;

                    // ΝΕΟ: αν ακυρώσει, φεύγει και το preview
                    adminCalendar.getEvents()
                        .filter(e => e.id === 'selection-preview')
                        .forEach(e => e.remove());
                });

                bikeFilter.addEventListener('change', () => adminCalendar.refetchEvents());
            });
        </script>
    @endpush
@endsection
