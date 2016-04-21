(function($) {
    // Filters documents
    function filterDocuments( filterValue ) {
        $('.post-type-archive-document #content article').each(function() {
            $(this).hide();
            if ($(this).hasClass(filterValue)) {
                $(this).show(300);
            }
        });
    }

    $(function() {
        // Filters
        var filters = $('.filters-select');

        filters.reset = function () {
            $(this).each(function () {
                $(this).prop('selectedIndex', 0);
            });
        }

        filters.reset();
        $(filters[0]).prop('selectedIndex', 1);
        filterDocuments(filters[0].value);

        filters.change(function() {
            var filterValue = this.value;

            filters.reset();
            $(this).prop('value', filterValue);
            filterDocuments( filterValue );
        });

        // Makes select elements nicer using Selectric library
        filters.selectric({
            maxHeight: 400
        });

        filters.change(function() {
            filters.each(function () {
               $(this).data('selectric').refresh();
            });
        });
    });
})( jQuery );



