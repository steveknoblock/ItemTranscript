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
		jQuery('#edit-transcript-form').submit(function (event) {
			// add ids to li elements so that we can pull out the parent/child relationships
			var listData = jQuery('#note-list').nestedSortable('serialize');
			
			var noteIds = [];
			jQuery('#note-list .note').each(function () {
			// note this is iterating over the notes marked for deletion
				noteIds.push(jQuery(this).attr('id').match(/_(.*)/)[1]);
			});
			
			var deletedIds = [];
			jQuery('#note-list .deleted').each(function () {
			// note this is iterating over the notes marked for deletion
deletedIds.push(jQuery(this).parent().attr('id').match(/_(.*)/)[1]);
			});

			//jQuery('#notes-hidden').val(listData); // set value attrib on hidden input
			jQuery('#notes-hidden').val(noteIds.join(','));
			jQuery('#notes-delete-hidden').val(deletedIds.join(',')); // same here
			//alert(deletedIds); // I've confirmed in the V8 debugger that the value for notes-delete-hidden INPUT consists of the comma separated deleted id list
		});
    };
    

})(jQuery);
