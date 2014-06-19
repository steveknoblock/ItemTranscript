<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */

require_once dirname(__FILE__) . '../../helpers/TranscriptParse.php';

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
        //throw new Omeka_Controller_Exception_404;


        // Create a new transcript.
       	$transcript = new Transcript;
        
        debug('new Transcript');
        
        // Get all the element sets that apply to the item.
       // $this->view->elementSets = $this->_getItemElementSets();
        
       
		if ($this->getRequest()->isPost()) {
			debug('Processing request');
			$this->_processTranscriptForm($transcript, 'add');
		} else {
			debug('Getting form');
			$tmp = $this->_getForm($transcript);
			debug('Displaying form');
			$this->view->form = $tmp;
			$this->render('transcript-form');
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
	    debug('editAction');
        $transcript = $this->_helper->db->findById();
        $this->view->form = $this->_getForm($transcript);
        $this->_processTranscriptForm($transcript, 'edit');
	    //$this->render('transcript-form');
    }

	
	/**
	 * Process the form submission for add or edit actions.
	 */
	protected function _processTranscriptForm($transcript, $mode)
	{
		debug('Processing transcript form');
		// don't display messages or save if not POST mode request
	if ($this->getRequest()->isPost()) {
		$transcript->setPostData($_POST);
        if ($transcript->save()) {
            if ('add' == $mode) {
                $this->_helper->flashMessenger(__('The new transcript "%s" has been saved.', $transcript->title), 'success');
            }
        if ('edit' == $mode) {
                $this->_helper->flashMessenger(__('The edited transcript "%s" has been saved.', $transcript->title), 'success');
            }        
		}
	 }
	}            


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
        
	 	return $form;
	
	}



    protected function _getDeleteSuccessMessage($record)
    {
        return __('The page "%s" has been deleted.', $record->title);
    }


    /**
     * Browse transcripts action.
     */
    public function browseAction()
    {
    	debug('In browseAction');
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
    	debug('showAction');
        
        /*
        if (!$transcript) {
            throw new Omeka_Controller_Exception_404;
        }
        */
        
        debug('About to get id parameter value');
        
        // Get the page object from the passed ID.
        $transcriptId = $this->_getParam('id');
        
        debug('Looking for transcript: '. $transcriptId);
        
        $transcript = $this->_helper->db->getTable('Transcript')->find($transcriptId);
        //var_dump($foo);
       	// 'ItemTranscript_Transcript is converted to
        // omeka_item_transcript_transcripts
        
        /* Restrict access to the page when it is not published.
        if (!$page->is_published 
            && !$this->_helper->acl->isAllowed('show-unpublished')) {
            throw new Omeka_Controller_Exception_403;
        }
        */
		$this->view->transcript = $transcript;
		debug('About to render view');
		$this->render('show');
        
        
         parent::showAction();
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
