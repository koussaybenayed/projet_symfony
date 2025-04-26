import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
// assets/app.js
import { startStimulusApp } from '@symfony/stimulus-bridge';
const app = startStimulusApp();

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

// Initialisation du calendrier si la div existe
if (document.getElementById('calendar')) {
    const calendarEl = document.getElementById('calendar');
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth'
    });
    calendar.render();
}