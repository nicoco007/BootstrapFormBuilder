'use strict';

$(function() {
    // initialize date/time pickers
    $('.datetimepicker-date').each(function() {
        var $element = $(this);

        console.log($element.data('locale'));

        $element.datetimepicker({
            format: 'L',
            locale: $element.data('locale') || 'en'
        })
    });
});