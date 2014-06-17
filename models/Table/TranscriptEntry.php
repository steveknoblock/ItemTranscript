<?php

/**
 * @copyright 2014 Steve Knoblock
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
	// Stub for future transcript entry structure
	
	/* A transcript consists of one or more transcript entries (or blocks of text) associated with the transcript.
	*/

    /**
     * After save callback.
     *
     * Update entry data and search data after saving.
     *
     * @var array $args
     */
    protected function afterSave($args)
    {
    }
	
	
    /**
     * Validate transcript entry.
     */
    protected function _validate()
    {
        if (!strlen($this->title)) {
            $this->addError('title', __('A transcript entry must not be empty.'));
        }
    }
    
    
    /**
     * Get this entry's owner transcript.
     *
     * @return id
     */
    public function getTranscriptId()
    {
        return $this->getTable('Transcript')->find($this->transcript_id);
    }
	
	/**
     * Delete owned blocks when deleting the transcript
     * (cascading delete).
     */
    protected function _delete()
    {
    }
    
    public function getSelect()
    {
        $select = parent::getSelect();
    }
}