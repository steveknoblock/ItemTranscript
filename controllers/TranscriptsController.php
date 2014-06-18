<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */

debug('path: '.dirname(__FILE__));
//path: /usr/home/folks/public_html/folks.pairserver.com/omeka/omeka-2.1.4-clean/plugins/ItemTranscript/controllers
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
    public function editAction()
    {
	    debug('editAction');
        throw new Omeka_Controller_Exception_404;
    }

	
	/**
	 * Process the form submission for add or edit actions.
	 */
	protected function _processTranscriptForm($transcript, $mode)
	{
		debug('Processing transcript form');
		$transcript->setPostData($_POST);
        if ($transcript->save()) {
            if ('add' == $mode) {
                $this->_helper->flashMessenger(__('The transcript "%s" has been added.', $transcript->title), 'success');
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
    
    
/****/



    

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
     * Find an exhibit by its slug.
     *
     * @param string|null $exhibitSlug The slug to look up. If null, look up
     *  the slug from the current request.
     * @return Exhibit
     */
    protected function _findByExhibitSlug($exhibitSlug = null)
    {
        if (!$exhibitSlug) {
            $exhibitSlug = $this->_getParam('slug');
        }
        $exhibit = $this->_helper->db->getTable()->findBySlug($exhibitSlug);
        return $exhibit;
    }

    /**
     * List tags for exhibits action.
     */
    public function tagsAction()
    {
        $params = array_merge($this->_getAllParams(), array('type'=>'Exhibit'));
        $tags = $this->_helper->db->getTable('Tag')->findBy($params);
        $this->view->assign(compact('tags'));
    }


    /**
     * Show the summary page for an exhibit.
     */
    public function summaryAction()
    {
        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        fire_plugin_hook('show_exhibit', array('exhibit' => $exhibit));
        $this->view->exhibit = $exhibit;
    }

    /**
     * Custom redirect for addAction allowing a page to be added immediately.
     *
     * @param Exhibit $exhibit
     */
    protected function _redirectAfterAdd($exhibit)
    {
        if (array_key_exists('add_page', $_POST)) {
            $this->_helper->redirector->gotoRoute(array('action' => 'add-page', 'id' => $exhibit->id), 'exhibitStandard');
        } else if (array_key_exists('configure-theme', $_POST)) {
            $this->_helper->redirector->gotoRoute(array('action' => 'theme-config', 'id' => $exhibit->id), 'exhibitStandard');
        } else {
            $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
        }
    }

    /**
     * Custom redirect for editAction.
     *
     * @see _redirectAfterAdd
     * @param Exhibit $exhibit
     */
    protected function _redirectAfterEdit($exhibit)
    {
        $this->_redirectAfterAdd($exhibit);
    }

    /**
     * Theme configuration page for an exhibit.
     */
    public function themeConfigAction()
    {
        $exhibit = $this->_helper->db->findById();
        $themeName = (string)$exhibit->theme;

        // Abort if no specific theme is selected.
        if ($themeName == '') {
            $this->_helper->flashMessenger(__('You must specifically select a theme in order to configure it.'), 'error');
            $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
            return;
        }

        $theme = Theme::getTheme($themeName);
        $previousOptions = $exhibit->getThemeOptions();

        $form = new Omeka_Form_ThemeConfiguration(array(
            'themeName' => $themeName,
            'themeOptions' => $previousOptions
        ));
        $form->removeDecorator('Form');

        $themeConfigIni = $theme->path . DIRECTORY_SEPARATOR . 'config.ini';

        if (file_exists($themeConfigIni) && is_readable($themeConfigIni)) {

            try {
                $pluginsIni = new Zend_Config_Ini($themeConfigIni, 'plugins');
                $excludeFields = $pluginsIni->exclude_fields;
                $excludeFields = explode(',', $excludeFields);

            } catch(Exception $e) {
                $excludeFields = array();
            }

            foreach ($excludeFields as $excludeField) {
                trim($excludeField);
                $form->removeElement($excludeField);
            }
        }

        // process the form if posted
        if ($this->getRequest()->isPost()) {
            $configHelper = new Omeka_Controller_Action_Helper_ThemeConfiguration;

            if (($newOptions = $configHelper->processForm($form, $_POST, $previousOptions))) {
                $exhibit->setThemeOptions($newOptions);
                $exhibit->save();

                $this->_helper->_flashMessenger(__('The theme settings were successfully saved!'), 'success');
                $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
            } else {
                $this->_helper->_flashMessenger(__('There was an error on the form. Please try again.'), 'error');
            }
        }

        $this->view->assign(compact('exhibit', 'form', 'theme'));
    }





    /**
     * Edit an existing exhibit page.
     */
    public function editPageAction()
    {
        $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');

        $exhibit = $exhibitPage->getExhibit();

        if (!$this->_helper->acl->isAllowed('edit', $exhibit)) {
            throw new Omeka_Controller_Exception_403;
        }

        $success = $this->processPageForm($exhibitPage, 'Edit', $exhibit);
        if ($success) {
            $this->_helper->flashMessenger("Changes to the exhibit's page were successfully saved!", 'success');
            if (array_key_exists('add-another-page', $_POST)) {
                $this->_helper->redirector->gotoRoute(array('action' => 'add-page', 'id' => $exhibit->id, 'previous' => $exhibitPage->id), 'exhibitStandard');
            } else {
                $this->_helper->redirector->gotoRoute(array('action' => 'edit-page', 'id' => $exhibitPage->id), 'exhibitStandard');
            }
            return;
        }

        $this->render('page-form');
    }



    /**
     * Delete an exhibit page.
     */
    public function deletePageAction()
    {
        $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');
        $exhibit = $exhibitPage->getExhibit();
        if (!$this->_helper->acl->isAllowed('delete', $exhibit)) {
            throw new Omeka_Controller_Exception_403;
        }

        $exhibitPage->delete();
        $this->_helper->redirector->gotoUrl('exhibits/edit/' . $exhibit->id );
    }

    /**
     * AJAX action for checking exhibit page data.
     */
    public function validatePageAction()
    {
        try {
            $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');
        } catch (Exception $e) {
            $exhibitPage = new ExhibitPage;
            if (($exhibit_id = $this->getParam('exhibit_id'))) {
                $exhibitPage->exhibit_id = $exhibit_id;
            }
            if (($parent_id = $this->getParam('parent_id'))) {
                $exhibitPage->parent_id = $parent_id;
            }
        }

        $exhibitPage->setPostData($_POST);
        $exhibitPage->validateSlug();
        if ($exhibitPage->isValid()) {
            $data = array('success' => true);
        } else {
            $data = array(
                'success' => false,
                'messages' => $exhibitPage->getErrors()->get()
            );
        }

        $this->_helper->json($data);
    }

    /**
     * AJAX/partial form for a single block in an page.
     */
    public function blockFormAction()
    {
        $block = new ExhibitPageBlock;
        $block->layout = $this->getParam('layout');
        $block->order = $this->getParam('order');

        $this->view->block = $block;
    }

    /**
     * AJAX/partial form for a single attachment on a block.
     */
    public function attachmentAction()
    {
        $attachment = new ExhibitBlockAttachment;
        $attachment->item_id = $this->_getParam('item_id');
        $attachment->file_id = $this->_getParam('file_id');
        $attachment->caption = $this->_getParam('caption');

        $block = new ExhibitPageBlock;
        $block->order = $this->_getParam('block_index');

        $this->view->attachment = $attachment;
        $this->view->block = $block;
        $this->view->index = (int) $this->_getParam('index');
    }

}
