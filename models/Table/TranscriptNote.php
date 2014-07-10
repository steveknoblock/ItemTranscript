<?php
/**
 * @copyright Steve Knoblock 2014
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */
 
 
/**
 * Transcript Note table class.
 *
 * @package ItemTranscript
 */
class Table_TranscriptNote extends Omeka_Db_Table
{

    /**
     * Find all notes for a page.
     *
     * @param Transcript $transcript
     * @return TranscriptNotes array (of objects, if found)
     */
    public function findByTranscript($transcript)
    {
        if (!$transcript->exists()) {
            return array();
        }

	// This does not require a join. The transcript id is known, only need
	// to pull up the notes for this transcript from the table.
        $select = $this->getSelect()
            ->where('transcript_notes.transcript_id = ?', $transcript->id);

        return $this->fetchObjects($select);
    }
    
    
    /**
     * Order by the order column by default.
     * Use SQL-based low-level permissions checking for transcript queries.
     * @return Omeka_Db_Select
     */

    public function getSelect()
    {
    
       /*
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('Collections');
        $permissions->apply($select, 'collections');
        */
 		$select = parent::getSelect();
        $select->order('transcript_notes.order');
        //$permissions = new Omeka_Db_Select_PublicPermissions('ItemTranscript_Transcripts');
        //$permissions->apply($select, 'transcripts');    
        return $select;
    }

}
