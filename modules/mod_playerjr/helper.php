<?php
/**
*Jw Player Module : mod_playerjr
* @version $Id$
* @package mod_playerjr
* @subpackage helper.php
* @author joomlarulez.
* @copyright (C) www.joomlarulez.com
* @license Limited  http://www.gnu.org/licenses/gpl.html
* @final 2.7.0
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class modplayerjr_Helper
{
  /**
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    function getParams(&$params)
    {
		// Module Parameters
		$params->def('PlaylistFlashinstall', '1');
		$params->def('Playlistjoomlarulezlink', '1');
		$params->def('Playlistplaylist', 'bottom');
		$params->def('Playlistcontrolbar', 'bottom');
		$params->def('Playlistplaylistsize', '180');
		$params->def('Playlistheight', '400');
		$params->def('Playlistwidth', '280');
		$params->def('Playlistbackcolor', '');
		$params->def('Playlistfrontcolor', '');
		$params->def('Playlistlightcolor', '');
		$params->def('Playlistscreencolor', '');
		$params->def('Playlistwmode', 'opaque');
		$params->def('Playlistautostart', '0');
		$params->def('Playlistbufferlength', '1');
		$params->def('Playlisticons', '1');
		$params->def('Playlistrepeat', 'none');
		$params->def('Playlistshuffle', '0');
		$params->def('Playlistsmoothing', '1');
		$params->def('Playliststretching', 'uniform');
		$params->def('Playlistvolume', '90');
		$params->def('Playliststart', '0');
		$params->def('Playliststreamer', '');
		$params->def('Playlistimage', '');
		
		//Adsolution
		$params->def('AdsolutionPluginEnabled', '0');
		$params->def('AdsolutionChannelcode', '');

		// Playlist Parameter
		$params->def('mod_plfile', 'http://www.joomlarulez.com/images/stories/playlist/big_buck_bunny/big_buck_bunny.xml');
		$params->def('mod_plselect', '0');

		return $params;
    }
}
?>