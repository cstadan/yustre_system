// ================================================
// GOOGLE CALENDAR INTEGRATION
// Uses Google Identity Services (OAuth 2.0)
// SESSION_USER se define en dashboard_clinic.php
// ================================================

const CALENDAR_CLIENT_ID = '532275483394-u79krduja5sm63fqqiufq954f4md1p86.apps.googleusercontent.com';
const CALENDAR_SCOPE     = 'https://www.googleapis.com/auth/calendar.readonly';

// Claves únicas por usuario — evita que una sesión comparta token con otra
const CAL_TOKEN_KEY = 'gcal_token_' + SESSION_USER;
const CAL_VIEW_KEY  = 'calView_'    + SESSION_USER;

let calendarTokenClient = null;
let calendarAccessToken = null;
let allEvents           = [];
let currentView         = localStorage.getItem(CAL_VIEW_KEY) || 'month';
let currentMonth        = new Date();
currentMonth.setDate(1);

function initCalendar() {
    calendarTokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CALENDAR_CLIENT_ID,
        scope:     CALENDAR_SCOPE,
        callback:  onCalendarToken,
    });
    const saved = JSON.parse(localStorage.getItem(CAL_TOKEN_KEY) || 'null');
    if (saved && saved.expires_at > Date.now()) {
        calendarAccessToken = saved.access_token;
        loadCalendarEvents();
    } else {
        localStorage.removeItem(CAL_TOKEN_KEY);
        showCalendarConnect();
    }
}

function showCalendarConnect() {
    document.getElementById('calendarBody').innerHTML = `
        <div class="calendar-connect">
            <div class="calendar-connect-icon">📅</div>
            <p class="calendar-connect-text">Connect your Google Calendar<br>to see upcoming events</p>
            <button class="btn-gcal-connect" onclick="requestCalendarAccess()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:8px">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Connect Google Calendar
            </button>
        </div>`;
}

function requestCalendarAccess() {
    calendarTokenClient.requestAccessToken({ prompt: 'consent' });
}

function onCalendarToken(response) {
    if (response.error) {
        document.getElementById('calendarBody').innerHTML =
            '<p class="placeholder-text">⚠️ Authorization failed. Please try again.</p>';
        return;
    }
    calendarAccessToken = response.access_token;
    localStorage.setItem(CAL_TOKEN_KEY, JSON.stringify({
        access_token: response.access_token,
        expires_at:   Date.now() + (response.expires_in * 1000)
    }));
    loadCalendarEvents();
}

async function loadCalendarEvents() {
    document.getElementById('calendarBody').innerHTML =
        '<p class="placeholder-text">Loading events...</p>';

    const past   = new Date(); past.setMonth(past.getMonth() - 1); past.setDate(1); past.setHours(0,0,0,0);
    const future = new Date(); future.setMonth(future.getMonth() + 3);

    try {
        const res = await fetch(
            `https://www.googleapis.com/calendar/v3/calendars/primary/events` +
            `?timeMin=${past.toISOString()}&timeMax=${future.toISOString()}` +
            `&singleEvents=true&orderBy=startTime&maxResults=100`,
            { headers: { Authorization: `Bearer ${calendarAccessToken}` } }
        );
        if (res.status === 401) {
            localStorage.removeItem(CAL_TOKEN_KEY);
            showCalendarConnect();
            return;
        }
        const data = await res.json();
        allEvents  = data.items || [];
        renderView();
    } catch (e) {
        document.getElementById('calendarBody').innerHTML =
            '<p class="placeholder-text">⚠️ Could not load calendar events.</p>';
    }
}

function renderView() {
    currentView === 'month' ? renderMonthView() : renderListView();
}

function buildTopbar() {
    const monthNames = ['January','February','March','April','May','June',
                        'July','August','September','October','November','December'];
    const label = `${monthNames[currentMonth.getMonth()]} ${currentMonth.getFullYear()}`;
    return `
    <div class="calendar-topbar">
        <span class="calendar-account">📅 Google Calendar</span>
        <div class="calendar-controls">
            <button class="cal-nav-btn" onclick="changeMonth(-1)">‹</button>
            <span class="cal-month-label">${label}</span>
            <button class="cal-nav-btn" onclick="changeMonth(1)">›</button>
            <button class="cal-view-btn ${currentView==='month'?'active':''}" onclick="switchView('month')" title="Month view">▦</button>
            <button class="cal-view-btn ${currentView==='list'?'active':''}"  onclick="switchView('list')"  title="List view">≡</button>
        </div>
        <button class="btn-gcal-disconnect" onclick="disconnectCalendar()">Disconnect</button>
    </div>`;
}

