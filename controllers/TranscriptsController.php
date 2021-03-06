<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */

require_once dirname(__FILE__) . '../../helpers/TranscriptParse.php';
require_once dirname(__FILE__) . '../../helpers/ItemTranscriptFunctions.php';

/**
 * Controller for Transcripts. 
 *
 * @package ItemTranscript
 */
 
class ItemTranscript_TranscriptsController extends Omeka_Controller_AbstractActionController
{
    /**
     * Controller-wide initialization. Sets the underlying model to use.
     */
    public function init()
    {
        // Setting the model class enables this controller to perform basic functions.
        $this->_helper->db->setDefaultModelName('Transcript');
    }


    /**
     * Add action.
     */
    public function addAction()
    {
    	debug('addAction');

        // Create a new transcript.
       	$transcript = new Transcript;
        
        //debug('new Transcript');
        
        // Get all the element sets that apply to the item.
       // $this->view->elementSets = $this->_getItemElementSets();
        
       
		if ($this->getRequest()->isPost()) {
			//debug('Processing request');
			$this->_processTranscriptForm($transcript, 'add');
			// Go to browse.
        	$this->_helper->redirector('browse');
		} else {
			//debug('Getting form');
			// Omeka_Form_Admin is too simple to handle the item data
			//$tmp = $this->_getForm($transcript);
			$this->render('add');
		}
	}
		

    /**
     * Edit action.
     */
     
     /**
      * Note: The Omeka callback function editAction()
      * assumes a view file 'edit.php' exists under the
      * views directory. This explains why the view is
      * not explicity rendered using $this->render()
      * The edit view is automatically created and rendered
      * on editAction().
      */
    public function editAction()
    {   
    	debug('TranscriptsController->editAction');
	    $transcript = $this->_helper->db->findById();
	    
	    if($_POST['add_note']) {
	    $this->_helper->redirector->gotoSimple('add','notes','item-transcript',
	    	array(
	    		'transcript_id' => $transcript->id,
	    		//'previous'      => $transcript->id
	    		)
	    	);
	    }

        /**
         * Get notes belonging to this transcript.
         */
		$transcript->notes = $transcript->getNotes();
		
		
        //$this->view->form = $this->_getForm($transcript);      
       //$transcript->notes = $this->getNotes();
               
        // okay, model objects must be injected explicity into view
        // $this->view->exhibit = $exhibit;
        $this->view->transcript = $transcript;
        $this->_processTranscriptForm($transcript, 'edit');
    }

	
 	/**
	 * Process the form submission for add or edit actions.
	 */
	protected function _processTranscriptForm($transcript, $mode)
	{
		// don't display messages or save if not POST mode request
		if ($this->getRequest()->isPost()) {
			try {
			$transcript->setPostData($_POST); // copied from exhibits q: why refer to php post var when request is available?
			if ($transcript->save()) {
				if ('add' == $mode) {
					$this->_helper->flashMessenger(__('The new transcript "%s" has been saved.', $transcript->title), 'success');
				} else if ('edit' == $mode) {
					$this->_helper->flashMessenger(__('The edited transcript "%s" has been saved.', $transcript->title), 'success');
				}
				debug('redirecting to browse');
				$this->_helper->redirector('browse');       
			}
			// Catch validation errors.
				} catch (Omeka_Validate_Exception $e) {
					$this->_helper->flashMessenger($e);
				}
		 }
	}           

    
    /**
     * Delete transcript.
     * Uses parent::deleteAction()
     */
  

