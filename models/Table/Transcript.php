<?php
/**
 * @copyright Steve Knoblock 2014
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ItemTranscript
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
    	debug('Table_Transcript->applySearchFilters');
    /*
        if(array_key_exists('public', $params)) {
            $this->filterByPublic($select, $params['public']);
        }
        
        if(array_key_exists('featured', $params)) {
            $this->filterByFeatured($select, $params['featured']);
        }
        */
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
    /*
        $isPublic = (bool) $isPublic; // this makes sure that empty strings and unset parameters are false

        //Force a preview of the public collections
        if ($isPublic) {
            $select->where('transcripts.public = 1');
        } else {
            $select->where('transcripts.public = 0');
        }
        */
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
    /*
        $isFeatured = (bool) $isFeatured; // this make sure that empty strings and unset parameters are false
        
        //filter items based on featured (only value of 'true' will return featured collections)
        if ($isFeatured) {
            $select->where('transcripts.featured = 1');
        } else {
            $select->where('transcripts.featured = 0');
        }   
        */  
    }
    
    
    /**
     * Use SQL-based low-level permissions checking for transcript queries.
     *
     * @return Omeka_Db_Select
     */

    public function getSelect()
    {
    	debug('Table_Transcript->getSelect');
       /*
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('Collections');
        $permissions->apply($select, 'collections');
        */
		$select = parent::getSelect();
        //$permissions = new Omeka_Db_Select_PublicPermissions('ItemTranscript_Transcripts');
        //$permissions->apply($select, 'transcripts');    
        return $select;
    }
    
}




