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
        'item_relations_public_items_show' => null,
        'item_relations_relation_format' => null
    );
    


    /**
     * Install the plugin.
     */
    public function hookInstall()
    {

        // Create tables.
        $db = $this->_db;

        $sql = "
		   CREATE TABLE IF NOT EXISTS `{$db->prefix}transcripts` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) DEFAULT NULL,
				`description` TEXT,
				`credits` TEXT,
				`featured` TINYINT(1) DEFAULT 0,
				`public` TINYINT(1) DEFAULT 0,
				`theme` VARCHAR(30) DEFAULT NULL,
				`theme_options` TEXT,
				`slug` VARCHAR(30) NOT NULL,
				`added` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
				`modified` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
				`owner_id` INT UNSIGNED DEFAULT NULL,
				PRIMARY KEY  (`id`),
				UNIQUE KEY `slug` (`slug`),
				KEY `public` (`public`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

        $sql = "
		   CREATE TABLE IF NOT EXISTS `{$db->prefix}transcript_entries` (
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
    /*
    	What changes between 1.0 and this version?
    	
    	name of relations table
    */
    /* code example from SimplePages plugin
        $oldVersion = $args['old_version'];
        $newVersion = $args['new_version'];
        $db = $this->_db;
         if ($oldVersion < '2.0') {
         
            $db->query("ALTER TABLE `$db->SimplePagesPage` DROP `add_to_public_nav`");
            delete_option('simple_pages_home_page_id');
            
          	$sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `is_published` )";
            $db->query($sql);    
            
        	$sql = "ALTER TABLE `$db->SimplePagesPage` ADD `parent_id` INT UNSIGNED NOT NULL ";
            $db->query($sql);
        */
            
    }
    
    
   /**
     * Add the translations.
     */
    public function hookInitialize()
    {
       // unimplemented
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

	/*
        $acl->allow(array('super', 'admin'), array('ItemTranscript_Index', 'ItemTranscript_Relation'));
        $acl->allow(null, 'ItemTranscript_Relation', 'show');
        $acl->deny(null, 'ItemTranscript_Relation', 'show-unpublished');
     */   
    }
       
    
    /**
     * Filter the 'text' field of the simple-pages form, but only if the 
     * 'simple_pages_filter_page_content' setting has been enabled from within the
     * configuration form.
     * 
     * @param array $args Hook args, contains:
     *  'request': Zend_Controller_Request_Http
     *  'purifier': HTMLPurifier
     */
    public function hookHtmlPurifierFormSubmission($args)
    {
       // unimplemented
    }
    
    
    /**
     * Display item relations on the public items show page.
     */
    public static function hookPublicAppendToItemsShow()
    {
        if ('1' == get_option('item_relations_public_items_show')) {
            $item = get_current_record('item');
            item_relations_display_relations($item);
        }
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
            'uri' => url('transcripts') /*,
            'resource' => 'ItemRelations_Index',
            'privilege' => 'index' */
        );
        return $nav;
    }
    
    
    
    
    /**
     * Insert a transcript.
     * 
     * @param 
     * @param 
     * @param 
     * @return bool True: success; false: unsuccessful
     */
    public static function insertItemTranscript($transcript) {

        // Only numeric property IDs are valid.
        if (!is_numeric($propertyId)) {
            return false;
        }
        
        // Set the subject item.
        if (!($subjectItem instanceOf Item)) {
            $subjectItem = get_db()->getTable('Item')->find($subjectItem);
        }
        
        // Set the object item.
        if (!($objectItem instanceOf Item)) {
            $objectItem = get_db()->getTable('Item')->find($objectItem);
        }
        
        // Don't save the relation if the subject or object items don't exist.
        if (!$subjectItem || !$objectItem) {
            return false;
        }
        
        $itemTranscript = new ItemTranscript;
        $itemTranscript->subject_item_id = $subjectItem->id;
        $itemTranscript->property_id = $propertyId;
        $itemTranscript->object_item_id = $objectItem->id;
        $itemTranscript->save();
        
        return true;
    }
    

    /**
     * Prepare special variables before saving the form.
     */
    protected function beforeSave($args) {
    }
    

   /**
     * Save the item relations after saving an item add/edit form.
     * 
     * @param Omeka_Record $record
     * @param array $post
     */
    public function hookAfterSaveItem($args)
    {
    //debug("IN hookAfterSaveItem()");

    	$record = $args['record'];
    	$post = $args['post'];
    
        $db = get_db();
        
        if (!($record instanceof Item)) {
            return;
        }
        
        // Save item relations.
        foreach ($post['item_relations_property_id'] as $key => $propertyId) {
            
            $insertedItemRelation = self::insertItemRelation(
                $record, 
                $propertyId, 
                $post['item_relations_item_relation_object_item_id'][$key]
            );
            if (!$insertedItemRelation) {
                continue;
            }
        }
        
        // Delete item relations.
        if (isset($post['item_relations_item_relation_delete'])) {
            foreach ($post['item_relations_item_relation_delete'] as $itemRelationId) {
                $itemRelation = $db->getTable('ItemRelationsRelation')->find($itemRelationId);
                // When an item is related to itself, deleting both relations 
                // simultaniously will result in an error. Prevent this by 
                // checking if the item relation exists prior to deletion.
                if ($itemRelation) {
                    $itemRelation->delete();
                }
            }
        }
    }

 
}