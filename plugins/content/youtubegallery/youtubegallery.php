<?php
/**
 * YoutubeGallery Joomla! 2.5 Native Component
 * @version 3.1.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');



class plgContentYoutubeGallery extends JPlugin
{

	public function onContentPrepare($context, &$article, &$params, $limitstart=0) {
		
		
		$count=0;
		$count+=$this->plgYoutubeGallery($article->text,true);
		$count+=$this->plgYoutubeGallery($article->text,false);

	}
	
	function strip_html_tags_textarea( $text )
	{
	    $text = preg_replace(
        array(
          // Remove invisible content
            '@<textarea[^>]*?>.*?</textarea>@siu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0",), $text );
     
		return $text ;
	}
	

	function plgYoutubeGallery(&$text_original, $byId)
	{
		
		$text=$this->strip_html_tags_textarea($text_original);
	
		$options=array();
		if($byId)
			$fList=$this->getListToReplace('youtubegalleryid',$options,$text);
		else
			$fList=$this->getListToReplace('youtubegallery',$options,$text);
			
	
		if(count($fList)==0)
			return 0;
		
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'render.php');
		
		
	
		for($i=0; $i<count($fList);$i++)
		{
			$replaceWith=$this->getYoutubeGallery($options[$i],$i,$byId);
			$text_original=str_replace($fList[$i],$replaceWith,$text_original);	
		}
	
		return count($fList);
	}



	function getYoutubeGallery($galleryparams,$count,$byId)
	{
		$result='';
		
		$opt=explode(',',$galleryparams);
		if(count($opt)<2)
			return '<p>YoutubeGallery Theme Not Set</p>';
	
		
		$db = JFactory::getDBO();
		
		if($byId)
		{
			$listid=(int)$opt[0];
			$themeid=(int)$opt[1];
						
			$query_list = 'SELECT * FROM #__youtubegallery_videolists WHERE id='.$listid.' LIMIT 1';
			$query_theme = 'SELECT * FROM #__youtubegallery_themes WHERE id='.$themeid.' LIMIT 1';
		}
		else
		{
			$listname=trim($opt[0]);
			$themename=trim($opt[1]);
			
			$query_list = 'SELECT * FROM #__youtubegallery_videolists WHERE listname="'.$listname.'" LIMIT 1';
			$query_theme = 'SELECT * FROM #__youtubegallery_themes WHERE themename="'.$themename.'" LIMIT 1';
		}
			
		//Video List data
		$db->setQuery($query_list);
		if (!$db->query())    die ( $db->stderr());
		$videolist_rows = $db->loadObjectList();
		if(count($videolist_rows)==0)
			return '<p>Video list not found</p>';
		$videolist_row=$videolist_rows[0];
		
		//Theme data
		$db->setQuery($query_theme);
		if (!$db->query())    die ( $db->stderr());
		$theme_rows = $db->loadObjectList();
		if(count($theme_rows)==0)
			return '<p>Theme not found</p>';
		$theme_row=$theme_rows[0];
		
		
		if(count($opt)>2)
		{
			//Old Style - overwrites some theme settings
			$theme_row->width=(int)$opt[1];
			$theme_row->height=(int)$opt[2];
			$theme_row->playvideo=(int)$opt[3];
			$theme_row->repeat=(int)$opt[4];
			$theme_row->fullscreen=(int)$opt[5];
			$theme_row->autoplay=(int)$opt[6];
			$theme_row->relatedvideos=(int)$opt[7];
			$theme_row->showinfo=(int)$opt[8];
			$theme_row->thumbbgcolor=$opt[9];
			$theme_row->columns=(int)$opt[10];
			$theme_row->showtitle=(int)$opt[11];
		}


		$misc=new YouTubeGalleryMisc;
		$misc->videolist_row = $videolist_row;
		$misc->theme_row = $theme_row;
	
		$total_number_of_rows=0;
							
		$misc->update_playlist();
								
		if($theme_row->openinnewwindow==4)
			$videoid=''; //Hot Video Switch
		else
			$videoid=JRequest::getVar('videoid');

		if($theme_row->playvideo==1 and $videoid!='')
			$theme_row->autoplay=1;

		$videoid_new=$videoid;
		$videolist=$misc->getVideoList_FromCache_From_Table($videoid_new,$total_number_of_rows);
					
		if($videoid=='')
		{
			if($theme_row->playvideo==1 and $videoid_new!='')
				$videoid=$videoid_new;
		}

		$renderer= new YouTubeGalleryRenderer;
		
		$result.=$renderer->render(
									$videolist,
									$videolist_row,
									$theme_row,
									$total_number_of_rows,
									$videoid
								 );

		return $result;
	
	}
	
	

	function getListToReplace($par,&$options,&$text)
	{
		$temp_text=preg_replace("/<textarea\b[^>]*>(.*?)<\/textarea>/i", "", $text);
		
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($temp_text))
				break;
		
			$ps=strpos($text, '{'.$par.'=', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($temp_text))
				break;
		
		$pe=strpos($text, '}', $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($temp_text,$ps,$pe-$ps+1);

			$options[]=substr($temp_text,$ps+$l,$pe-$ps-$l);
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		return $fList;
	}
	
}
?>