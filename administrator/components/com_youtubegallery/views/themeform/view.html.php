<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');



 
/**
 * Youtube Gallery Theme Form View
 */
class YoutubeGalleryViewThemeForm extends JView
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {
                // get the Data
                $form = $this->get('Form');

                $item = $this->get('Item');

                $script = $this->get('Script');

                // Check for errors.

                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                
                // Assign the Data
                $this->form = $form;

                $this->item = $item;

                $this->script = $script;


                // Set the toolbar
                $this->addToolBar($this->item->readonly);
                parent::display($tpl);
                
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar($readyonly) 
        {
                JRequest::setVar('hidemainmenu', true);
                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_THEME_NEW') : JText::_('COM_YOUTUBEGALLERY_THEME_EDIT'));
                if(!$readyonly)
                {
                        JToolBarHelper::apply('themeform.apply');
                        JToolBarHelper::save('themeform.save');
                }
                JToolBarHelper::cancel('themeform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        
}


?>