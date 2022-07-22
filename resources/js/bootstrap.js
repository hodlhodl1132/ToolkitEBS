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
window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTls: true,
        authorizer: (channel, options) => {
                return {
                authorize: (socketId, callback) => {
                        axios.post('/broadcasting/auth', {
                        socket_id: socketId,
                        channel_name: channel.name
                        })
                        .then(response => {
                                callback(false, response.data);
                        })
                        .catch(error => {
                                callback(true, error);
                                window.ErrorToast("We have detected a duplicate connection. Please refresh the page and try again.");
                        });
                }
                };
        },
})

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
                position: 'center', // `left`, `center` or `right`
                style: {
                        background: "Alpine.store('countdown').stop = true"
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
                position: 'center', // `left`, `center` or `right`
                style: {
                        background: "#FE9A76"
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function () {
                // Optional callbacks
                }
        }).showToast();
}