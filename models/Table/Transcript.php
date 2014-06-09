<?php
/**
 * @copyright Steve Knoblock 2014
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
 */
 
 
 /* Notes:
 
 Declaring 
 class Table_Transcript extends Omeka_Db_Table

causes a table 'transcripts' to be created on plugin install from the inflected (pluralized) name of the second part of the class name. I think.

I suppose applySearchFilters() is automatically called at some point by the framework. Can't we mark all these magic functions some way?

 */

/**
 * Transcript table class.
 *
 * @package ItemTranscript
 */
class Table_Transcript extends Omeka_Db_Table
{

	/**
	 * Apply search filters
	 */
    public function applySearchFilters($select, $params)
    {
        if(array_key_exists('public', $params)) {
            $this->filterByPublic($select, $params['public']);
        }
        
        if(array_key_exists('featured', $params)) {
            $this->filterByFeatured($select, $params['featured']);
        }
    }
    

    /**
     * Apply a filter to the transcriptions based on whether or not they are public
     * 
     * @param Zend_Db_Select
     * @param boolean Whether or not to retrieve only public transcriptions
     * @return void
     */
    public function filterByPublic($select, $isPublic)
    {         
        $isPublic = (bool) $isPublic; // this makes sure that empty strings and unset parameters are false

        //Force a preview of the public collections
        if ($isPublic) {
            $select->where('transcripts.public = 1');
        } else {
            $select->where('transcripts.public = 0');
        }
    }
    
    /**
     * Apply a filter to the transcriptions based on whether or not they are featured
     * 
     * @param Zend_Db_Select
     * @param boolean Whether or not to retrieve only public collections
     * @return void
     */
    public function filterByFeatured($select, $isFeatured)
    {
        $isFeatured = (bool) $isFeatured; // this make sure that empty strings and unset parameters are false
        
        //filter items based on featured (only value of 'true' will return featured collections)
        if ($isFeatured) {
            $select->where('transcripts.featured = 1');
        } else {
            $select->where('transcripts.featured = 0');
        }     
    }
    
    
    
    /**
     * Use SQL-based low-level permissions checking for exhibit queries.
     *
     * @return Omeka_Db_Select
     */
    /**
     * Apply permissions checks to all SQL statements retrieving collections from the table
     * 
     * @param string
     * @return void
     */
    public function getSelect()
    {
    
    /*
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('ItemTranscript_Transcripts');
        $permissions->apply($select, 'transcripts');
    */
    
        /*
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('Collections');
        $permissions->apply($select, 'collections');
        */
        
        return $select;
    }
    
}




