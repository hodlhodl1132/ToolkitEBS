window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.Toastify = require('toastify-js')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import axios from 'axios';
import Swal from 'sweetalert2';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
window.Pusher = Pusher;

$('select.dropdown')
        .dropdown();

window.ErrorToast = (message) => {
        Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: 'center', // `left`, `center` or `right`
                style: {
                        background: "#B03060"
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function () {
                // Optional callbacks
                }
        }).showToast();
}

window.InfoToast = (message) => {
        Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                style: {
                        background: "#00bcd4"
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function () {
                // Optional callbacks
                }
        }).showToast();
}

window.AlertToast = (message) => {
        Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                style: {
                        background: "#ff9800"
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function () {
                // Optional callbacks
                }
        }).showToast();
}