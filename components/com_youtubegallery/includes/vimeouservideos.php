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


class VideoSource_VimeoUserVideos
{
	public static function extractVimeoUserID($vimeo_user_link)
	{
		//http://vimeo.com/user13484491
		$matches=explode('/',$vimeo_user_link);
		
		if (count($matches) >3)
		{
			
			$userid = $matches[3];
			
			return str_replace('user','',$userid);
			
		}
				
	    return '';
	}
	
	public static function getVideoIDList($vimeo_user_link,$optionalparameters,&$userid)
	{
		
		//$optionalparameters_arr=explode(',',$optionalparameters);
		$videolist=array();
		
		//$spq=implode('&',$optionalparameters_arr);
		
		$userid=VideoSource_VimeoUserVideos::extractVimeoUserID($vimeo_user_link);
		
		
		// prepare our Consumer Key and Secret
		$consumer_key = '41349f38982966f25d9a2453dc83a0afe7043bb1';
		$consumer_secret = '83bcca5595aecb3b678997c89d991b0a6bb09191';

		require_once('vimeo_api.php');
		session_start();
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);
		$params = array();
        $params['user_id'] = $userid;
        $videos = $vimeo->call('videos.getAll',$params);
		
		foreach($videos->videos->video as $video)
		{
			$videolist[] = 'http://vimeo.com/'.$video->id;
		}
	
		return $videolist;
		
	}
	

}


?>