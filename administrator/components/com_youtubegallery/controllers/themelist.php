<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Youtube Gallery - ThemeList Controller
 */
class YoutubeGalleryControllerThemeList extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         */
		function display()
		{
				JRequest::setVar( 'view', 'themelist');
				parent::display();
		}
		
        public function getModel($name = 'ThemeList', $prefix = 'YoutubeGalleryModel') 
        {
                $model = parent::getModel($name, $prefix, array('ignore_request' => true));
                return $model;
        }
        
 
        public function delete()
        {
                
        	// Check for request forgeries
        	JRequest::checkToken() or jexit( 'Invalid Token' );
        	
            $cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

            if (count($cid)<1) {

                $this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', JText::_('COM_YOUTUBEGALLERY_NO_ITEMS_SELECTED'),'error' );
                
        		return false;
        	}
		
        	$model = $this->getModel();
        	
        	$model->ConfirmRemove();
        }
	
        public function remove_confirmed()
        {
		
        	// Get some variables from the request
        	
        	$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );


        	if (count($cid)<1) {
        		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', JText::_('COM_YOUTUBEGALLERY_NO_THEME_SELECTED'),'error' );
        		return false;
        	}

        	$model = $this->getModel('themeform');
        	if ($n = $model->deleteTheme($cid)) {
        		$msg = JText::sprintf( 'COM_YOUTUBEGALLERY_THEME_S_DELETED', $n );
        		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', $msg );
        	} else {
        		$msg = $model->getError();
        		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', $msg,'error' );
        	}
		
        }
		
		public function copyItem()
		{
				
		    $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	    
		    $model = $this->getModel('themelist');
	    
	    
		    if($model->copyItem($cid,$msg))
		    {
				$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_COPIED_SUCCESSFULLY' );
				$link 	= 'index.php?option=com_youtubegallery&view=themelist';
				$this->setRedirect($link, $msg);
		    }
		    else
		    {
				if($msg=='')
						$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_COPY' );
						
				$link 	= 'index.php?option=com_youtubegallery&view=themelist';
				$this->setRedirect($link, $msg,'error');
		    }
	    
		    
		}
		
		public function uploadItem()
		{
				$link 	= 'index.php?option=com_youtubegallery&view=themeimport';
					
				$this->setRedirect($link, '');
		}



}

?>