<?php
/**
 * @copyright
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */
 
/**
 * Transcript model.
 *
 * @package ItemTranscript
 */
class Transcript extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
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
     * Transcript credits.
     *
     * @var string
     */
    public $credits;
    
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
     * Public theme to use for this transcript.
     *
     * @var string
     */
    public $theme;

    /**
     * Options for this transcript's theme, serialized.
     *
     * @var string
     */
    public $theme_options;
    
    /**
     * URL slug for the transcript.
     *
     * @var string
     */
    public $slug;
    
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
     * Validation callback.
     */
    protected function _validate()
    {
        if (!strlen((string)$this->title)) {
            $this->addError('title', __('An transcript must be given a title.'));
        }

        if (strlen((string)$this->title) > 255) {
            $this->addError('title', __('The title for an transcript must be 255 characters or less.'));
        }

        if (strlen((string)$this->theme) > 30) {
            $this->addError('theme', __('The name of your theme must be 30 characters or less.'));
        }
    }

    /**
     * Delete callback.
     *
     * Delete all assigned pages when the transcript is deleted.
     */
    protected function _delete()
    {
        //get all the pages and delete them
        $pages = $this->getTable('Transcript')->findBy(array('transcript'=>$this->id));
        foreach($pages as $page) {
            $transcript->delete();
        }
        $this->deleteTaggings();
    }

    /**
     * After-save callback.
     *
     * Updates search text and page data for the transcript.
     *
     * @param array $args
     */
    protected function afterSave($args)
    {
        if (!$this->public) {
            $this->setSearchTextPrivate();
        }
        $this->setSearchTextTitle($this->title);
        $this->addSearchText($this->title);
        $this->addSearchText($this->description);
        
        if ($args['post']) {
            //Add the tags after the form has been saved
            $post = $args['post'];
            $this->applyTagString($post['tags']);
            if (isset($post['pages-hidden'])) {
                parse_str($post['pages-hidden'], $pageData);
                $this->_savePages($pageData['page']);
            }

            if (isset($post['pages-delete-hidden'])) {
                $pagesToDelete = explode(',', $post['pages-delete-hidden']);
                foreach ($pagesToDelete as $id) {
                    $page = $this->getTable('TranscriptPage')->find($id);
                    if ($page) {
                        $page->delete();
                    }
                }
            }
        }
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
