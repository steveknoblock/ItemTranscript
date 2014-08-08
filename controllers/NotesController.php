<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */


/**
 * Controller for Notes associated with a Transcript.
 *
 * @package ItemTranscript
 */
 
class ItemTranscript_NotesController extends Omeka_Controller_AbstractActionController
{
    /**
     * Controller-wide initialization. Sets the underlying model to use.
     */
    public function init()
    {
        // Setting the model class enables this controller to perform basic functions.
        $this->_helper->db->setDefaultModelName('TranscriptNote');
    }


    /**
     * Add Action
     * Add a note to a transcript.
     * The URL param 'id' refers to the note that will be contained by the transcript.
     */
 	public function addAction()
	{
		debug('NotesController->addAction');
		if($this->getRequest()) {
			//debug('getRequest');
			if($this->getRequest()->isPost()) {
					//debug('is post');
					$transcriptId = $this->getRequest()->getParam('transcript_id');
				} else {
					//debug('is get');
					$transcriptId = $this->getRequest()->get('transcript_id');
				}
		}
		//debug('Adding note to transcript: '. $transcriptId);

		$this->view->transcript_id = $transcriptId;
		
		$note = new TranscriptNote;
		// assign to transcript
		$note->transcript_id = $transcriptId;

		// Note: no transcript_id property is available since it hasn't been created yet
		// maybe get it from param and set it right after new note?
		// Get note count
		debug('Get note count');
		$noteCount = $this->_helper->db->getTable('TranscriptNote')->count(array('transcript_id' => $transcriptId));
		debug('Note Count: '. $noteCount);
	
		/* Note order. The note order should be set to one for the first
		 * note added, after that the order should increment by one from
		 * the last note: total_notes + 1;
		 */
		$note->order = $noteCount + 1;
		if( $noteCount == 0 ) {
			$note->order = 1;
		}
		
		if($this->getRequest()->isPost()) {
			$this->_processTranscriptNoteForm($note, 'add');
		} else {
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
	   	debug('NotesController->editAction');
	    $note = $this->_helper->db->findById();
	    $this->view->note = $note;
       $this->_processTranscriptNoteForm($note, 'edit');       
       // note automatically redirects to browse
       // I need to prevent this and redirect to either this edit page or the transcript page
    }

	
 	/**
	 * Process the form submission for add or edit actions.
	 */
	protected function _processTranscriptNoteForm($note, $mode)
	{
		debug('Processing transcript notes form');
		// don't display messages or save if not POST mode request
		if ($this->getRequest()->isPost()) {
			debug('is POST request');
			try {
			$note->setPostData($_POST);
			if ($note->save()) {
				if ('add' == $mode) {
					$this->_helper->flashMessenger(__('The new note "%s" has been saved.', $note->order), 'success');
				} else if ('edit' == $mode) {
					$this->_helper->flashMessenger(__('The edited note "%s" has been saved.', $note->order), 'success');
				}
				debug('redirecting to browse');
				// don't browse
				//$this->_helper->redirector('browse');       
			}
			// Catch validation errors.
				} catch (Omeka_Validate_Exception $e) {
					$this->_helper->flashMessenger($e);
				}
		 }
	}


	/**
	 * Generate and return the HMTL for the transcript form.
	 * This is typically be made available to the view.
	 * @param $note object
	 * @return string HTML
	 */
	protected function _getForm($note)
	{
	
		debug('_getForm');
		
	 	$formOptions = array('type' => 'item_transcript_transcript_note', 'hasPublicPage' => true);
        if ($note && $note->exists()) {
            $formOptions['record'] = $note;
        }
		$form = new Omeka_Form_Admin($formOptions);
		
	
	    $form->addElementToEditGroup(
            'textarea', 'note_text',
            array('id' => 'item-transcript-note-text',
                'cols'  => 50,
                'rows'  => 7,
                'value' => $note->text,
                'label' => __('Note'),
                'description' => __('Transcript note required.'),
                'required' => true
                )
        );

        $form->addElementToEditGroup(
            'text', 'order',
            array(
                'id' => 'item-transcript-note-order',
                'value' => $note->title,
                'label' => __('Order'),
                'description' => __('Order (required)'),
                'required' => true
            )
        );

		/** 
		 * Notes are part of a transcript, so they take their
		 * public and featured settings from the transcript, not
		 * individually.
		 */
        
	 	return $form;
	}


    protected function _getDeleteSuccessMessage($record)
    {
        return __('The note "%s" has been deleted.', $record->order);
    }


    /**
     * Notes are browsed on the transcript page.
     * Display 404 Not Found page for browse request.
     */
    public function browseAction()
    {
        throw new Omeka_Controller_Exception_404;
    }



    /**
     * Show transcript page.
     */
    public function showAction()
    {
    			debug('NotesController->showAction');
        
        /*
        if (!$note) {
            throw new Omeka_Controller_Exception_404;
        }
        */
        
        debug('About to get id parameter value');
        
        // Get the page object from the passed ID.
        $noteId = $this->_getParam('id');
        
        debug('Looking for transcript: '. $noteId);
        
        $note = $this->_helper->db->getTable('Transcript')->find($noteId);
        
        /* Restrict access to the page when it is not published.
        if (!$page->is_published 
            && !$this->_helper->acl->isAllowed('show-unpublished')) {
            throw new Omeka_Controller_Exception_403;
        }
        */
        
        $note_notes = findByTranscript($note);
        
		$this->view->transcript = $note;
		debug('About to render view');
		$this->render('show');
		
         parent::showAction();
    }


    /**
     * Handle the POST for the note add and edit actions.
     *
     * @param TranscriptNote $note
     * @param string $actionName
     * @param Transcript $note
     */
    protected function processPageForm($note, $actionName, $note = null)
    {
        $this->view->assign(compact('exhibit', 'actionName'));
        $this->view->transcript_note = $note;
        if ($this->getRequest()->isPost()) {
            $note->setPostData($_POST);
            try {
                $success = $note->save();
                return true;
            } catch (Exception $e) {
                $this->_helper->flashMessenger($e->getMessage(), 'error');
                return false;
            }
        }
    }
    
    /**
     * Use global settings for determining browse page limits.
     *
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
}