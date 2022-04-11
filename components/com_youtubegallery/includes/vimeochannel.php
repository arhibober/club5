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

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');


class VideoSource_VimeoChannel
{
	public static function extractVimeoUserID($vimeo_user_link)
	{
		//http://vimeo.com/channels/431663
		$matches=explode('/',$vimeo_user_link);
		
		if (count($matches) >4)
		{
			if($matches[3]!='channels')
				return ''; //not a channel link
			
			return (int)$matches[4];
			
		}
				
	    return '';
	}
	
	public static function getVideoIDList($vimeo_user_link,$optionalparameters,&$userid)
	{
		
		$videolist=array();
		
		$channel_id=VideoSource_VimeoChannel::extractVimeoUserID($vimeo_user_link);
		//echo '$channel_id='.$channel_id.'<br/>';
		
		
		// prepare our Consumer Key and Secret
		$consumer_key = '41349f38982966f25d9a2453dc83a0afe7043bb1';
		$consumer_secret = '83bcca5595aecb3b678997c89d991b0a6bb09191';

		require_once('vimeo_api.php');
		//echo 's';
		
		session_start();
		//echo 't';
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);
		//echo 'u';
		
		$params = array();
        $params['channel_id'] = $channel_id;
        $videos = $vimeo->call('channels.getVideos',$params);
		print_r($videos);
		//die;
		foreach($videos->videos->video as $video)
		{
			$videolist[] = 'http://vimeo.com/'.$video->id;
		}
	
		//print_r($videolist);
		//die;
		return $videolist;
		
	}
	

}


?>