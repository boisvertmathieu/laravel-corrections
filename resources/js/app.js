require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;

jQuery(function ($) {

    $(".form-auto-submit").on('focusout', 'input, textarea', function () {
        $(this).closest('.form-auto-submit').submit();
    });
    

    $(".form-auto-submit").on('change', 'select', function () {
        $(this).closest('.form-auto-submit').submit();
    });


});