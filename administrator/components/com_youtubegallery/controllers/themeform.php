<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

 
 
/**
 * YoutubeGallery - themeform Controller
 */

class YoutubeGalleryControllerThemeForm extends JControllerForm
{
       /**
         * Proxy for getModel.
         */
    
	function save()
	{
		$task = JRequest::getVar( 'task');
		
		// get our model
		$model = $this->getModel('themeform');
		// attempt to store, update user accordingly
		
		if($task != 'save' and $task != 'apply')
		{
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
		
		
		if ($model->store())
		{
		
			if($task == 'save')
			{
				$link 	= 'index.php?option=com_youtubegallery&view=themelist';

			  }
			elseif($task == 'apply')
			{
	
				
				$link 	= 'index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$model->id;
			}
			
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_SAVED_SUCCESSFULLY' );
			
			$this->setRedirect($link, $msg);
		}
		else
		{

			$link 	= 'index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$model->id;
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}
	
	/**
	* Cancels an edit operation
	*/
	function cancelItem()
	{
		

		$model = $this->getModel('item');
		$model->checkin();

		
		
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist');
	}

	/**
	* Form for copying item(s) to a specific option
	*/
}
