<?php
/**
 * Item Transcript
 *
 * @copyright 2014 by Steve Knoblock
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package ItemTranscript
 */


/**
 * ItemTranscript plugin.
 */
class ItemTranscriptPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
    	'install',
    	'uninstall',
    	'define_acl' /*,
    	'upgrade',
        'config',
		'config_form',
    	'initialize',
		'after_save_item',
        'admin_items_show_sidebar',
 		'public_items_show',
        'html_purifier_form_submission',*/
    );


    /**
     * @var array Filters for the plugin.
     */
    
    protected $_filters = array(
    	'admin_navigation_main'
    /*
    	'admin_items_form_tabs',
        'search_record_types',
        'page_caching_whitelist',
        'page_caching_blacklist_for_record'
        */
    );
	
	
    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
       // 'item_transcript_public_items_show' => null,
       // 'item_transcript_transcription_format' => null
    );
    


    /**
     * Install the plugin.
     */
    public function hookInstall()
    {

        // Create tables.
        $db = $this->_db;

        $sql = "
		   CREATE TABLE IF NOT EXISTS `{$db->prefix}item_transcript_transcripts` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) DEFAULT NULL,
				`description` TEXT,
				`entry` TEXT,
				`featured` TINYINT(1) DEFAULT 0,
				`public` TINYINT(1) DEFAULT 0,
				`added` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
				`modified` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
				`owner_id` INT UNSIGNED DEFAULT NULL,
				PRIMARY KEY  (`id`),
				KEY `public` (`public`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

        $sql = "
		   CREATE TABLE IF NOT EXISTS `{$db->prefix}item_transcript_transcript_entries` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`transcript_id` INT UNSIGNED NOT NULL,
				`text` MEDIUMTEXT,
				`order` SMALLINT UNSIGNED DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `transcript_id_order` (`transcript_id`, `order`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);    

        $sql = "
		   CREATE TABLE IF NOT EXISTS `{$db->prefix}item_transcript_transcript_notes` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`transcript_id` INT UNSIGNED NOT NULL,
				`text` MEDIUMTEXT,
				`order` SMALLINT UNSIGNED DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `transcript_id_order` (`transcript_id`, `order`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);    
        
        $this->_installOptions();

    }
    
    
    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {        
	    $db = $this->_db;

        // Drop transcripts table.
        $sql = "DROP TABLE IF EXISTS `{$db->prefix}transcripts`";
        $db->query($sql);

 		// Drop the transcript entries table.
        $sql = "DROP TABLE IF EXISTS `{$db->prefix}transcript_entries`";
        $db->query($sql);

        $this->_uninstallOptions();
    }
    
        
   /**
     * Display the plugin configuration form.
     */
    public static function hookConfigForm()
    {
        $publicAppendToItemsShow = get_option('item_relations_public_items_show');
        if (null == $publicAppendToItemsShow) {
            $publicAppendToItemsShow = self::DEFAULT_PUBLIC_ITEMS_SHOW;
        }
        
        $relationFormat = get_option('item_relations_relation_format');
        if (null == $relationFormat) {
            $relationFormat = self::DEFAULT_RELATION_FORMAT;
        }
        require dirname(__FILE__) . '/config_form.php';
    }
    
    
    /**
     * Handle the plugin configuration form.
     * 
     * @param array $params
     */
    public static function hookConfig()
    {
  	
  	// modeled on SimplePages handler
	//  set_option('simple_pages_filter_page_content', (int)(boolean)$_POST['simple_pages_filter_page_content']);
 
 	// Set options
        set_option('item_relations_public_items_show', 
        (int)(boolean)$_POST['item_relations_public_items_show']);
        set_option('item_relations_relation_format', 
                   $_POST['item_relations_relation_format']);
    }
    
    
    /**
     * Upgrade the plugin.
     *
     * @param array $args contains: 'old_version' and 'new_version'
     */
   	public function hookUpgrade($args)
    {
        //unimplemented
    }
    
    
   /**
     * Add the translations.
     */
    public function hookInitialize()
    {
       // unimplemented
    }


    /**
     * Add the Transcripts link to the admin main navigation.
     * 
     * @param array Navigation array.
     * @return array Filtered navigation array.
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Transcripts'),
            'uri' => url('item-transcript/transcripts') /*,
            'resource' => 'ItemRelations_Index',
            'privilege' => 'index' */
        );
        return $nav;
    }
    

    /**
     * Define the ACL.
     * 
     * @param Omeka_Acl
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        
        /* Just to explain for those unfamiliar with Zend ACL. indexResource represents the index page, the "landing page" for the plugin.
        */
        $indexResource = new Zend_Acl_Resource('ItemTranscript_Index');
        $acl->add($indexResource);
		$tResource = new Zend_Acl_Resource('ItemTranscript_Transcripts');
        $acl->add($tResource);
	
        //$acl->allow(array('super', 'admin'), array('ItemTranscript_Index', 'ItemTranscript_Transcripts'));
        //$acl->allow(null, 'ItemTranscript_Transcripts', 'show');
        //$acl->deny(null, 'ItemTranscript_Transcripts', 'show-unpublished');
    }

 
}