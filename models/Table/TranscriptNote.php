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
     /*
    public function findByTranscript($transcript)
    {
    	debug('in TranscriptNote findByTranscript');
        if (!$transcript->exists()) {
            return array();
        }

	// This does not require a join. The transcript id is known, only need
	// to pull up the notes for this transcript from the table.
        $select = $this->getSelect()
            ->where('transcript_notes.transcript_id = ?', $transcript->id);

        return $this->fetchObjects($select);
    }
    */
    
    /*
    just overriding this function causes it to always return either #20 or all notes, when commented out returns notes belonging to transcript
    
    an idea. This may only be called by functions that look for stuff, find and findBy
    
    
    The docs say that for example browse action invokes applysearchfilters
    
    In your table record, filter the select object using the provided parameters using: public function applySearchFilters($select, $params);
    
    
     */
    public function applySearchFilters($select, $params) {
    	debug('TranscriptNote->applySearchFilters');
    	//parent::applySearchFilters();
    	//foreach( $params as $param ) {
    	//	debug('params: '.$param);
    	//}
    	 if(isset($params['transcript_id'])) {
    	 	debug('Transcript id exists');
    	 }
    	 if(isset($params['id'])) {
    	 	debug('TranscriptNote id exists');
    	 }
    	 if(isset($params['text'])) {
    	 	debug('TranscriptNote text exists');
    	 }
    	 if(isset($params['order'])) {
    	 	debug('TranscriptNote order exists');
    	 }
    	 
    	$this->filterByTranscriptId($select, $params['transcript_id']);
    	
    }

    protected function filterByTranscriptId($select, $transcriptId)
    {
        $select->where('transcript_notes.transcript_id = ?', $transcriptId);
    }
    
    /**
     * Order by the order column by default.
     * Use SQL-based low-level permissions checking for transcript queries.
     * @return Omeka_Db_Select
     */
 /*
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('Collections');
        $permissions->apply($select, 'collections');
        */
        
    public function getSelect()
    {
    	debug('in TranscriptNote getSelect');
      
 		$select = parent::getSelect();
		
        //$select->order('transcript_notes.order');
        //$permissions = new Omeka_Db_Select_PublicPermissions('ItemTranscript_Transcripts');
        //$permissions->apply($select, 'transcripts');    
        return $select;
    }

}
