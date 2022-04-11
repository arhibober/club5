<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Youtube Gallery - Video Lists Table class
 */
class YoutubeGalleryTableVideolists extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */

		var $id = null;
		var $listname = null;
		var $videolist = null;
		var $catid = null;
		var $updateperiod = null;
		var $lastplaylistupdate = null;

        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_videolists', 'id', $db);
        }
}

?>