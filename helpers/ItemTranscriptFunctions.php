<?php

/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */

	/**
	 * Render markup for the notes for a transcript.
	 *
	 * @param 
	 * @return string
	 */
	function item_transcript_notes_list()
	{
		debug('item_transcript_notes_list()');
	
		$transcript = get_current_record('transcript');
		$transcript->notes = $transcript->getNotes();
		$html = '<ul id="note-list" class="sortable">';
		
		
		/*
		display zero-based index as one-based index to user
		foreach ($transcript->getPageBlocks() as $index => $block):
            $block->order = $index + 1;
            echo $this->partial('transcripts/block-form.php', array('block' => $block));
        endforeach
        
        */
		foreach ($transcript->notes as $note) {
			$noteId = html_escape($note->id);
			debug('Iterating over notes, noteId: '. $noteId .' note->order: '.$note->order);
			$html .= '<li class="note" id="note_'. $noteId .'">';
			$html .= '<div class="sortable-item">';
			$html .= '<a href="'. url('item-transcript/notes/edit/id/' . $noteId) . '">' . html_escape($note->order) . '</a>';
			$html .= html_escape($note->text);
			$html .= ' <a class="delete-toggle delete-element" href="#">' . __('Delete') . '</a>';
			$html .= '</div>';
			$html .= '</li>';
		}
		//$html .= '<input type="hidden" name="notes-hidden" value="" id="notes-hidden">    <input type="hidden" name="notes-delete-hidden" value="" id="notes-delete-hidden">';
		$html .= '</ul>';
		return $html;
	}

