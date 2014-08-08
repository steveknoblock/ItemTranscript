<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */


/**
 * The Transcript Note record class.
 *
 * @package ItemTranscript
 */

class TranscriptNote extends Omeka_Record_AbstractRecord /* implements Zend_Acl_Resource_Interface */
{

    /**
     * ID of transcript note text belongs to.
     *
     * @var string
     */
    public $transcript_id;

    /**
     * Transcript note text.
     *
     * @var string
     */
    public $text;

    /**
     * Transcript note order.
     *
     * @var string
     */
    public $order;


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
     * Delete a note.
     */
    protected function _delete() {
    
    }


    /**
     * Set the data for this note from an array.
     *
     * @param array $data Data to set
     */
    public function setData($data)
    {
    
        if (!empty($data['text'])) {
            $this->text = $data['text'];
        } else {
            $this->text = null;
        }
        
        if (!empty($data['order'])) {
            $this->order = $data['order'];
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
        return 'ItemTranscript_TranscriptNote';
    }
}
