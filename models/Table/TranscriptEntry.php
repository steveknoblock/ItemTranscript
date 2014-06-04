<?php

/**
 * @copyright
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */

/**
 * TranscriptEntry table class.
 *
 * @package ItemTranscript
 */
 
class Table_TranscriptEntry extends Omeka_Db_Table
{
    public function getSelect()
    {
        $select = parent::getSelect();
        $request = Zend_Controller_Front::getInstance()->getRequest();

        $db = $this->getDb();

        $select->join(array('transcripts' => $db->TranscriptEntry), 'transcript_entries.page_id = transcripts.id', array());
        $select->join(array('transcript_entries' => $db->Transcript), 'transcripts.id = transcript_entries.transcript_id', array());

        $permissions = new Omeka_Db_Select_PublicPermissions('Transcripts');

        $permissions->apply($select, 'transcripts');

        return $select;        
    }
}