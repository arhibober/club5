<?php
/**
 * YoutubeGallery
 * @version 3.1.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);


class VideoSource_Vimeo
{

	public static function extractVimeoID($theLink)
	{
		
		preg_match('/http:\/\/vimeo.com\/(\d+)$/', $theLink, $matches);
		if (count($matches) != 0)
		{
			$vimeo_id = $matches[1];
			
			return $vimeo_id;
		}
		
		return '';
	}

	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		
		$theTitle='';
		$Description='';
		$theImage='';
				
		
		// prepare our Consumer Key and Secret
		$consumer_key = '41349f38982966f25d9a2453dc83a0afe7043bb1';
		$consumer_secret = '83bcca5595aecb3b678997c89d991b0a6bb09191';

		require_once('vimeo_api.php');
		session_start();
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);
		$params = array();
        $params['video_id'] = $videoid;
        $video_info = $vimeo->call('videos.getInfo',$params);
		
		
		if(isset($video_info))
		{
			if($customimage!='')
				$theImage=$customimage;
			else
				$theImage=$video_info->video[0]->thumbnails->thumbnail[1]->_content;
		
			if($customtitle=='')
				$theTitle=$video_info->video[0]->title;
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description=$video_info->video[0]->description;	
			else
				$Description=$customdescription;
			
			$keywords=array();
			
			if(isset($video_info->video[0]->tags->tag))
			{
				foreach($video_info->video[0]->tags->tag as $tag)
				{
					$keywords[]=$tag->_content;
				}
			}
			
			return array(
				'videosource'=>'vimeo',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>$video_info->video[0]->upload_date,
				'duration'=>$video_info->video[0]->duration,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>$video_info->video[0]->number_of_likes,
				'statistics_viewCount'=>$video_info->video[0]->number_of_plays,
				'keywords'=>implode(',',$keywords)
			);
		}
		else
			return array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>$theTitle,'description'=>$Description);
	}
	
	public static function renderVimeoPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';

		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$result='<iframe src="http://player.vimeo.com/video/'.$videoidkeyword.'?';
		
		if($options['color1']!='')
			$result.='color='.$options['color1'].'&amp;';
			
		if($options['showinfo']==0)
			$result.='portrait=0&amp;title=0&amp;byline=0&amp;';
		
		$result.='autoplay='.(int)$options['autoplay'].'&amp;loop='.(int)$options['repeat'];
		
		$result.='"';
		
		$border_width=3;
		
		if((int)$options['border']==1 and $options['color1']!='')
		{
			$width=((int)$width)-($border_width*2);
			$height=((int)$height)-($border_width*2);
		}
		
		$result.=''
			.' id="'.$playerid.'"'
			.' width="'.$width.'" height="'.$height.'" frameborder="'.(int)$options['border'].'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '');
			
		if((int)$options['border']==1 and $options['color1']!='')
			$result.=' style="border: '.$border_width.'px solid #'.$options['color1'].'"';
			
			
		$result.='></iframe>';
		
		return $result;
	}

}


?>
