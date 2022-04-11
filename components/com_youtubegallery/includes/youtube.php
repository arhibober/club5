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


class VideoSource_YouTube
{
	public static function extractYouTubeID($youtubeURL)
	{
		if(!(strpos($youtubeURL,'://youtu.be')===false) or !(strpos($youtubeURL,'://www.youtu.be')===false))
		{
			//youtu.be
			$list=explode('/',$youtubeURL);
			if(isset($list[3]))
				return $list[3];
			else
				return '';
		}
		else
		{
			//youtube.com
			$arr=YouTubeGalleryMisc::parse_query($youtubeURL);
			return $arr['v'];	
		}
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription, $thumbnailcssstyle)
	{
			
		$theTitle='';
		$Description='';
		$theImage='';
						
							
		if($customimage!='')
			$theImage=$customimage;
		else
			$theImage=VideoSource_YouTube::getYouTubeImageURL($videoid,$thumbnailcssstyle);
			
		$theData=VideoSource_YouTube::getYouTubeVideoData($videoid);

				
		if($customtitle!='')
			$theTitle=$customtitle;
		else
			$theTitle=$theData[0];

		if($customdescription!='')
			$Description=$customdescription;
		else
			$Description=$theData[1];
					
		return array(
				'videosource'=>'youtube',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>$theData[2],
				'duration'=>$theData[3],
				'rating_average'=>$theData[4],
				'rating_max'=>$theData[5],
				'rating_min'=>$theData[6],
				'rating_numRaters'=>$theData[7],
				'statistics_favoriteCount'=>$theData[8],
				'statistics_viewCount'=>$theData[9],
				'keywords'=>$theData[10]
				);
			
	}
	
	public static function getYouTubeImageURL($videoid,$thumbnailcssstyle)
	{
		
		
		if($thumbnailcssstyle == null)
			return 'http://img.youtube.com/vi/'.$videoid.'/default.jpg';
		
		//get bigger image if size of the thumbnail set;
		
		$a=str_replace(' ','',$thumbnailcssstyle);
		if(strpos($a,'width:')===false and strpos($a,'height:')===false)
			return 'http://img.youtube.com/vi/'.$videoid.'/default.jpg';
		else
			return 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
		
	}
	
	public static function getYouTubeVideoData($videoid)
	{
		if(phpversion()<5)
			return "Update to PHP 5+";
				
		//if(!ini_get('allow_url_fopen'))
			//return 'Set "allow_url_fopen=on" in PHP.ini file.';
				
		try{
			


			$url = 'http://gdata.youtube.com/feeds/api/videos/'.$videoid;
			
			//$url ='http://compass.com.pa';
			
			$doc = new DOMDocument;

			$htmlcode=YouTubeGalleryMisc::getURLData($url);

			if(strpos($htmlcode,'<?xml version')===false)
			{
				if(strpos($htmlcode,'Invalid id')===false)
				{
					//Cannot Connect to Youtube Server
					$pair=array('Cannot Connect to Youtube Server','','','0','0','0','0','0','0','0','');
				}
				else
				{
					//Invalid id, video not found
					$pair=array('Invalid id','Invalid id','','0','0','0','0','0','0','0','');
					
				}
				return $pair;
			}
			
			$doc->loadXML($htmlcode);
			
			
			
			$tplusd =$doc->getElementsByTagName("title")->item(0)->nodeValue;
			
			$tplusd.="<!--and-->";
			$tplusd.=$doc->getElementsByTagName("description")->item(0)->nodeValue;
			
			$tplusd.="<!--and-->";
			$tplusd.=$doc->getElementsByTagName("published")->item(0)->nodeValue;
			
			$tplusd.="<!--and-->";
			
		
			if($doc->getElementsByTagName("duration"))
			{
				if($doc->getElementsByTagName("duration")->item(0))
				{
					$tplusd.=$doc->getElementsByTagName("duration")->item(0)->getAttribute("seconds");	
				}
				
			}
			
			$RatingElement=$doc->getElementsByTagName("rating");
			if($RatingElement->length>0)
			{
				$re0=$RatingElement->item(0);
				$tplusd.="<!--and-->";
				$tplusd.=$re0->getAttribute("average");
				$tplusd.="<!--and-->";
				$tplusd.=$re0->getAttribute("max");
				$tplusd.="<!--and-->";
				$tplusd.=$re0->getAttribute("min");
				$tplusd.="<!--and-->";
				$tplusd.=$re0->getAttribute("numRaters");
			}
			else
				$tplusd.="<!--and-->0<!--and-->0<!--and-->0<!--and-->0";
			
			$StatElement=$doc->getElementsByTagName("statistics");
			if($StatElement->length>0)
			{
				$se0=$StatElement->item(0);
				$tplusd.="<!--and-->";
				$tplusd.=$se0->getAttribute("favoriteCount");
			
				$tplusd.="<!--and-->";
				$tplusd.=$se0->getAttribute("viewCount");
			}	
			else
				$tplusd.="<!--and-->0<!--and-->0";
				
			$tplusd.="<!--and-->";
				$tplusd.=$doc->getElementsByTagName("keywords")->item(0)->nodeValue;
			
			$value=$tplusd;

		
		}
		catch(Exception $e)
		{
			//$description='cannot get youtibe video data';
			return 'cannot get youtube video data';
		}
		
		$pair=explode('<!--and-->',$value);
		
		if(count($pair)!=11)
			$pair=array();
		
		return $pair;
	}
	


	
	public static function renderYouTubePlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$settings=array();
		
