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
     * Use SQL-based low-level permissions checking for exhibit queries.
     *
     * @return Omeka_Db_Select
     */
    public function getSelect()
    {
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('ItemTranscript_Transcripts');
        $permissions->apply($select, 'transcripts');
        return $select;
    }
    
}




