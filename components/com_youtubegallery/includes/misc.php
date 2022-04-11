<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);


class YouTubeGalleryMisc
{
	var $videolist_row;
	var $theme_row;
	
	function getVideoListTableRow($listid)
	{
		$db = JFactory::getDBO();
	
		//Load Video List
		$query = 'SELECT * FROM `#__youtubegallery_videolists` WHERE `id`='.$listid.' LIMIT 1';
		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());
	
		$videolist_rows = $db->loadObjectList();
			
		if(count($videolist_rows)==0)
			return false;//'<p>No video list found</p>';
			
		$this->videolist_row=$videolist_rows[0];
		return true;
	}
	
	function getThemeTableRow($themeid)
	{
		$db = JFactory::getDBO();

		//Load Theme Row
		$query = 'SELECT `id`, `themename`, `width`, `height`, `playvideo`, `repeat`, `fullscreen`, `autoplay`, `related`, `showinfo`, `bgcolor`, `cols`,
		`showtitle`, `cssstyle`, `navbarstyle`, `thumbnailstyle`, `linestyle`, `showlistname`, `listnamestyle`, `showactivevideotitle`, `activevideotitlestyle`,
		`description`, `descr_position`, `descr_style`, `color1`, `color2`, `border`, `openinnewwindow`, `rel`, `hrefaddon`, `pagination`, `customlimit`,
		`controls`, `youtubeparams`, `playertype`, `useglass`, `logocover`, `customlayout`,  `prepareheadtags`, `muteonplay`
		`volume`, `orderby`, `customnavlayout`, `responsive`, `mediafolder`, `readonly`, `headscript`, `themedescription`, `nocookie`
		FROM `#__youtubegallery_themes` WHERE `id`='.$themeid.' LIMIT 1';


		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());

		$theme_rows = $db->loadObjectList();
			
		if(count($theme_rows)==0)
			return false;//'<p>No video found</p>';
			
		$this->theme_row=$theme_rows[0];
		return true;
	}
	
	
	function formVideoList($rawList,&$firstvideo)
	{
		$gallery_list=array();
		
		$main_ordering=10000; //10000 step
		
		foreach($rawList as $b)
		{
			
			$b=str_replace("\n",'',$b);
			$b=trim(str_replace("\r",'',$b));
			
			$listitem=$this->csv_explode(',', $b, '"', false);
			
			$theLink=trim($listitem[0]);
			
			if(!(strpos($theLink, '/embed/')===false))
			{
				//Convert Embed links to Address bar version
				$theLink=str_replace('www.youtube.com/embed/','youtu.be/',$theLink);
				$theLink=str_replace('youtube.com/embed/','youtu.be/',$theLink);
			}
			
			
			$vsn=$this->getVideoSourceName($theLink);
				
			if(isset($listitem[4]))
				$specialparams=$listitem[4];
			else
				$specialparams='';
				
			if($vsn=='youtubeplaylist')
			{
				require_once('youtubeplaylist.php');
				$newlist=VideoSource_YoutubePlaylist::getVideoIDList($theLink, $specialparams, $playlistid);
			}
			elseif($vsn=='youtubeuserfavorites')
			{
				require_once('youtubeuserfavorites.php');
				$newlist=VideoSource_YoutubeUserFavorites::getVideoIDList($theLink, $specialparams, $playlistid);
			}
			elseif($vsn=='youtubeuseruploads')
			{
				require_once('youtubeuseruploads.php');
				$newlist=VideoSource_YoutubeUserUploads::getVideoIDList($theLink, $specialparams, $playlistid);
			}
			elseif($vsn=='youtubestandard')
			{
				require_once('youtubestandard.php');
				$newlist=VideoSource_YoutubeStandard::getVideoIDList($theLink, $specialparams, $playlistid);
			}
			elseif($vsn=='vimeouservideos')
			{
				require_once('vimeouservideos.php');
				$newlist=VideoSource_VimeoUserVideos::getVideoIDList($theLink, $specialparams, $playlistid);
			}
			elseif($vsn=='vimeochannel')
			{
				require_once('vimeochannel.php');
				$newlist=VideoSource_VimeoChannel::getVideoIDList($theLink, $specialparams, $playlistid);
			}			

			if($vsn=='youtubeuseruploads' or $vsn=='youtubestandard' or $vsn=='youtubeplaylist' or $vsn=='youtubeuserfavorites' or $vsn=='vimeouservideos' or $vsn=='vimeochannel') 
			{
				if($vsn=='youtubeuseruploads' or $vsn=='youtubestandard' or $vsn=='youtubeplaylist' or $vsn=='youtubeuserfavorites')
					$video_source='youtube';
				
				if($vsn=='vimeouservideos')
					$video_source='vimeo';
					
				if($vsn=='vimeochannel')
					$video_source='vimeo';
				
				$new_List_Clean=array();
			
				$ordering=1;
				foreach($newlist as $theLinkItem)
				{
					

					$item=$this->GrabVideoData($theLinkItem,$video_source);

					if($item['videoid']!='')
					{
						if($firstvideo=='')
							$firstvideo=$item['videoid'];
						
						$item['ordering']=$main_ordering+$ordering;	
						$new_List_Clean[]=$item;
						
						
						$ordering++;
						
					}	
						
				}
				
				$item=array(
				'videosource'=>$vsn,
				'videoid'=>$playlistid,
				'imageurl'=>'',
				'title'=>'',
				'description'=>'',
				'specialparams'=>$specialparams,
				'count'=>count($new_List_Clean),
				'link'=>'',
				'ordering'=>$main_ordering
				
				);
				
				$gallery_list[]=$item;
				$gallery_list=array_merge($gallery_list,$new_List_Clean);
			}
			elseif($vsn=='videolist')
			{
				$linkPair=explode(':',$theLink);
		
				if(isset($linkPair[1]))
				{
					if(trim($linkPair[1])=='all')
						$vID=-1;
					else
						$vID=(int)$linkPair[1];
				
					$item=array(
						'videosource'=>$vsn,
						'videoid'=>$vID,
						'isvideo'=>"0",
						'imageurl'=>'',
						'title'=>'',
						'description'=>'',
						'specialparams'=>'',
						'count'=>'',
						'link'=>'',
						'ordering'=>''
					);
					$gallery_list[]=$item;
				}

			}
			else
			{
				$item=$this->GrabVideoData($listitem,$vsn);
				if($item['videoid']!='')
				{
					if($firstvideo=='')
							$firstvideo=$item['videoid'];
							
					$item['ordering']=$main_ordering;
					$gallery_list[]=$item;
				}
			}
			
			$main_ordering+=10000;
			
		}//foreach($rawList as $b)

		
		return $gallery_list;
	}
	

	
	
	
	
	function GrabVideoData($listitem,$vsn,$videoid_optional='')
	{
	
			$query_video_host=true;
			
			
			//Return Video Data Array separated with commma
		
			//extract title if it's needed for navigation (thumbnail) or for active video.
			$videoitem=array();
		
			$customtitle='';
			$customdescription='';
			$customimage='';
		
			if(is_array($listitem))
			{
				$theLink=trim($listitem[0]);
				
				if(isset($listitem[1]))
					$customtitle=$listitem[1];
			
				if(isset($listitem[2]))
					$customdescription=$listitem[2];
				
				if(isset($listitem[3]))
					$customimage=$listitem[3];
			}
			else
				$theLink=$listitem;
					
				
			if(!(strpos($theLink, '/embed/')===false))
			{
				//Convert Embed links to Address bar version
				$theLink=str_replace('www.youtube.com/embed/','youtu.be/',$theLink);
				$theLink=str_replace('youtube.com/embed/','youtu.be/',$theLink);
			}
		

			switch($vsn)
			{
				
				case 'break' :
					
					require_once('break.php');
					$HTML_SOURCE='';
					
					$videoid=VideoSource_Break::extractBreakID($theLink,$HTML_SOURCE);
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Break::getVideoData($videoid, $customimage, $customtitle, $customdescription, $HTML_SOURCE);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'break', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				
				case 'vimeo' :
					
					require_once('vimeo.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Vimeo::extractVimeoID($theLink);
					else
						$videoid=$videoid_optional;						
					
				
					if($videoid!='')
					{

						if($query_video_host)
						{
							$videoitem=VideoSource_Vimeo::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				
				case 'own3dtvlive' :
					
					require_once('own3dtvlive.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Own3DTvLive::extractOwn3DTvLiveID($theLink);
					else
						$videoid=$videoid_optional;						
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Own3DTvLive::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'own3dtvlive', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'own3dtvvideo' :
					
					require_once('own3dtvvideo.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Own3DTvVideo::extractOwn3DTvVideoID($theLink);
					else
						$videoid=$videoid_optional;						
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Own3DTvVideo::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'own3dtvvideo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'youtube' :

				
					require_once('youtube.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Youtube::extractYouTubeID($theLink);
					else
						$videoid=$videoid_optional;
					
					if($videoid!='')
					{
						
						if($query_video_host)
						{
							$videoitem=VideoSource_Youtube::getVideoData(
												$videoid,
												$customimage,
												$customtitle,
												$customdescription,
												$this->theme_row->thumbnailstyle
												);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'youtube', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					break;
				
				case 'google' :

					require_once('google.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Google::extractGoogleID($theLink);
					else
						$videoid=$videoid_optional;

					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Google::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'google', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'yahoo' :
					
					require_once('yahoo.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_Yahoo::extractYahooID($theLink);
					else
						$videoid=$videoid_optional;
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Yahoo::getVideoData($videoid,$customimage,$customtitle,$customdescription,$theLink);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'yahoo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
				
					break;
				
				case 'collegehumor' :
					
					require_once('collegehumor.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_CollegeHumor::extractCollegeHumorID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_CollegeHumor::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'collegehumor', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
			
					break;
				
				case 'dailymotion' :
					
					require_once('dailymotion.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_DailyMotion::extractDailyMotionID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_DailyMotion::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'dailymotion', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
			}//switch($vsn)
			
			$videoitem['custom_title']=$customtitle;
			$videoitem['custom_description']=$customdescription;
			$videoitem['custom_imageurl']=$customimage;
				
			
		return $videoitem;
	}
	
	
	function isVideo_record_exist($videosource,$videoid,$listid)
	{
				$db = JFactory::getDBO();
				
				$query = 'SELECT id, allowupdates FROM #__youtubegallery_videos WHERE `videosource`="'.$videosource.'" AND `videoid`="'.$videoid.'" AND `listid`='.$listid.' LIMIT 1';

				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				
				$videos_rows=$db->loadAssocList();
				
				if(count($videos_rows)==0)
						return 0;
				
				$videos_row=$videos_rows[0];
				
				if($videos_row['allowupdates']!=1)
						return -1; //Updates disable
				
				return $videos_row['id'];
	}
	

	
	function getVideoList_FromCache_From_Table(&$videoid,&$total_number_of_rows)
	{
		$listIDs=array();
		$listIDs[]=$this->videolist_row->id;
		
		$db = JFactory::getDBO();
		
		$where=array();
		
		$where[]='`listid`="'.$this->videolist_row->id.'"';
		$where[]='`isvideo`=0';
		$where[]='`videosource`="videolist"';
		
		$query = 'SELECT `videoid` FROM `#__youtubegallery_videos` WHERE '.implode(' AND ', $where);
		//echo '$query='.$query.'<br/>';
		
		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
		$videos_lists=$db->loadAssocList();
		
		if(count($videos_lists)>0)
		{
			foreach($videos_lists as $v)
			{
				if($v['videoid']==-1)
				{
					$listIDs=array();
					break;
				}
				else
					$listIDs[]=$v['videoid'];
			}	
		
		}
		
		return $this->getVideoList_FromCacheFromTable($videoid,$total_number_of_rows,$listIDs);
	}
	
	function getVideoList_FromCacheFromTable(&$videoid,&$total_number_of_rows,&$listIDs)
	{
		
		
				
		$where=array();
		
		if(count($listIDs)>0)
			$where[]='(`listid`="'.implode('" OR `listid`="',$listIDs).'")';
			
			
		$where[]='`isvideo`';
				
		$db = JFactory::getDBO();
	
		if($this->theme_row->rel!='' and JRequest::getCmd('tmpl')=='component')
		{
			// Get only one video, because video opens in Shadow/Lightbox
			$where[]='`videoid`="'.$videoid.'"';
			$limitstart=0;
			$limit=1;
		}
		else
		{
			if(((int)$this->theme_row->customlimit)==0)
				$limit=0; // UNLIMITED
			else
				$limit = (int)$this->theme_row->customlimit;
			
			$limitstart = JRequest::getVar('start', 0, '', 'int');
		}
		
		if($this->theme_row->orderby!='' and $this->theme_row->orderby!='randomization')
			$orderby=$this->theme_row->orderby;
		else
			$orderby='ordering';
		
		$query = 'SELECT * FROM `#__youtubegallery_videos` WHERE '.implode(' AND ', $where).' ORDER BY '.$orderby;
		//echo 'query='.$query.'<br/>';
		//die;
		
		
		$db->setQuery($query);
		$db->query();
		$total_number_of_rows = $db->getNumRows();
		
		if($limit==0)
			$db->setQuery($query);
		else
			$db->setQuery($query, $limitstart, $limit);
		
		if (!$db->query())    die( $db->stderr());
				


		$videos_rows=$db->loadAssocList();
		
		if($this->theme_row->orderby=='randomization')
			shuffle($videos_rows);
			
		$firstvideo='';
		
		if($firstvideo=='' and count($videos_rows)>0)
		{
			$videos_row=$videos_rows[0];
			$firstvideo=$videos_row['videoid'];
			
			
		}
		if($videoid!='')
		{
			$found=false;	
			foreach($videos_rows as $videos_row)
			{
								
				if($videos_row['videoid']==$videoid)
					$found=true;
			}
		
			if(!$found)
			{
				$videoid=$firstvideo;
							
			}	
		}
		else
			$videoid=$firstvideo;
	

		
		return $videos_rows;
		
	}
	
	

	function update_playlist($force_update = false)
	{

			$start  = strtotime( $this->videolist_row->lastplaylistupdate );
			$end    = strtotime( date( 'Y-m-d H:i:s') );
			$days_diff = ($end-$start)/86400;
			
			$updateperiod=$this->videolist_row->updateperiod;
			if($updateperiod==0)
				$updateperiod=1;
			
			if($days_diff>$updateperiod or $force_update)
			{

				$this->update_cache_table($this->videolist_row);
				$this->videolist_row->lastplaylistupdate =date( 'Y-m-d H:i:s');
				
				$db = JFactory::getDBO();
				$query = 'UPDATE #__youtubegallery_videolists SET `lastplaylistupdate`="'.$this->videolist_row->lastplaylistupdate.'" WHERE `id`="'.$this->videolist_row->id.'"';
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
			}
	}

	function update_cache_table(&$videolist_row) 
	{
				$videolist_array=$this->csv_explode("\n", $videolist_row->videolist, '"', true);
				
				$firstvideo='';
				$videolist=$this->formVideoList($videolist_array, $firstvideo);

				$ListOfVideos=array();
				
				$db = JFactory::getDBO();
				
				$parent_id=0;
				$this_is_a_list=false;
				$list_count_left=0;
				
				foreach($videolist as $g)
				{
						$g_title=str_replace('"','&quot;',$g['title']);
						$g_description=str_replace('"','&quot;',$g['description']);
						
						$custom_g_title=str_replace('"','&quot;',$g['custom_title']);
						$custom_g_description=str_replace('"','&quot;',$g['custom_description']);
						
						$fields=array();

						if(
						   $g['videosource']=='youtubeuseruploads' or
						   $g['videosource']=='youtubestandard' or
						   $g['videosource']=='youtubeplaylist' or
						   $g['videosource']=='youtubeuserfavorites' or
						   $g['videosource']=='vimeouservideos' or
						   $g['videosource']=='vimeochannel' or
						   $g['videosource']=='videolist'						   
						   )
						{
								//parent
								$parent_id=0;
								$this_is_a_list=true;
								$list_count_left=(int)$g['count'];
						}
						else
						{
								$this_is_a_list=false;
						}

						
						$fields[]='`listid`="'.$videolist_row->id.'"';
						$fields[]='`parentid`="'.$parent_id.'"';
						$fields[]='`videosource`="'.$g['videosource'].'"';
						$fields[]='`videoid`="'.$g['videoid'].'"';
						
						if($g['imageurl']!='')
							$fields[]='`imageurl`="'.$g['imageurl'].'"';
							
						if($g['title']!='')
							$fields[]='`title`="'.$g_title.'"';
						
						if($g['description']!='')
							$fields[]='`description`="'.$g_description.'"';
						
						$fields[]='`custom_imageurl`="'.$g['custom_imageurl'].'"';
						$fields[]='`custom_title`="'.$custom_g_title.'"';
						$fields[]='`custom_description`="'.$custom_g_description.'"';
						
						$fields[]='`specialparams`="'.$g['specialparams'].'"';
						$fields[]='`link`="'.$g['link'].'"';
						$fields[]='`ordering`="'.$g['ordering'].'"';
					
						if($this_is_a_list)
								$fields[]='`lastupdate`="'.date( 'Y-m-d H:i:s').'"';
						$fields[]='`isvideo`="'.($this_is_a_list ? '0' : '1').'"';
						

						if(isset($g['publisheddate']))
							$fields[]='`publisheddate`="'.$g['publisheddate'].'"';
						//else
						//	$fields[]='`publisheddate`=""';
					
						if(isset($g['duration']))
							$fields[]='`duration`="'.$g['duration'].'"';
						//else
						//	$fields[]='`duration`=0';
					
						if(isset($g['rating_average']))
							$fields[]='`rating_average`="'.$g['rating_average'].'"';
						//else
						//	$fields[]='`rating_average`=0';
						
						if(isset($g['rating_max']))
							$fields[]='`rating_max`="'.$g['rating_max'].'"';
						//else
						//	$fields[]='`rating_max`=0';
					
						if(isset($g['rating_min']))
							$fields[]='`rating_min`="'.$g['rating_min'].'"';
						//else
						//	$fields[]='`rating_min`=0';
					
						if(isset($g['rating_numRaters']))
							$fields[]='`rating_numRaters`="'.$g['rating_numRaters'].'"';
						//else
						//	$fields[]='`rating_numRaters`=0';
					
						if(isset($g['statistics_favoriteCount']))
							$fields[]='`statistics_favoriteCount`="'.$g['statistics_favoriteCount'].'"';
						//else
						//	$fields[]='`statistics_favoriteCount`=0';
					
						if(isset($g['statistics_viewCount']))
							$fields[]='`statistics_viewCount`="'.$g['statistics_viewCount'].'"';

				
						if(isset($g['keywords']))
							$fields[]='`keywords`="'.$g['keywords'].'"';

						
						$record_id=$this->isVideo_record_exist($g['videosource'],$g['videoid'],$videolist_row->id);
						
						$query='';
						

						
						
						if($record_id==0)
						{
								$query="INSERT #__youtubegallery_videos SET ".implode(', ', $fields).', `allowupdates`="1"';
								$db->setQuery($query);
								if (!$db->query())    die( $db->stderr());
								
								$record_id_new=$this->isVideo_record_exist($g['videosource'],$g['videoid'],$videolist_row->id);
								
								$ListOfVideos[]=$record_id_new;

								if($this_is_a_list)
										$parent_id=$record_id_new;
						}
						elseif($record_id>0)
						{
								$query="UPDATE #__youtubegallery_videos SET ".implode(', ', $fields).' WHERE id='.$record_id;
								
								$db->setQuery($query);
								if (!$db->query())    die( $db->stderr());
								
								$ListOfVideos[]=$record_id;
								
								if($this_is_a_list)
										$parent_id=$record_id;
								
						}

						
						if(!$this_is_a_list)
						{
								if($list_count_left>0)
										$list_count_left-=1;
										
								
								if($list_count_left==0)
										$parent_id=0;
						}
						

				}
				
				//Delete All videos of this gallery that has bee delete form the list but allowed for updates.
				
				$query='DELETE FROM #__youtubegallery_videos WHERE listid='.$videolist_row->id.' AND allowupdates';
				if(count($ListOfVideos)>0)
						$query.=' AND id!='.implode(' AND id!=',$ListOfVideos).' ';
				
			
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				
			
	}
	
	
	function RefreshVideoData(&$gallery_list)
	{
		$db = JFactory::getDBO();
		
		$count=count($gallery_list);
		for($i=0;$i<$count;$i++)
		{
			$listitem=$gallery_list[$i];
			
			$start  = strtotime( $listitem['lastupdate'] );
			$end    = strtotime( date( 'Y-m-d H:i:s') );
			$days_diff = ($end-$start)/86400;
			
			$updateperiod=$this->videolist_row->updateperiod;
			
			
			if($updateperiod==0)
				$updateperiod=1;
			
			if($listitem['status']==0 or $days_diff>$updateperiod)
			{

				$listitem_temp=array();
				$listitem_temp[]=$listitem['link'];
				$listitem_temp[]=$listitem['custom_title'];
				$listitem_temp[]=$listitem['custom_description'];
				$listitem_temp[]=$listitem['custom_imageurl'];
				
				$listitem_new=$this->GrabVideoData($listitem_temp,$listitem['videosource'],$listitem['videoid']);
				
				if($listitem_new['title']!='')
					$listitem['title']=$listitem_new['title'];
					
				if($listitem_new['description']!='')
					$listitem['description']=$listitem_new['description'];
					
				if($listitem_new['imageurl']!='')
					$listitem['imageurl']=$listitem_new['imageurl'];
				
				$fields=array();
				
				$fields[]='`title`="'.$this->mysqlrealescapestring($listitem_new['title']).'"';
				$fields[]='`description`="'.$this->mysqlrealescapestring($listitem_new['description']).'"';
				$fields[]='`imageurl`="'.$listitem_new['imageurl'].'"';
				$fields[]='`lastupdate`="'.date( 'Y-m-d H:i:s').'"';
				$fields[]='`status`="200"';
				
				if(isset($listitem_new['publisheddate']))
					$fields[]='`publisheddate`="'.$listitem_new['publisheddate'].'"';
					
				if(isset($listitem_new['duration']))
					$fields[]='`duration`="'.$listitem_new['duration'].'"';
					
				if(isset($listitem_new['rating_average']))
					$fields[]='`rating_average`="'.$listitem_new['rating_average'].'"';
					
				if(isset($listitem_new['rating_max']))
					$fields[]='`rating_max`="'.$listitem_new['rating_max'].'"';
					
				if(isset($listitem_new['rating_min']))
					$fields[]='`rating_min`="'.$listitem_new['rating_min'].'"';
					
				if(isset($listitem_new['rating_numRaters']))
					$fields[]='`rating_numRaters`="'.$listitem_new['rating_numRaters'].'"';
					
				if(isset($listitem_new['statistics_favoriteCount']))
					$fields[]='`statistics_favoriteCount`="'.$listitem_new['statistics_favoriteCount'].'"';
					
				
				if(isset($listitem_new['statistics_viewCount']))
					$fields[]='`statistics_viewCount`="'.$listitem_new['statistics_viewCount'].'"';
				
				if(isset($listitem_new['keywords']))
					$fields[]='`keywords`="'.$listitem_new['keywords'].'"';
					
				
				$query="UPDATE `#__youtubegallery_videos` SET ".implode(', ', $fields).' WHERE `id`='.(int)$listitem['id'];
				
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				
				$gallery_list[$i]=$listitem;
				
			}
			
		}//foreach($gallery_list as $listitem)
		

	}
	
	
	function getVideoSourceName($link)
	{
	
		if(!(strpos($link,'://youtube.com')===false) or !(strpos($link,'://www.youtube.com')===false))
		{
			if(!(strpos($link,'/playlist')===false))
				return 'youtubeplaylist';
			elseif(!(strpos($link,'/favorites')===false))
				return 'youtubeuserfavorites';
			elseif(!(strpos($link,'/user')===false))
				return 'youtubeuseruploads';
			else
				return 'youtube';
		}
		
		if(!(strpos($link,'://youtu.be')===false) or !(strpos($link,'://www.youtu.be')===false))
			return 'youtube';
		
		if(!(strpos($link,'youtubestandard:')===false))
			return 'youtubestandard';
		
		if(!(strpos($link,'videolist:')===false)) // new in 3.1.1 version
			return 'videolist';
		
		
		if(!(strpos($link,'://vimeo.com/user')===false) or !(strpos($link,'://www.vimeo.com/user')===false))
			return 'vimeouservideos';
		elseif(!(strpos($link,'://vimeo.com/channels/')===false) or !(strpos($link,'://www.vimeo.com/channels/')===false))
			return 'vimeochannel';
		elseif(!(strpos($link,'://vimeo.com')===false) or !(strpos($link,'://www.vimeo.com')===false))
			return 'vimeo';
		
		
		if(!(strpos($link,'://own3d.tv/l/')===false) or !(strpos($link,'://www.own3d.tv/l/')===false))
			return 'own3dtvlive';
		
		if(!(strpos($link,'://own3d.tv/v/')===false) or !(strpos($link,'://www.own3d.tv/v/')===false))
			return 'own3dtvvideo';
		
		
		if(!(strpos($link,'video.google.com')===false))
			return 'google';
		
		if(!(strpos($link,'video.yahoo.com')===false))
			return 'yahoo';
		
		if(!(strpos($link,'://break.com')===false) or !(strpos($link,'://www.break.com')===false))
			return 'break';
		
	
		if(!(strpos($link,'://collegehumor.com')===false) or !(strpos($link,'://www.collegehumor.com')===false))
			return 'collegehumor';
		
		if(!(strpos($link,'://dailymotion.com')===false) or !(strpos($link,'://www.dailymotion.com')===false))
			return 'dailymotion';
		
		return '';
	}
	
	
	public static function parse_query($var)
	{
		$arr  = array();
		
		 $var  = parse_url($var);
		 $varquery=$var['query'];

		 
		 if($varquery=='')
			return $arr;
		
		 $var  = html_entity_decode($varquery);
		 $var  = explode('&', $var);
		 

		foreach($var as $val)
		{
			$x          = explode('=', $val);
			$arr[$x[0]] = $x[1];
		}
		unset($val, $x, $var);
		return $arr;
	}
	
	
	function csv_explode($delim=',', $str, $enclose='"', $preserve=false)
	{
		$resArr = array();
		$n = 0;
		$expEncArr = explode($enclose, $str);
		foreach($expEncArr as $EncItem)
		{
			if($n++%2){
				array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
			}else{
				$expDelArr = explode($delim, $EncItem);
				array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			    $resArr = array_merge($resArr, $expDelArr);
			}
		}
	return $resArr;
	}
	
	
	function mysqlrealescapestring($inp)
    {
		
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
		    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	    }

	    return $inp;

    }	

	public static function getURLData($url)
	{
		if (function_exists('curl_init'))
		{
			$ch = curl_init();
			$timeout = 10;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$htmlcode = curl_exec($ch);
			curl_close($ch);
		}
		elseif (ini_get('allow_url_fopen') == true)
		{
			$htmlcode = file_get_contents($url);
		}
		else
		{
			echo '<p style="color:red;">Cannot load data, enable "allow_url_fopen" or install cURL<br/>
			<a href="http://joomlaboat.com/youtube-gallery/f-a-q/why-i-see-allow-url-fopen-message" target="_blank">Here</a> is what to do.
			</p>
			';
		}

		return $htmlcode;
	}
	
	
	
	public static function ApplyPlayerParameters(&$settings,$youtubeparams)
	{
		if($youtubeparams=='')
			return;
		
		$a=str_replace("\n",'',$youtubeparams);
		$a=trim(str_replace("\r",'',$a));
		$l=explode(';',$a);
		
		foreach($l as $o)
		{
			if($o!='')
			{
				$pair=explode('=',$o);
				if(count($pair)==2)
				{
					$option=trim(strtolower($pair[0]));
			
					$found=false;
			
					for($i=0;$i<count($settings);$i++)
					{
				
						if($settings[$i][0]==$option)
						{
							$settings[$i][1]=$pair[1];
							$found=true;
							break;
						}
					}
				
					if(!$found)
						$settings[]=array($option,$pair[1]);
				}//if(count($pair)==2)
			}//if($o!='')
		}
		
	}
	
	public static function CreateParamLine(&$settings)
	{
		$a=array();
		
		foreach($settings as $s)
			$a[]=$s[0].'='.$s[1];

		return implode('&amp;',$a);
	}


}




?>