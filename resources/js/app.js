require("./bootstrap");

import $ from "jquery";
window.$ = window.jQuery = $;

import "jquery-ui/ui/widgets/autocomplete.js";
import "jquery-ui/ui/widgets/datepicker.js";

jQuery(function($) {
    $(".form-auto-submit").on("focusout", "input, textarea", function() {
        $(this)
            .closest(".form-auto-submit")
            .submit();
    });

    $(".form-auto-submit").on("change", "select", function() {
        $(this)
            .closest(".form-auto-submit")
            .submit();
    });

});
