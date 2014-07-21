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
		jQuery('.sortable').nestedSortable({
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
		jQuery('#note-list .delete-toggle').click(function (event) {
			event.preventDefault();
			header = jQuery(this).parent();
			if (jQuery(this).hasClass('delete-element')) {
				jQuery(this).removeClass('delete-element').addClass('undo-delete');
				header.addClass('deleted');
			} else {
				jQuery(this).removeClass('undo-delete').addClass('delete-element');
				header.removeClass('deleted');
			}
		});
	};

	Omeka.ItemTranscript.setUpFormSubmission = function () {
		jQuery('#transcript-edit-form').submit(function (event) {
			// add ids to li elements so that we can pull out the parent/child relationships
			var listData = jQuery('#note-list').nestedSortable('serialize');
			var deletedIds = [];
			jQuery('#note-list .deleted').each(function () {
				deletedIds.push(jQuery(this).parent().attr('id').match(/_(.*)/)[1]);
			});
		
			jQuery('#notes-hidden').val(listData);
			jQuery('#notes-delete-hidden').val(deletedIds.join(','));
		});
    };
    
    

})(jQuery);
