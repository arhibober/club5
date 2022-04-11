<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

 
/**
 * Youtube Gallery Theme Export View
 */
class YoutubeGalleryViewThemeImport extends JView
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */

        public function display($tpl = null) 
        {
                // Set the toolbar
                $this->addToolBar();
                parent::display($tpl);
                
        }

        protected function addToolBar() 
        {
                JRequest::setVar('hidemainmenu', true);
                
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_THEME_IMPORT'));
                JToolBarHelper::cancel('themeimport.cancel', 'JTOOLBAR_CLOSE');
        }
       
    
}


?>