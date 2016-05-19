(function($) {
    $(function(){
        var filters = $('.filters-select');

        // On change reset all other filters and auto-submit form
        filters.change( function() {
            var value = $(this).val();
            $('.filters-select').val('');
            $(this).val(value);

            $(this).closest('form').submit();
        });

        // Makes select elements nicer using Selectric library
        filters.selectric({
            maxHeight: 400
        });

        // Rename 'Search' to 'Search by document name'
        $('#searchsubmit').val('Search by document name');
    });
})( jQuery );