 	// Note: the following function is unused due to limitations on the Omeka form system
	/**
	 * Generate and return the HMTL for the transcript form.
	 * This is typically be made available to the view.
	 * @param $transcript object
	 * @return string HTML
	 */
	protected function _getForm($transcript)
	{
	
		debug('_getForm');
		
	 	$formOptions = array('type' => 'item_transcript_transcript', 'hasPublicPage' => true);
        if ($transcript && $transcript->exists()) {
            $formOptions['record'] = $transcript;
        }
		$form = new Omeka_Form_Admin($formOptions);
		
        $form->addElementToEditGroup(
            'text', 'title',
            array(
                'id' => 'item-transcript-title',
                'value' => $transcript->title,
                'label' => __('Title'),
                'description' => __('Name of transcript (required)'),
                'required' => true
            )
        );
	
	    $form->addElementToEditGroup(
            'textarea', 'description',
            array('id' => 'item-transcript-description',
                'cols'  => 50,
                'rows'  => 7,
                'value' => $transcript->description,
                'label' => __('Description'),
                'description' => __('Description of transcript'),
                'required' => false
                )
        );

		$form->addElementToEditGroup(
            'textarea', 'entry',
            array('id' => 'item-transcript-entry',
                'cols'  => 50,
                'rows'  => 7,
                'value' => $transcript->entry,
                'label' => __('Transcript Entry'),
                'description' => __('Add transcript text (using SpeakUp!) markup')
            )
        );
        
         $form->addElementToSaveGroup(
            'checkbox', 'public',
            array(
                'id' => 'item-transcript-is-published',
                'values' => array(1, 0),
                'checked' => $transcript->public,
                'label' => __('Publish this transcript?'),
                'description' => __('Checking this box will make the transcript public')
            )
        );

         $form->addElementToSaveGroup(
            'checkbox', 'featured',
            array(
                'id' => 'item-transcript-is-featured',
                'values' => array(1, 0),
                'checked' => $transcript->featured,
                'label' => __('Feature this transcript?'),
                'description' => __('Checking this box will make the transcript featured')
            )
        );
	 	return $form;
	
	}

                     //_getDeleteSuccessMessage() override
    protected function _getDeleteSuccessMessage($record)
    {
        return __('The transcript "%s" has been deleted.', $record->title);
    }


    /**
     * Browse transcripts action.
     */
    public function browseAction() {
	    debug('TranscriptsController->browseAction');
        //$request = $this->getRequest();
        //$sortParam = $request->getParam('sort');
        //$sortOptionValue = get_option('item_transcript_sort_browse');
/*
        if (!isset($sortParam)) {
            switch ($sortOptionValue) {
                case 'alpha':
                    //$request->setParam('sort', 'alpha');
                    break;
                case 'recent':
                    //$request->setParam('sort', 'recent');
                    break;
            }
        }
*/
        parent::browseAction();
    }


    /**
     * Show transcript page.
     */
    public function showAction()
    {
    	debug('TranscriptsController->showAction');
        /**
         * Restrict access to the page when it is not published.
         */
        if (!$transcript->is_published 
            && !$this->_helper->acl->isAllowed('show-unpublished')) {
            throw new Omeka_Controller_Exception_403;
        }
        
        // Get the page object from the passed ID.
        $transcriptId = $this->_getParam('id');
        
        $transcript = $this->_helper->db->getTable('Transcript')->find($transcriptId);

        /**
         * Get notes belonging to this transcript and add to view.
         * Add transcript to view.
         */
         // Todo: needs access control for notes
		$this->view->notes = $transcript->getNotes();
		$this->view->transcript = $transcript;
    }
    

    /**
     * Add a note to a transcript.
     *
     * The URL param 'id' refers to the note that will be contained by the transcript.
     */
	// This is handled by the Notes controller.
    
    
    /**
     * getNoteCount
     * Get the number of notes belonging to this transcript.
     * @return int
     */
     public function getNoteCount() {
     	// This could get the value of $this->noteCount;
     	// or it could query the Notes table for a count of notes belonging to this transcript by id
     	// or its also possible when the transcript is constructed to query
     	// all its notes and get the count from the array containing them.
     	
     	// This needs a WHERE clause
     	$noteCount = get_db()->getTable('TranscriptNotes')->findBy(array('transcript_id' => $transcript->id));
     	return $noteCount;
     }

    
        
    /**
     * Use global settings for determining browse page limits.
     * This function determines the display of records on browseAction
     * automatically called by browseAction.
     * @return int
     */
    public function _getBrowseRecordsPerPage()
    {
        if (is_admin_theme()) {
            return (int) get_option('per_page_admin');
        } else {
            return (int) get_option('per_page_public');
        }
    }

    /**
     * List tags for exhibits action.
     */
    public function tagsAction()
    {
        $params = array_merge($this->_getAllParams(), array('type'=>'Transcript'));
        $tags = $this->_helper->db->getTable('Tag')->findBy($params);
        $this->view->assign(compact('tags'));
    }



}
