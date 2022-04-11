<?php
/**
 * youtubegallery Joomla! 2.5 Native Component
 * @version 3.1.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'render.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

$listid=(int)$params->get( 'listid' );
$themeid=(int)$params->get( 'themeid' );

$align='';

if($listid!=0 and $themeid!=0)
{
	$misc=new YouTubeGalleryMisc;
	
    
	if(!$misc->getVideoListTableRow($listid))
		echo '<p>No video found</p>';
	
	if(!$misc->getThemeTableRow($themeid))
		echo '<p>No video found</p>';
			
	$firstvideo='';
	$youtubegallerycode='';
	$total_number_of_rows=0;
							
	$misc->update_playlist();

	if($theme_row->openinnewwindow==4)
		$videoid=''; //Hot Video Switch
	else
		$videoid=JRequest::getVar('videoid');
	
	if($misc->theme_row->playvideo==1 and $videoid!='')
		$misc->theme_row->autoplay=1;
	
	$videoid_new=$videoid;
	$videolist=$misc->getVideoList_FromCache_From_Table($videoid_new,$total_number_of_rows);
	
	if($videoid=='')
	{
		if($misc->theme_row->playvideo==1 and $videoid_new!='')
			$videoid=$videoid_new;
	}
	
	$renderer= new YouTubeGalleryRenderer;
	
	$gallerymodule=$renderer->render(
		$videolist,
		$misc->videolist_row,
		$misc->theme_row,
		$total_number_of_rows,
		$videoid
	);

	//$app		= JFactory::getApplication();
    
    $align=$params->get( 'galleryalign' );
	
    switch($align)
    {
       	case 'left' :
       		$youtubegallerycode.= '<div style="float:left;position:relative;">'.$gallerymodule.'</div>';
   		break;

		case 'center' :
       		$youtubegallerycode.= '<div style="width:'.$misc->theme_row->width.'px;margin-left:auto;margin-right:auto;position:relative;">'.$gallerymodule.'</div>';
   		break;
        	
       	case 'right' :
      		$youtubegallerycode.= '<div style="float:right;position:relative;">'.$gallerymodule.'</div>';
   		break;
	
       	default :
       		$youtubegallerycode.= $gallerymodule;
   		break;
	
	}//switch($align)
	
	echo $youtubegallerycode;
	
	
}
else
	echo '<p>Video list or Theme not selected</p>';






?>