function renderMonthView() {
    const year        = currentMonth.getFullYear();
    const month       = currentMonth.getMonth();
    const firstDay    = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today       = new Date().toISOString().substring(0, 10);

    const evMap = {};
    allEvents.forEach(ev => {
        const d = (ev.start.dateTime || ev.start.date).substring(0, 10);
        if (!evMap[d]) evMap[d] = [];
        evMap[d].push(ev);
    });

    // Wrapper + topbar
    let html = `<div style="width:100%;min-width:0">` + buildTopbar();

    // Grid with inline styles to guarantee it always renders as grid
    html += `<div class="cal-grid" style="display:grid;grid-template-columns:repeat(7,1fr);width:100%;border-left:1px solid #e0e0e0;border-top:1px solid #e0e0e0">`;

    ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(d => {
        html += `<div class="cal-header-cell">${d}</div>`;
    });

    for (let i = 0; i < firstDay; i++) html += `<div class="cal-cell empty"></div>`;

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr   = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const isToday   = dateStr === today;
        const dayEvents = evMap[dateStr] || [];

        html += `<div class="cal-cell ${isToday?'today':''}">
            <div class="cal-day-num ${isToday?'today-num':''}">${day}</div>`;
        dayEvents.slice(0, 2).forEach(ev => {
            const color = ev.colorId ? getEventColor(ev.colorId) : '#007bff';
            html += `<div class="cal-event-pill" style="background:${color}" title="${escapeHtml(ev.summary||'')}">${escapeHtml(ev.summary||'(No title)')}</div>`;
        });
        if (dayEvents.length > 2) html += `<div class="cal-more">+${dayEvents.length - 2} more</div>`;
        html += `</div>`;
    }

    html += `</div></div>`;
    document.getElementById('calendarBody').innerHTML = html;
}

function changeMonth(dir) {
    currentMonth.setMonth(currentMonth.getMonth() + dir);
    renderMonthView();
}

function renderListView() {
    const now    = new Date().toISOString().substring(0, 10);
    const future = allEvents.filter(ev => (ev.start.dateTime || ev.start.date).substring(0,10) >= now).slice(0, 10);

    let html = `<div style="width:100%;min-width:0">` + buildTopbar();

    if (future.length === 0) {
        html += '<p class="placeholder-text" style="padding:30px">No upcoming events.</p></div>';
        document.getElementById('calendarBody').innerHTML = html;
        return;
    }

    const days   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const grouped = {};
    future.forEach(ev => {
        const d = (ev.start.dateTime || ev.start.date).substring(0,10);
        if (!grouped[d]) grouped[d] = [];
        grouped[d].push(ev);
    });

    html += '<div class="calendar-events">';
    Object.keys(grouped).forEach(dateStr => {
        const date    = new Date(dateStr + 'T12:00:00');
        const isToday = dateStr === new Date().toISOString().substring(0,10);
        html += `<div class="calendar-day-group">
            <div class="calendar-day-label ${isToday?'today':''}">
                <span class="day-name">${days[date.getDay()]}</span>
                <span class="day-num">${date.getDate()}</span>
                <span class="day-month">${months[date.getMonth()]}</span>
                ${isToday ? '<span class="today-badge">Today</span>' : ''}
            </div>
            <div class="calendar-day-events">`;
        grouped[dateStr].forEach(ev => {
            const title  = ev.summary || '(No title)';
            const color  = ev.colorId ? getEventColor(ev.colorId) : '#007bff';
            let timeStr  = 'All day';
            if (ev.start.dateTime) timeStr = `${formatTime(new Date(ev.start.dateTime))} – ${formatTime(new Date(ev.end.dateTime))}`;
            html += `<div class="calendar-event" style="border-left-color:${color}">
                <div class="event-title">${escapeHtml(title)}</div>
                <div class="event-time">🕐 ${timeStr}</div>
                ${ev.location ? `<div class="event-location">📍 ${escapeHtml(ev.location)}</div>` : ''}
            </div>`;
        });
        html += `</div></div>`;
    });
    html += '</div></div>';
    document.getElementById('calendarBody').innerHTML = html;
}

function switchView(view) {
    currentView = view;
    localStorage.setItem(CAL_VIEW_KEY, view);
    renderView();
}

function disconnectCalendar() {
    if (calendarAccessToken) google.accounts.oauth2.revoke(calendarAccessToken, () => {});
    calendarAccessToken = null;
    localStorage.removeItem(CAL_TOKEN_KEY);
    showCalendarConnect();
}

function formatTime(date) {
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function getEventColor(colorId) {
    const colors = {
        '1':'#7986cb','2':'#33b679','3':'#8e24aa','4':'#e67c73',
        '5':'#f6bf26','6':'#f4511e','7':'#039be5','8':'#616161',
        '9':'#3f51b5','10':'#0b8043','11':'#d50000'
    };
    return colors[colorId] || '#007bff';
}

// Auto-init: espera a que Google GIS esté listo
function waitForGoogle() {
    if (typeof google !== 'undefined' && google.accounts) {
        initCalendar();
    } else {
        setTimeout(waitForGoogle, 100);
    }
}
document.addEventListener('DOMContentLoaded', waitForGoogle);