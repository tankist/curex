(function (window, $) {
    $(function () {
        $('select').select2({dropdownCssClass: 'dropdown-inverse'});
        $(':radio').radiocheck();
    });
})(window, window.jQuery);