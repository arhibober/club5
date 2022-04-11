<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * linkslist Model
 */
class YoutubeGalleryModelLinksList extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return string  An SQL query
         */
        protected function getListQuery()
        {
				$where=array();
				
				$context= 'com_youtubegallery.linkslist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
				$search			=strtolower(trim(preg_replace("/[^a-zA-Z0-9 ]/", "", $search)));
				
                
				if($search!='')
						$where[]='instr(`listname`,"'.$search.'")';
				
				$filter_category = (int)$mainframe->getUserStateFromRequest($context."filter_category",'filter_category','',	'integer' );
				
				
				if($filter_category!=0)
						$where[]='`catid`='.$filter_category;
				
				
				
                // Create a new query object.         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('*, #__youtubegallery_videolists.id AS id, listname,categoryname, count(#__youtubegallery_videos.id) AS number_of_videos');
                // From the Youtube Gallery table
                $query->from('#__youtubegallery_videolists');
				
				$query->leftjoin('#__youtubegallery_categories ON #__youtubegallery_categories.id=catid');
				$query->leftjoin('#__youtubegallery_videos ON #__youtubegallery_videos.listid=#__youtubegallery_videolists.id AND #__youtubegallery_videos.isvideo');
				
				$query->group('#__youtubegallery_videolists.id');
				
				if(count($where)>0)
						$query->where(implode(' AND ',$where));
				
                return $query;
        }
    
        
       	function ConfirmRemove()
        {
				JRequest::setVar('hidemainmenu', true);
				JToolBarHelper::title(JText::_( 'COM_YOUTUBEGALLERY_DELETE_VIDEOLIST_S' ));
		
				$cancellink='index.php?option=com_youtubegallery&view=linkslist';
		
				$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
				if(count($cids)==0)
						return false;
		
				//Get Table Name
		
				if (count( $cids ))
				{
						echo '<p style="font-size:20px;">Video List(s) ID: '.(count($cids)>1 ? implode(',',$cids) : $cids[0] ).'<br/><br/><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_CANCEL' ).'</a></p>';

						echo '
            <form action="index.php?option=com_youtubegallery" method="post" >
            <input type="hidden" name="task" value="linkslist.remove_confirmed" />
';
						$i=0;
						foreach($cids as $cid)
						        echo '<input type="hidden" id="cb'.$i.'" name="cid[]" value="'.$cid.'">';

						echo '
            <input type="submit" value="'.JText::_( 'COM_YOUTUBEGALLERY_YES_DELETE' ).'" class="button" />
            </form>
';
				}
				else
						echo '<p><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_ITEMS_SELECTED' ).'</a></p>';

        }
		
		public function getTable($type = 'LinksList', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
		
		function copyItem($cid)
		{


				$item = $this->getTable('videolists');
				
	    
		
				foreach( $cid as $id )
				{
			
		
						$item->load( $id );
						$item->id 	= NULL;
		
						$old_title=$item->listname;
						$new_title='Copy of '.$old_title;
		
						$item->listname 	= $new_title;
			
	
		
						if (!$item->check()) {
							return false;
						}
		
						if (!$item->store()) {
							return false;
						}
						$item->checkin();
							
				}//foreach( $cid as $id )
		
				return true;
		}//function copyItem($cid)
    
      
}
