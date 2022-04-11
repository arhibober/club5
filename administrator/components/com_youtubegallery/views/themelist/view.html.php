<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * YoutubeGallery ThemeList View
 */
class YoutubeGalleryViewThemeList extends JView
{
        /**
         * YoutubeGallery view display method
         * @return void
         */
        function display($tpl = null) 
        {
                // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;

                // Set the toolbar
                $this->addToolBar();
                
                $context= 'com_youtubegallery.themelist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
                $search			= JString::strtolower( $search );
                
                $lists['search']=$search;
                                
                
                
                $javascript = 'onchange="document.adminForm.submit();"';
                
                
                $this->assignRef('lists', $lists);
                
                
                // Display the template
                parent::display($tpl);
        }
        
        /**
         * Setting the toolbar
        */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_THEMELIST'));
                
                
                JToolBarHelper::addNewX('themeform.add');
                JToolBarHelper::editListX('themeform.edit');
                JToolBarHelper::customX( 'themelist.copyItem', 'copy.png', 'copy_f2.png', 'Copy', true);
				JToolBarHelper::customX( 'themelist.uploadItem', 'upload.png', 'upload_f2.png', 'Import', false);
		
                JToolBarHelper::deleteListX('', 'themelist.delete');
                
        }
        
        

        function array_insert(&$array, $insert, $position = -1)
        {
                $position = ($position == -1) ? (count($array)) : $position ;
                if($position != (count($array))) {
                $ta = $array;
                for($i = $position; $i < (count($array)); $i++)
                {
                        if(!isset($array[$i])) {
                                 die(print_r($array, 1)."\r\nInvalid array: All keys must be numerical and in sequence.");
                        }
                        $tmp[$i+1] = $array[$i];
                        unset($ta[$i]);
                }       
                $ta[$position] = $insert;
                $array = $ta + $tmp;

                } else {
                     $array[$position] = $insert;
                }
                ksort($array);
                return true;
        }   
        
}
