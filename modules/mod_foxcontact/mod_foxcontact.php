<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fieldsbuilder.php');

// $params is a global variable not defined here. I can use it even in tmpl/default.php
// The helper is a class, so I have to pass che $params as a function parameter. For reference.
// Use: $params->get('name', 0)

// Validation of menu_item. It must be a number, and not to be empty
if (!is_numeric($targetmenu_id = $params->get('menu_item')))
	{
	echo(JText::_('MOD_VFC_MESSAGE_WRONG_MENUITEM'));
	return; // stop the execution
	}

jimport( 'joomla.application.menu' );
// $wholemenu   =& JMenu::getInstance();  // This doesn't work
$wholemenu =& JSite::getMenu();  // This one works 
// We're looking for parameters of the target menu
$cparams = $wholemenu->getParams($targetmenu_id);  // Component parameters

//$test = modFoxContactHelper::getHello($params);
$FieldsBuilder = new FieldsBuilder($cparams, $params, true);

$document =& JFactory::getDocument();
// Add a javascript
//$js = JURI::base().'modules/mod_foxcontact/js/custom.js';
//$document->addScript($js);

// Add a javascript from source code
//$js = "source of javascript;";
//$document->addScriptDeclaration($js);

// Add a stylesheet
$css = JURI::base().'components/com_foxcontact/css/neon.css';
$document->addStyleSheet($css);

// Add a style from source code
//$csscode = Helper::getStyle($params,$module->id);
//$document->addStyleDeclaration($csscode);

// Get the menu instance by id
$targetmenu = $wholemenu->getItem($targetmenu_id);

// If target page is the wrong type, this module can't work. Parameters for fields will not match.
if (!isset($targetmenu->component) || $targetmenu->component != 'com_foxcontact')
	{
	echo(JText::_('MOD_VFC_MESSAGE_WRONG_MENUITEM'));
	return;
	}

// If this page is the component page, it's better don't show the module.
$activemenu = $wholemenu->getActive();
// Let's do it directly, without ask it to the webmaster
// By exiting here, the module disappears completely
// only in debug environment it's alowed to display module and component in the same page
$application = &JFactory::getApplication();
//if ($activemenu->id == $targetmenu->id) return;
if ($activemenu->component == 'com_foxcontact' && !$application->getCfg("debug")) return;

// Get target link
$link = $targetmenu->link;

// Build it with the correct id
$router = JSite::getRouter();                                                
if ($router->getMode() == JROUTER_MODE_SEF) $link = 'index.php?Itemid=' . $targetmenu_id;
else $link .= '&Itemid=' . $targetmenu_id;

// Finally translate it in a SEF one if needed   
$link = JRoute::_($link);

// Debug
//print_r($targetmenu, false);

//require(JModuleHelper::getLayoutPath('mod_foxcontact'));
// Suggested by Julien Roubieu:
require(JModuleHelper::getLayoutPath('mod_foxcontact', $params->get('layout', 'default')));

