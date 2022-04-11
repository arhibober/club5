<?php
/**
* @Copyright Copyright (C) 2010- ... Vijay Padsumbiya
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * mod_youtubeshowcase is Commercial software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

	
// no direct access
defined('_JEXEC') or die('Restricted access');
// Include the whosonline functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$container_width = $params->get( 'container_width', 0 );
$left_margin = $params->get( 'left_margin', 0 );
$top_margin = $params->get( 'top_margin', 0 );

$video_panel_width = $params->get( 'video_panel_width', 0 );
$video_panel_height = $params->get( 'video_panel_height', 0 );
$thumb_panel_width = $params->get( 'thumb_panel_width', 0 );
$thumb_panel_height = $params->get( 'thumb_panel_height', 0 );

$video_auto_play = $params->get( 'video_auto_play', 0 );
$allow_full_screen = $params->get( 'allow_full_screen', 0 );

$youtube_video_number = $params->get( 'youtube_video_number', 0 );

$youtube_url1 = $params->get( 'youtube_url1', 0 );
$youtube_video1_title = $params->get( 'youtube_video1_title', 0 );

$youtube_url2 = $params->get( 'youtube_url2', 0 );
$youtube_video2_title = $params->get( 'youtube_video2_title', 0 );

$youtube_url3 = $params->get( 'youtube_url3', 0 );
$youtube_video3_title = $params->get( 'youtube_video3_title', 0 );

$youtube_url4 = $params->get( 'youtube_url4', 0 );
$youtube_video4_title = $params->get( 'youtube_video4_title', 0 );

$youtube_url5 = $params->get( 'youtube_url5', 0 );
$youtube_video5_title = $params->get( 'youtube_video5_title', 0 );

$youtube_url6 = $params->get( 'youtube_url6', 0 );
$youtube_video6_title = $params->get( 'youtube_video6_title', 0 );

$youtube_url7 = $params->get( 'youtube_url7', 0 );
$youtube_video7_title = $params->get( 'youtube_video7_title', 0 );

$youtube_url8 = $params->get( 'youtube_url8', 0 );
$youtube_video8_title = $params->get( 'youtube_video8_title', 0 );

$youtube_url9 = $params->get( 'youtube_url9', 0 );
$youtube_video9_title = $params->get( 'youtube_video9_title', 0 );

$youtube_url10 = $params->get( 'youtube_url10', 0 );
$youtube_video10_title = $params->get( 'youtube_video10_title', 0 );

$content = modYoutubeShowcaseMenuHelper::getStart( $params );
require( JModuleHelper::getLayoutPath( 'mod_youtubeshowcase' ) );
?>