		$settings[]=array('autoplay',(int)$options['autoplay']);
		
		$settings[]=array('hl','en');
		
		
		if($options['fullscreen']!=0)
			$settings[]=array('fs','1');
		else
			$settings[]=array('fs','0');
			
			
		$settings[]=array('showinfo',$options['showinfo']);
		$settings[]=array('iv_load_policy','3');
		$settings[]=array('rel',$options['relatedvideos']);
		$settings[]=array('loop',(int)$options['repeat']);
		$settings[]=array('border',(int)$options['border']);
		
		if($options['color1']!='')
			$settings[]=array('color1',$options['color1']);
			
		if($options['color2']!='')
			$settings[]=array('color2',$options['color2']);

		if($options['controls']!='')
		{
			$settings[]=array('controls',$options['controls']);
			if($options['controls']==0)
				$settings[]=array('version',3);
			
		}
		
		
		if($theme_row->muteonplay)
			$options['playertype']=2; //becouse other types of player doesn't support this functionality.
		
		$playerapiid='ygplayerapiid_'.$videolist_row->id;
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		if($options['playertype']==2)
		{
			//Player with Flash availability check
			$settings[]=array('playerapiid','ygplayerapiid_'.$playerapiid);
			$settings[]=array('enablejsapi','1');
		}
		
	
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		$result='';
		
		$p=explode(';',$options['youtubeparams']);
		$playlist='';
		foreach($p as $v)
		{
			$pair=explode('=',$v);
			if($pair[0]=='playlist')
				$playlist=$pair[1];
		}
		
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
			$http='https://';
		else
			$http='http://';
			
		
		if($theme_row->nocookie)
			$youtubeserver=$http.'www.youtube-nocookie.com/';
		else
			$youtubeserver=$http.'www.youtube.com/';
		
		if($options['playertype']==1) //new HTML 5 player
		{
			//new player
			$result.='			<iframe width="'.$width.'" height="'.$height.'"'
				.' src="'.$youtubeserver.'embed/'.$videoidkeyword.'?'.$settingline.'"'
				.' frameborder="'.(int)$options['border'].'"'
				.' id="'.$playerid.'"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
				.($options['fullscreen']==0 ? '' : ' allowfullscreen')
				.'>'
			.'</iframe>';
		}
		elseif($options['playertype']==0 or $options['playertype']==3) //Flash AS3.0 Player
		{
			//Old player
			$pVersion=($options['playertype']==0 ? '3': '2');
			$result.=
			'<object '
				.' id="'.$playerid.'"'
				.' width="'.$width.'"'
				.' height="'.$height.'"'
				.' data="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'"'
				.' type="application/x-shockwave-flash"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '').'>'
				.'<param name="id" value="'.$playerid.'" />'
				.'<param name="movie" value="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'" />'
				.'<param name="wmode" value="transparent" />'
				.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.($playlist!='' ? '<param name="playlist" value="'.$playlist.'" />' : '');
			$result.='</object>';
		}

		elseif($options['playertype']==2 or $options['playertype']==4) //Flash Player with detection 3 and 2
		{
			$pVersion=($options['playertype']==2 ? '3': '2');
			$initial_volume=(int)$theme_row->volume;
			
			if($initial_volume>100)
				$initial_volume=100;
			if($initial_volume<-1)
				$initial_volume=-1;
	
			//Old player
			$result_head='
			<!-- Youtube Gallery - Youtube Flash Player With Detection -->
			<script src="'.$http.'www.google.com/jsapi" type="text/javascript"></script>
			<script src="'.$http.'ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js" type="text/javascript"></script>
			<script type="text/javascript">
			//<![CDATA[
			    google.load("swfobject", "2");
			    function onYouTubePlayerReady(playerId) {
			        ytplayer = document.getElementById("'.$playerid.'");
					'.($theme_row->muteonplay ? 'ytplayer.mute();' : '').'
					'.($initial_volume!=-1 ? 'setTimeout("changeVolumeAndPlay(\'"+playerId+"\')", 750);' : '').'
			    }
				'.($initial_volume!=-1 ? '
				function changeVolumeAndPlay(playerId) {
					ytplayer = document.getElementById("'.$playerid.'");
					if(ytplayer) {
					
						ytplayer.setVolume('.$initial_volume.');
				        '.($theme_row->autoplay ? 'ytplayer.playVideo();' : '').'
					}
				}   
				' : '').'
			//]]>
			</script>
			<!-- end of Youtube Gallery - Youtube Flash Player With Detection -->
			';
			
			$result.=
			'<div id="'.$playerapiid.'">You need Flash player 8+ and JavaScript enabled to view this video.</div>
			<script type="text/javascript">
			//<![CDATA[
			    var params = { allowScriptAccess: "always", wmode: "transparent" };
			    var atts = { id: "'.$playerid.'" };
			    swfobject.embedSWF("'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'","'.$playerapiid.'", "'.$width.'", "'.$height.'", "8", null, null, params, atts);
			//]]> 
			</script>
';
			
			$document = JFactory::getDocument();
			$document->addCustomTag($result_head);
			
		}

		

		
	

		return $result;
	}
	
	
	
	
}


?>