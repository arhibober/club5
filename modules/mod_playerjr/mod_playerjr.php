<?php
/**
*Jw Player Module : mod_playerjr
* @version mod_playerjr$Id$
* @package mod_playerjr
* @subpackage mod_playerjr.php
* @author joomlarulez.
* @copyright (C) www.joomlarulez.com
* @license Limited  http://www.gnu.org/licenses/gpl.html
* @final 2.7.0
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$params = modplayerjr_Helper::getParams( $params );

// Module Parameters
$playersite  = JURI::root();
$document = &JFactory::getDocument();
$moduleclasspl_sfx = $module->id;
$moduleflash_sfx = $params->get('PlaylistFlashinstall');
if ($moduleflash_sfx == '1')
{
	$is_modplayer_flash = "You must have <a href=\"http://get.adobe.com/flashplayer\">the Adobe Flash Player</a> installed to view this player.";
}
ELSE
{
	$is_modplayer_flash = "";
}

// set array flashvars
$is_modplayer_flashvars = array();

// Var Parameters
$is_modplayer_playlist_joomlarulezlink = $params->get('Playlistjoomlarulezlink');

//set  flashwars with no default mode
$count = 5;
$index1 = 'height';
$index_defaut1 = '240';
$index2 = 'width';
$index_defaut2 = '320';
$index3 = 'wmode';
$index_defaut3 = 'opaque';
$index4 = 'playlistsize';
$index_defaut4 = '180';
for ($i = 1; $i < $count; $i++)
{
	$is_modplayer_flashvars[${'index'.$i}] = $params->get("Playlist".${'index'.$i}, ${'index_defaut'.$i});
}

//set  simple flaswars
$count = 19;
$index1 = 'playlist';
$index_defaut1 = 'none';
$index2 = 'controlbar';
$index_defaut2 = 'bottom';
$index3 = 'bandwidth';
$index_defaut3 = '5000';
$index4 = 'bufferlength';
$index_defaut4 = '1';
$index5 = 'repeat';
$index_defaut5 = 'none';
$index6 = 'stretching';
$index_defaut6 = 'uniform';
$index7 = 'volume';
$index_defaut7 = '90';
$index8 = 'backcolor';
$index_defaut8 = '';
$index9 = 'frontcolor';
$index_defaut9 = '';
$index10 = 'lightcolor';
$index_defaut10 = '';
$index11 = 'screencolor';
$index_defaut11 = '';
$index12 = 'streamer';
$index_defaut12 = '';
$index13 = 'start';
$index_defaut13 = '0';
$index14 = 'image';
$index_defaut14 = '';
//set  true false flaswars
$index15 = 'autostart';
$index_defaut15 = '0';
$index16 = 'shuffle';
$index_defaut16 = '0';
$index17 = 'smoothing';
$index_defaut17 = '1';
$index18 = 'icons';
$index_defaut18 = '1';
for ($i = 1; $i < $count; $i++)
{
	$is_modplayer_playlist_var = $params->get("Playlist".${'index'.$i}, ${'index_defaut'.$i});
	if ($is_modplayer_playlist_var != '' && $is_modplayer_playlist_var != ${'index_defaut'.$i})
	{
		if ($i > 14) //true false
		{
			$is_modplayer_flashvars[${'index'.$i}] = $is_modplayer_playlist_var ? 'true' : 'false' ;
		}
		else
		{
			$is_modplayer_flashvars[${'index'.$i}] = $is_modplayer_playlist_var;
		}
	}
}

// plugin set
$is_modplayer_plugin_array = array();

// Plugin adsolution
$index = 'ltas';
$index2 = 'ltas.cc';
$is_modplayer_playlist_adschannelcode = $params->get('AdsolutionChannelcode');
$is_modplayer_playlist_adsenabled = $params->get('AdsolutionPluginEnabled');
if($is_modplayer_playlist_adsenabled == '1' && $is_modplayer_playlist_adschannelcode != '')
{
	$is_modplayer_playlist_adsenabled = true;
	$is_modplayer_plugin_array[] = $index;
	$is_modplayer_flashvars["ltas.cc"] = $is_modplayer_playlist_adschannelcode;
}

//Insert the plugin array and clean the string flaswars
$index = 'plugins';
$is_modplayer_plugin = implode(',', $is_modplayer_plugin_array);
unset($is_modplayer_flashvars[$index]);
if (!(empty($is_modplayer_plugin)))
{
	$is_modplayer_flashvars[$index] = $is_modplayer_plugin;
}

// Playlist Parameter
$is_modplayer_playlist = $params->get('mod_plfile');

//url encode
$is_modplayer_playlist = urlencode($is_modplayer_playlist);
$is_modplayer_playlist_select = $params->get('mod_plselect');
if($is_modplayer_playlist_select == '1') 
{
	$is_modplayer_playlisttype = "file";
}		
ELSEIF($is_modplayer_playlist_select == '0') 
{
	$is_modplayer_playlisttype = "playlistfile";
}
ELSE
{
	$is_modplayer_playlisttype = "file";
}

require( JModuleHelper::getLayoutPath( 'mod_playerjr' ) );
?>