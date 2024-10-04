import $ from 'jquery';
window.$ = window.jQuery = $;

import './bootstrap';
import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/js/all.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Alpine from 'alpinejs';
import { initializeDataTable, disableRecord, deleteRecord } from './datatable';

window.Alpine = Alpine;
Alpine.start();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    setTimeout(function() {
        let successMessage = $('#success-message');
        if (successMessage.length) {
            successMessage.fadeOut(1000, function() {
                $(this).remove();
            });
        }
    }, 5000); // 5000 ms = 5 detik
});

window.initializeDataTable = initializeDataTable;
window.disableRecord = disableRecord;
window.deleteRecord = deleteRecord;
