<?php
/**
 * @copyright 2014 Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */


/**
 * Controller for Transcripts. 
 *
 * @package ItemTranscript
 */
 
 // naming convention: this is a plugin, so the plugin name is prefixed to the controller name, so if this is the Transcripts Controller for plugin ItemTranscript, the full name is ItemTranscript_TranscriptsController, if this were not a plugin, it would just be TranscriptsController
 
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
        
        
        // Get all the element sets that apply to the item.
       // $this->view->elementSets = $this->_getItemElementSets();
        
       
		if ($this->getRequest()->isPost()) {
        	$this->_processTranscriptForm($transcript, 'add');
       } else {
        $this->view->form = "FORM";
        $this->render('add');
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


	protected function _processTranscriptForm($transcript, $mode) {
	
		$transcript->setPostData($_POST);
        if ($transcript->save()) {
            if ('add' == $action) {
                $this->_helper->flashMessenger(__('The page "%s" has been added.', $transcript->title), 'success');
            }        
		}
	}            


    /**
     * Add a transcript.
     *
     * The URL param 'id' refers to the exhibit that will contain the page, and
     * 'previous' refers to an existing page the new one will be placed after.
     */
    public function addTranscriptAction()
    {
        $db = $this->_helper->db->getDb();
        $request = $this->getRequest();
        $transcriptId = $request->getParam('id');
      
        $success = $this->processTranscriptForm($transcriptentry, 'Add', $transcript);
        if ($success) {
            $this->_helper->flashMessenger("Changes to the transcripts's page were successfully saved!", 'success');
 
                $this->_helper->redirector->gotoRoute(array('action' => 'edit-page', 'id' => $exhibitPage->id), 'exhibitStandard');
            
            return;
        }

        $this->render('transcript-metadata-form');
    }
    
	/**
     * Handle the POST for the transcript add and edit actions.
     *
     * @param Transcript $transcript
     * @param string $actionName
     */
    protected function processTranscriptForm($transcript, $actionName)
    {
        $this->view->assign(compact('exhibit', 'actionName'));
        $this->view->exhibit_page = $transcript;
        if ($this->getRequest()->isPost()) {
            $transcript->setPostData($_POST);
            try {
                $success = $transcript->save();
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

    /**
     * Browse transcripts action.
     */
    public function browseAction()
    {
    	debug('In browseAction');
        $request = $this->getRequest();
        $sortParam = $request->getParam('sort');
        $sortOptionValue = get_option('item_transcript_sort_browse');

        if (!isset($sortParam)) {
            switch ($sortOptionValue) {
                case 'alpha':
                    $request->setParam('sort', 'alpha');
                    break;
                case 'recent':
                    $request->setParam('sort', 'recent');
                    break;
            }
        }

        parent::browseAction();
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
     * Show item in exhibit action.
     */
    public function showItemAction()
    {
        $itemId = $this->_getParam('item_id');
        $item = $this->_helper->db->findById($itemId, 'Item');

        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        if ($item && $exhibit->hasItem($item) ) {
            //Plugin hooks
            fire_plugin_hook('show_exhibit_item',  array('item' => $item, 'exhibit' => $exhibit));
            $this->view->exhibit = $exhibit;
            $this->_forward('show', 'items', 'default', array('id' => $itemId));
        } else {
            throw new Omeka_Controller_Exception_403(__('This item is not used within this exhibit.'));
        }
    }

    /**
     * Show transcript page.
     */
    public function showAction()
    {
    	debug('showAction');
        //$transcript = $this->_findByExhibitSlug();
        
        if (!$transcript) {
            throw new Omeka_Controller_Exception_404;
        }
        
        $params = $this->getRequest()->getParams();
        unset($params['action']);
        unset($params['controller']);
        unset($params['module']);
        //loop through the page slugs to make sure each one actually exists
        //then render the last one
        //pass all the pages into the view so the breadcrumb can be built there
        unset($params['slug']); // don't need the exhibit slug

        $transcriptTable = $this->_helper->db->getTable('Transcript');

       
        foreach($params as $slug) {
            if(!empty($slug)) {
                $exhibitPage = $transcriptTable->findBySlug($slug, $exhibit, $parentPage);
                if($exhibitPage) {
                    $parentPage = $exhibitPage;
                } else {
                    throw new Omeka_Controller_Exception_404;
                }
            }
        }

        fire_plugin_hook('show_transcript', array(
            'transcript' => $transcript
        ));

        $this->view->assign(array(
            'transcript' => $transcript
        ));
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

    /**
     * AJAX form for editing an attachment.
     */
    public function attachmentItemOptionsAction()
    {
        $attachment = new ExhibitBlockAttachment;
        $attachment->item_id = $this->_getParam('item_id');
        $attachment->file_id = $this->_getParam('file_id');
        $this->view->attachment = $attachment;
    }
}
