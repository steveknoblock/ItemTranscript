<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */


/**
 * The Transcript record class.
 *
 * @package ItemTranscript
 */

class Transcript extends Omeka_Record_AbstractRecord /* implements Zend_Acl_Resource_Interface */
{
    /**
     * Transcript title.
     *
     * @var string
     */
    public $title;

    /**
     * Transcript description (in HTML).
     *
     * @var string
     */
    public $description;

    /**
     * Transcript entry.
     *
     * @var string
     */
    public $entry;

    /**
     * Transcript notes.
     *
     * @var array
     */
    public $notes;
    
    /**
     * Whether the transcript is featured.
     *
     * @var integer
     */
    public $featured = 0;

    /**
     * Whether the transcript is public.
     *
     * @var integer
     */
    public $public = 1;
    
    /**
     * Date the transcript was created, as a MySQL-formatted date string.
     *
     * @var string
     */
    public $added;

    /**
     * Date the transcript was last modified, as a MySQL-formatted date string.
     *
     * @var string
     */
    public $modified;

    /**
     * User ID of the user who created the transcript.
     *
     * @var integer
     */
    public $owner_id;


    /**
     * Initialize the mixins.
     */
    protected function _initializeMixins() {
    /*
        $this->_mixins[] = new Mixin_Tag($this);
        $this->_mixins[] = new Mixin_Owner($this);
    */
        $this->_mixins[] = new Mixin_PublicFeatured($this);
        $this->_mixins[] = new Mixin_Timestamp($this);
        $this->_mixins[] = new Mixin_ElementText($this);
        $this->_mixins[] = new Mixin_Search($this);
    }
    

    // ActiveRecord callbacks

    /**
     * Before-save ActiveRecord callback.
     *
     * @param array $args
     */
    protected function beforeSave($args)
    {
    	debug('Transcript::beforeSave');
        if ($args['post']) {
            $post = $args['post'];
            
            $this->beforeSaveElements($post);
        }
    }
    
    
    /**
     * After-save ActiveRecord callback.
     *
     * Updates search text and page data for the transcript.
     *
     * @param array $args
     */
    protected function afterSave($args)
    {
    	debug('Transcript::afterSave');
    	if(!$this->public) {
    		$this->setSearchTextPrivate();
    	}
    	
    	$this->setSearchTextTitle($this->title);
    	$this->addSearchText($this->title);
    	$this->addSearchText($this->description);

    }
    
    /**
     * Validation callback.
     */
    protected function _validate() {
    	debug('_validate()');
    	/*
        if (!strlen((string)$this->title)) {
            $this->addError('title', __('A transcript must be given a title.'));
        }

        if (strlen((string)$this->title) > 255) {
            $this->addError('title', __('The title for an transcript must be 255 characters or less.'));
        }
		*/
    }

    /**
     * Delete callback.
     *
     * Delete all assigned pages when the transcript is deleted.
     */
    protected function _delete() {
        /*
        Will need this in future to delete notes associated with transcript.
        $notes = $this->getTable('TranscriptNotes')->findBy(array('transcript_notes'=>$this->id));
        foreach($notes as $note) {
            $note->delete();
        }
        */
        //$this->deleteTaggings();
    }



    /**
     * Get all notes for this transcript.
     *
     * @return TranscriptNote array
     */
    public function getTranscriptNotes()
    {
        return $this->getTable('TranscriptNote')->findByNote($this);
    }



// not objects yet, notes are database rows, they must be instantiated as objects so they can be assigned as a collection of objects to this transcript

    /**
     * Set data for this transcript's notes.
     *
     * @param array $notesData An array of key-value arrays for each block.
     * @param boolean $deleteExtras Whether to delete any extra preexisting
     *  blocks.
     */ 
    public function setTranscriptNotes($notesData, $deleteExtras = true)
    {
        $existingNotes = $this->getTranscriptNotes();
        foreach ($notesData as $i => $noteData) {
            if (!empty($existingNotes)) {
                $note = array_pop($existingNotes);
            } else {
                $note = new TranscriptNote;
                $note->transcript_id = $this->id;
            }
            $note->order = $i;
            $note->setData($noteData);
            $note->save();
        }
        // Any leftover blocks beyond the new data get erased.
        if ($deleteExtras) {
            foreach ($existingBlocks as $extraBlock) {
                $extraBlock->delete();
            }
        }
    }
    
    
    
	/**
	 * Override getRecordUrl to take control over what URL 
	 * is returned for this record in a given context.
	 */

	public function getRecordUrl($action = 'show')
	{
		return array(
			'module' => 'item-transcript',
			'controller' => 'transcripts',
			'action' => $action,
			'id' => $this->id
		);
	}

    /**
     * Required by Zend_Acl_Resource_Interface.
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'ItemTranscript_Transcripts';
    }
}
