var Omeka = Omeka || {};
Omeka.ItemTranscript = {};

(function ($) {
  



        function setSearchVisibility(show) {
            var searchForm = $('#page-search-form');
            var searchButton = $('#show-or-hide-search');

            if (typeof show === 'undefined') {
                show = !searchForm.is(':visible');
            }
            if (show) {
                searchForm.show();
                searchButton.addClass('hide-form').removeClass('show-form');
            } else {
                searchForm.hide();
                searchButton.addClass('show-form').removeClass('hide-form');
            }
        }


    /**
     * Enable drag and drop sorting for elements.
     */
    Omeka.ItemTranscript.enableSorting = function () {
        $('.sortable').nestedSortable({
            listType: 'ul',
            items: 'li.note',
            handle: '.sortable-item',
            revert: 200,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            toleranceElement: '> div',
            placeholder: 'ui-sortable-highlight',
            containment: 'document',
            maxLevels: 3
        });
    };

    Omeka.ItemTranscript.activateDeleteLinks = function () {
        $('#note-list .delete-element').click(function (event) {
            event.preventDefault();
            var header = $(this).parent();
            if ($(this).hasClass('delete-element')) {
                $(this).removeClass('delete-element').addClass('undo-delete');
                header.addClass('deleted');
            } else {
                $(this).removeClass('undo-delete').addClass('delete-element');
                header.removeClass('deleted');
            }
        });
    };

    Omeka.ItemTranscript.setUpFormSubmission = function () {
        $('edit-transcript-form').submit(function (event) {
            // add ids to li elements so that we can pull out the parent/child relationships
            var listData = $('#note-list').nestedSortable('serialize');
            var deletedIds = [];
            $('#note-list .deleted').each(function () {
                deletedIds.push($(this).parent().attr('id').match(/_(.*)/)[1]);
            });
            
            $('#notes-hidden').val(listData);
            $('#notes-delete-hidden').val(deletedIds.join(','));
        });
    };

    Omeka.ItemTranscript.setUpPageValidate = function (validateUrl) {
        $('#exhibit-page-form').submit(function (event) {
            $('.page-validate-message').remove();

            $.ajax({
                url: validateUrl,
                method: 'POST',
                dataType: 'json',
                data: $('#title, #slug').serialize(),
                async: false,
                success: function (response) {
                    if (!response.success) {
                        event.preventDefault();
                        $(document).scrollTop(0);
                        $.each(response.messages, function (key, value) {
                            var message = '<span class="error page-validate-message">' + value + '</span>';
                            jQuery('#' + key).after(message)
                                .parent().effect('shake', {distance: 10});
                        });
                    }
                }
            });
        });
    };
})(jQuery);
