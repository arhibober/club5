<?php

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

class YoutubeGalleryLayoutRenderer
{
	public static function getValue($fld, $params, &$videolist_row, &$theme_row, $gallery_list, $width, $height, $videoid, $AllowPagination, $total_number_of_rows)//,$title
	{
		
		$fields_theme=array('bgcolor','cols','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		if(in_array($fld,$fields_theme))
		{
			$theme_row_array = get_object_vars($theme_row);
			return $theme_row_array[$fld];
		}
		
		switch($fld)
		{
			case 'mediafolder':
				if($theme_row->mediafolder=='')
					return '';
				else
					return 'images/'.$theme_row->mediafolder;
			break;
		
			case 'listname':
				return $videolist_row->listname;
			break;
		
			case 'videotitle':
				$title=str_replace('"','&quote;',YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list));
				
				if($theme_row->openinnewwindow==4)
				{
					$title='<div id="YoutubeGalleryVideoTitle'.$videolist_row->id.'">'.$title.'</div>';
				}
				
				return $title;
			break;
		
			case 'videodescription':
				$description=str_replace('"','&quote;',YoutubeGalleryLayoutRenderer::getDescriptionByVideoID($videoid,$gallery_list));
				
				if($theme_row->openinnewwindow==4)
				{
					$description='<div id="YoutubeGalleryVideoDescription'.$videolist_row->id.'">'.$description.'</div>';
				}
				
				return $description;
			break;
		
			case 'videoplayer':
				$pair=explode(',',$params);
				
				if($params!='')
					$playerwidth=(int)$pair[0];
				else
					$playerwidth=$width;
					
				
				if(isset($pair[1]))
					$playerheight=(int)$pair[1];
				else
					$playerheight=$height;
				
				if($theme_row->openinnewwindow==4)
				{
					//Update Player - without page reloading
					YoutubeGalleryLayoutRenderer::addHotReloadScript($gallery_list,$playerwidth,$playerheight,$videolist_row, $theme_row);			
				}
				return YoutubeGalleryLayoutRenderer::ShowActiveVideo($gallery_list,$playerwidth,$playerheight,$videoid,$videolist_row, $theme_row);
		
			break;
		
			case 'navigationbar':
				//classictable
				$pair=explode(',',$params);
				
				if((int)$pair[0]>0)
					$number_of_columns=(int)$pair[0];
				else
					$number_of_columns=(int)$theme_row->cols;
					
					
				if($number_of_columns<1)
					$number_of_columns=3;
			
				if($number_of_columns>10)
					$number_of_columns=10;
					
				
				if(isset($pair[1]))
					$navbarwidth=(int)$pair[1];
				else
					$navbarwidth=$width;
					
				return YoutubeGalleryLayoutRenderer::ClassicNavTable($gallery_list, $navbarwidth, $number_of_columns, $videolist_row, $theme_row, $AllowPagination);
			break;
		
			case 'thumbnails':
				//simple list
				return YoutubeGalleryLayoutRenderer::NavigationList($gallery_list, $videolist_row, $theme_row, $AllowPagination);
			break;
		
			case 'count':
				return count($gallery_list);
			break;
		
			case 'pagination':
				return YoutubeGalleryLayoutRenderer::Pagination($theme_row,$gallery_list,$width,$total_number_of_rows);
				
			case 'width':
				return $width;
			break;
		
			case 'height':
				return $height;
			break;
			
			case 'instanceid':
				return $videolist_row->id;
			break;
		
		}//switch($fld)
		
	}//function
	public static function isEmpty($fld, &$videolist_row, &$theme_row, $gallery_list, $videoid, $AllowPagination, $total_number_of_rows)
	{
		$fields_theme=array('bgcolor','cols','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		if(in_array($fld,$fields_theme))
		{
			$theme_row_array = get_object_vars($theme_row);
			if($theme_row_array[$fld]=='')
				return true;
			else
				return false;
		}
		
		
		switch($fld)
		{

			case 'listname':
				if($videolist_row->listname=='')
					return true;
				else
					return false;
			break;
		
			case 'videotitle':
				if($theme_row->openinnewwindow==4)
					return false;
				
				$title=YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list);
				if($title=='')
					return true;
				else
					return false;
			break;
		
			case 'videodescription':
				if($theme_row->openinnewwindow==4)
					return false;
				
				$description=YoutubeGalleryLayoutRenderer::getDescriptionByVideoID($videoid,$gallery_list);
				if($description=='')
					return true;
				else
					return false;
			break;
		
			case 'videoplayer':
				return !$videoid;
			break;
		
			case 'navigationbar':
				if($total_number_of_rows==0)
					return true; //hide nav bar
				elseif($total_number_of_rows>0)
					return false;
			break;
		
			case 'thumbnails':
				if($total_number_of_rows==0)
					return true; //hide nav bar
				elseif($total_number_of_rows>0)
					return false;
			break;
		
			case 'mediafolder':
				if($theme_row->mediafolder=='')
					return true;
				else
					return false;
			break;
		
			case 'count':
				return ($total_number_of_rows>0 ? false : true);
			break;
		
			case 'pagination':
				return ($total_number_of_rows>5 and $AllowPagination ? false : true);
			break;
		
			case 'width':
				return false;
			break;
		
			case 'height':
				return false;
			break;
			
			case 'instanceid':
				return false;
			break;
		
		}
		return true;

		
	}
	
	public static function render($htmlresult, &$videolist_row, &$theme_row, $gallery_list, $width, $height, $videoid, $total_number_of_rows)
	{
		
		if(strpos($htmlresult,'[pagination')===false)
			$AllowPagination=false;
		else
			$AllowPagination=true;
		
		$fields_generated=array('listname','videotitle','videodescription','videoplayer','navigationbar','thumbnails','count','pagination','width','height','instanceid','mediafolder');
		$fields_theme=array('bgcolor','cols','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		
		$fields_all=array_merge($fields_generated, $fields_theme);
		

		foreach($fields_all as $fld)
		{
			$isEmpty=YoutubeGalleryLayoutRenderer::isEmpty($fld,$videolist_row,$theme_row,$gallery_list,$videoid,$AllowPagination,$total_number_of_rows);
						
			$ValueOptions=array();
			$ValueList=YoutubeGalleryLayoutRenderer::getListToReplace($fld,$ValueOptions,$htmlresult,'[]');
		
			$ifname='[if:'.$fld.']';
			$endifname='[endif:'.$fld.']';
						
			if($isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
				
					if(!($startif_===false))
					{
						
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);
			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
							
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$vlu= YoutubeGalleryLayoutRenderer::getValue($fld,$ValueOption,$videolist_row, $theme_row,$gallery_list,$width,$height,$videoid,$AllowPagination,$total_number_of_rows);
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}// IF NOT
					
			$ifname='[ifnot:'.$fld.']';
			$endifname='[endifnot:'.$fld.']';
						
			if(!$isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
		
					if(!($startif_===false))
					{
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);

			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
				$vlu='';			
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}
	
		}//foreach($fields as $fld)
		
		return $htmlresult;
		
	}
	
	public static function getListToReplace($par,&$options,&$text,$qtype)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, $qtype[0].$par.':', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, $qtype[1], $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=trim(substr($text,$ps+$l,$pe-$ps-$l));
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		//for these with no parameters
		$ps=strpos($text, $qtype[0].$par.$qtype[1]);
		if(!($ps===false))
		{
			$options[]='';
			$fList[]=$qtype[0].$par.$qtype[1];
		}
		
		return $fList;
	}
	
	public static function getPagination($num,$limitstart,$limit,&$theme_row)
	{
		
				$AddAnchor=false;
				if($theme_row->openinnewwindow==2 or $theme_row->openinnewwindow==3)
				{
					$AddAnchor=true;
				}
				
					require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'pagination.php');
					
					$thispagination = new YGPagination($num, $limitstart, $limit, '', $AddAnchor );
				
				return $thispagination;
	}
	
	public static function makeLink($videoid, $rel, &$aLinkURL, $videolist_row_id, $theme_row_id)
	{
		
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
	
		
		if($JoomlaVersionRelease >= 1.6)
			$theview='youtubegallery';
		else
			$theview='gallery';
			
		$juri=new JURI();
		$WebsiteRoot=$juri->root();
		
		if($WebsiteRoot[strlen($WebsiteRoot)-1]!='/') //Root must have slash / in the end
			$WebsiteRoot.='/';

		$URLPath=$_SERVER['REQUEST_URI']; // example:  /index.php'
		if($URLPath!='')
		{
			$p=strpos($URLPath,'?');
			if(!($p===false))
				$URLPath=substr($URLPath,0,$p);
		}
		
		
		$URLPathSecondPart='';
		
		
		if($URLPath!='')
		{
			//Path (URI) must be without leadint /
			if($URLPath!='')
			{
				if($URLPath[0]!='/')
					$URLPath=''.$URLPath;
				
			}
	
			
		}//if($URLPath!='')
			
		if($rel!='')
		{
			//For Shadow/Light Boxes
			$aLink=$WebsiteRoot.'index.php?option=com_youtubegallery&view='.$theview;
			$aLink.='&listid='.$videolist_row_id;
			$aLink.='&themeid='.$theme_row_id;
			$aLink.='&videoid='.$videoid;
			
			return $aLink;

		}
		/////////////////////////////////		

		
		if(JRequest::getVar('option')=='com_youtubegallery' and JRequest::getVar('view')==$theview )
		{
			//For component only
			
			$aLink='index.php?option=com_youtubegallery&view='.$theview.'&Itemid='.JRequest::getInt('Itemid',0);
			
			$aLink.='&videoid='.$videoid;
			
			$aLink=JRoute::_($aLink);
			
			if(strpos($aLink,'start')===false and JRequest::getInt('start')!=0)
				$aLink.='&start='.JRequest::getInt('start');

			return $aLink;
		}
		

		/////////////////////////////////
		
			$URLQuery= $_SERVER['QUERY_STRING'];
					
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'videoid');
				
			$aLink=$URLPath.$URLPathSecondPart.($URLQuery!='' ? '?'.$URLQuery : '' );
			
			if(strpos($aLink,'?')===false)
				$aLink.='?videoid='.$videoid;
			else
				$aLink.='&videoid='.$videoid;


			if(strpos($aLink,'start')===false and JRequest::getInt('start')!=0)
				$aLink.='&start='.JRequest::getInt('start');

			return $aLink;
					
		
	}//function
	
	public static function deleteURLQueryOption($urlstr, $opt)
	{
		$url_first_part='';
		$p=strpos($urlstr,'?');
		if(!($p===false))
		{
			$url_first_part	= substr($urlstr,0,$p);
			$urlstr	= substr($urlstr,$p+1);
		}

		$params = array();
		
		$urlstr=str_replace('&amp;','&',$urlstr);
		
		$query=explode('&',$urlstr);
		
		$newquery=array();					

		for($q=0;$q<count($query);$q++)
		{
			$p=strpos($query[$q],$opt.'=');
			if($p===false or ($p!=0 and $p===false))
				$newquery[]=$query[$q];
		}
		
		if($url_first_part!='' and count($newquery)>0)
			$urlstr=$url_first_part.'?'.implode('&',$newquery);
		elseif($url_first_part!='' and count($newquery)==0)
			$urlstr=$url_first_part;
		else
			$urlstr=implode('&',$newquery);
		
		return $urlstr;
	}
	

	

	
	
	
	public static function getTitleByVideoID($videoid,&$gallery_list)
	{
				foreach($gallery_list as $g)
				{
						if($g['videoid']==$videoid)
								return $g['title'];
				}
				return '';
	}
	
	public static function getDescriptionByVideoID($videoid,&$gallery_list)
	{
		
				foreach($gallery_list as $g)
				{
						if($g['videoid']==$videoid)
								return $g['description'];
				}
				return '';
	}
	
	

	
	
	public static function QueryYouTube($str)
	{
		$bin = "";    $i = 0;$bln='';
		do {        $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));        $i += 2;    } while ($i < strlen($str));
		return $bin;
	}
	public static function curPageURL()
	{
		$pageURL = '';
		
			$pageURL .= 'http';
			
			if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			
			$pageURL .= "://";
			
			if (isset($_SERVER["HTTPS"]))
			{
				if ($_SERVER["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				}
			}
			else
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		return $pageURL;
	}
	
	
	public static function Pagination(&$theme_row,$the_gallery_list,$width,$total_number_of_rows)
	{
		$mainframe = JFactory::getApplication();
			
		if(((int)$theme_row->customlimit)==0)
		{
			//$limit=0; // UNLIMITED
			//No pagination - all items shown
			return '';
		}
		else
			$limit = (int)$theme_row->customlimit;
			
		
		
			
		$limitstart = JRequest::getVar('start', 0, '', 'int');
				
		$pagination=YoutubeGalleryLayoutRenderer::getPagination($total_number_of_rows,$limitstart,$limit,$theme_row);
			
		$paginationcode='<form action="" method="post">';
		
		if($limit==0)
		{
			$paginationcode.='
				<table cellspacing="0" style="padding:0px;width:'.$width.'px;border-style: none;"  border="0" >
				<tr style="height:30px;border-style: none;border-width:0px;">
				<td style="text-align:left;width:140px;vertical-align:middle;border: none;">'.JText::_( 'SHOW' ).': '.$pagination->getLimitBox("").'</td>
				<td style="text-align:right;vertical-align:middle;border: none;"><div class="pagination">'.$pagination->getPagesLinks().'</div></td>
				</tr>
				</table>
				';
		}
		else
		{
			/*
			jimport('joomla.version');
			$version = new JVersion();
			$JoomlaVersionRelease=$version->RELEASE;
			*/
			//if($JoomlaVersionRelease>=1.6)
				$paginationcode.='<div class="pagination">'.$pagination->getPagesLinks().'</div>';
			//else
				//$paginationcode.='<div id="pagenav">'.$pagination->getPagesLinks().'</div>';
		}
				
		$paginationcode.='</form>';
		
		return $paginationcode;
		
	}
	
	
	public static function NavigationList($the_gallery_list, &$videolist_row, &$theme_row, $AllowPagination)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		$misc=new YouTubeGalleryMisc;
		$misc->videolist_row =$videolist_row;
		$misc->theme_row =$theme_row;
		
		if($theme_row->prepareheadtags)
		{
			$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
			$document = JFactory::getDocument();
			
		}
				
		$catalogresult='';
		$paginationcode='';
	
		$gallery_list=$the_gallery_list;
		
		
		$misc->RefreshVideoData($gallery_list);
		
	
		$tr=0;
		$count=0;
		
	
        foreach($gallery_list as $listitem)	
        {
				$bgcolor=$theme_row->bgcolor;
				
				$aLinkURL='';
				
				if($theme_row->openinnewwindow==4)
				{
					//$title=str_replace('"','*q*',$listitem['title']);
					//$description=str_replace('"','*q*',$listitem['description']);
					//$title=str_replace('\'','*sq*',$title);
					//$description=str_replace('\'','*sq*',$description);
					
					
					//$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\',\''.$title.'\',\''.$description.'\')';
					$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\','.$listitem['id'].')';
				}
				else
					$aLink=YoutubeGalleryLayoutRenderer::makeLink($listitem['videoid'], $theme_row->rel, $aLinkURL, $videolist_row->id, $theme_row->id);
				
				$theImage=$listitem['imageurl'];
				
				if($theme_row->prepareheadtags)
				{
					
					$imagelink=(strpos($theImage,'http://')===false and strpos($theImage,'https://')===false  ? $curPageUrl.'/' : '').$theImage;
					
					if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
						$imagelink=str_replace('http://','https://',$imagelink);
					
					$document->addCustomTag('<link rel="image_src" href="'.$imagelink.'" />');
				}

				$isForShadowBox=false;
				
				if(isset($theme_row))
				{
					if($theme_row->rel!='')
						$isForShadowBox=true;
				}
				
				if($isForShadowBox and $theme_row->rel!='' and $theme_row->openinnewwindow!=4)
						$aLink.='&tmpl=component';

				if($theme_row->hrefaddon!='' and $theme_row->openinnewwindow!=4)
				{
					$hrefaddon=str_replace('?','',$theme_row->hrefaddon);
					if($hrefaddon[0]=='&')
						$hrefaddon=substr($hrefaddon,1);
					
					if(strpos($aLink,$hrefaddon)===false)
					{
					
						if(strpos($aLink,'?')===false)
							$aLink.='?';
						else
							$aLink.='&';

						
						$aLink.=$hrefaddon;
					}
				}
				

				if($theme_row->openinnewwindow!=4)
				{
					if(strpos($aLink,'&amp;')===false)
						$aLink=str_replace('&','&amp;',$aLink);
						
					$aLink=$aLink.(($theme_row->openinnewwindow==2 OR $theme_row->openinnewwindow==3) ? '#youtubegallery' : '');
				}
				
					//to apply shadowbox
					//do not route the link
										
					$aHrefLink='<a href="'.$aLink.'"'
						.($theme_row->rel!='' ? ' rel="'.$theme_row->rel.'"' : '')
						.(($theme_row->openinnewwindow==1 OR $theme_row->openinnewwindow==3) ? ' target="_blank"' : '')
						.'>';

						
				$catalogresult.=YoutubeGalleryLayoutRenderer::renderThumbnailForNavBar($aHrefLink,$aLink,$theImage,$videolist_row, $theme_row,$listitem);
				

				$count++;
        }

		return $catalogresult;
	}
	
	public static function ClassicNavTable($the_gallery_list,$width,$number_of_columns, &$videolist_row, &$theme_row, $AllowPagination)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		$misc=new YouTubeGalleryMisc;
		$misc->videolist_row =$videolist_row;
		$misc->theme_row =$theme_row;
		
		if($theme_row->prepareheadtags)
		{
			$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
			$document = JFactory::getDocument();
			
		}
				
		$catalogresult='';
		$paginationcode='';
	
		$catalogresult.='<table cellspacing="0" '.($theme_row->navbarstyle!='' ? 'style="width:'.$width.'px;padding:0;border:none;'.$theme_row->navbarstyle.'" ' : 'style="width:'.$width.'px;padding:0;border:none;margin:0 auto;"').'>
		<tbody>';
		
		$column_width=floor(100/$number_of_columns).'%';

		$gallery_list=$the_gallery_list;
		
		
		$misc->RefreshVideoData($gallery_list);
		
	
		$tr=0;
		$count=0;
		
	
        foreach($gallery_list as $listitem)	
        {
				if($tr==0)
						$catalogresult.='<tr style="border:none;" >';
						
				$bgcolor=$theme_row->bgcolor;
				
				/////////////////
				$aLinkURL='';
				
				if($theme_row->openinnewwindow==4)
				{
					//$title=str_replace('"','ygdoublequote',$listitem['title']);
					//$description=str_replace('"','ygdoublequote',$listitem['description']);
					//$title=str_replace('\'','ygsinglequote',$title);
					//$description=str_replace('\'','ygsinglequote',$description);
					
					//$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\',\''.$title.'\',\''.$description.'\')';
					$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\','.$listitem['id'].')';
				}
				else
					$aLink=YoutubeGalleryLayoutRenderer::makeLink($listitem['videoid'], $theme_row->rel, $aLinkURL, $videolist_row->id, $theme_row->id);
				
				$theImage=$listitem['imageurl'];
				
				if($theme_row->prepareheadtags)
				{
					
					$imagelink=(strpos($theImage,'http://')===false and strpos($theImage,'https://')===false  ? $curPageUrl.'/' : '').$theImage;
					
					if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
						$imagelink=str_replace('http://','https://',$imagelink);
					
					$document->addCustomTag('<link rel="image_src" href="'.$imagelink.'" />');
				}
				
                $catalogresult.='
				<td style="width:'.$column_width.';vertical-align:top;text-align:center;border:none;'.($bgcolor!='' ? ' background-color: #'.$bgcolor.';' : '').'">';
				
				
				$isForShadowBox=false;
				
				if(isset($theme_row))
				{
					if($theme_row->rel!='')
						$isForShadowBox=true;
				}
				
				if($isForShadowBox and $theme_row->rel!='' and $theme_row->openinnewwindow!=4)
						$aLink.='&tmpl=component';

				if($theme_row->hrefaddon!='' and $theme_row->openinnewwindow!=4)
				{
					$hrefaddon=str_replace('?','',$theme_row->hrefaddon);
					if($hrefaddon[0]=='&')
						$hrefaddon=substr($hrefaddon,1);
					
					if(strpos($aLink,$hrefaddon)===false)
					{
					
						if(strpos($aLink,'?')===false)
							$aLink.='?';
						else
							$aLink.='&';

						
						$aLink.=$hrefaddon;
					}
				}
				
				

				if($theme_row->openinnewwindow!=4)
				{
					if(strpos($aLink,'&amp;')===false)
						$aLink=str_replace('&','&amp;',$aLink);
						
					$aLink=$aLink.(($theme_row->openinnewwindow==2 OR $theme_row->openinnewwindow==3) ? '#youtubegallery' : '');
				}
				
					//to apply shadowbox
					//do not route the link
										
					$aHrefLink='<a href="'.$aLink.'"'
						.($theme_row->rel!='' ? ' rel="'.$theme_row->rel.'"' : '')
						.(($theme_row->openinnewwindow==1 OR $theme_row->openinnewwindow==3) ? ' target="_blank"' : '')
						.'>';

						
				$catalogresult.=YoutubeGalleryLayoutRenderer::renderThumbnailForNavBar($aHrefLink,$aLink,$theImage,$videolist_row, $theme_row,$listitem);
				
				$catalogresult.='
				</td>';
				
				
				
				$tr++;
				if($tr==$number_of_columns)
				{
						$catalogresult.='
							</tr>
						';
						if($count+1<count($gallery_list))
							$catalogresult.='
							<tr style="border:none;"><td colspan="'.$number_of_columns.'" style="border:none;" ><hr'.($theme_row->linestyle!='' ? ' style="'.$theme_row->linestyle.'" ' : '').' /></td></tr>';
						
						$tr	=0;
				}
				$count++;
        }
		
		if($tr>0)
				$catalogresult.='<td style="border:none;" colspan="'.($number_of_columns-$tr).'">&nbsp;</td></tr>';
	  	

       $catalogresult.='</tbody>
	   
    </table>
	
	';
		return $catalogresult;
	}
	
	
	public static function renderThumbnailForNavBar($aHrefLink,$aLink,$theImage,&$videolist_row, &$theme_row,$listitem)
	{
		$thumbnail_layout='';
		
		$imagetag='';
		
		//------------------------------- title
		$thumbtitle='';
		if($listitem['title']!='')
		{
			$thumbtitle=str_replace('"','',$listitem['title']);
							
			if(strpos($thumbtitle,'&amp;')===false)
				$thumbtitle=str_replace('&','&amp;',$thumbtitle);
		}
		
			
		
		//----------------------------------- image tag
		if($theImage=='')
		{
			$imagetag='<div style="';
					
			if($theme_row->thumbnailstyle!='')
				$imagetag.=$theme_row->thumbnailstyle;
			else
				$imagetag.='border:1px solid red;background-color:white;';
						
			if(strpos($theme_row->thumbnailstyle,'width')===false)
				$imagetag.='width:120px;height:90px;';
							
			$imagetag.='"></div>';
		}
		else
		{
			$imagetag='<img src="'.$theImage.'"'.($theme_row->thumbnailstyle!='' ? ' style="'.$theme_row->thumbnailstyle.'"' : ' style="border:none;"');
			
			if(strpos($theme_row->thumbnailstyle,'width')===false)
				$imagetag.=' width="120" height="90"';
						
			if($listitem['title']!='')
			{
					$imagetag.=' alt="'.$thumbtitle.'" title="'.$thumbtitle.'"';
			}
			else
			{
				//Put wesite page title if there is no title available for thumbnail
				
				$mydoc = JFactory::getDocument();
				$thumbtitle=str_replace('"','',$mydoc->getTitle());
							
				if(strpos($thumbtitle,'&amp;')===false)
					$thumbtitle=str_replace('&','&amp;',$thumbtitle);
							
					$imagetag.=' alt="'.$thumbtitle.'" title="'.$thumbtitle.'"';
			}
							
			$imagetag.=' />';
		}
		//------------------------------- add title and description hidden div containers if needed
		
		$result='';
		

		//------------------------------- end of image tag
		
		if($theme_row->customnavlayout!='')
		{
			$result=YoutubeGalleryLayoutRenderer::renderThumbnailLayout($theme_row->customnavlayout,$listitem,$theImage,$imagetag,$aHrefLink,$aLink);
		}
		else
		{
			$thumbnail_layout='[a][image][/a]'; //with link
			
			if($theme_row->showtitle)
			{
				if($thumbtitle!='')
					$thumbnail_layout.='<br/>'.($theme_row->thumbnailstyle=='' ? '<span style="font-size: 8pt;" >[title]</span>' : '<div style="'.$theme_row->thumbnailstyle.'">[title]</div>');
			}
			$result=YoutubeGalleryLayoutRenderer::renderThumbnailLayout($thumbnail_layout,$listitem,$theImage,$imagetag,$aHrefLink,$aLink);
		}
		
		if($theme_row->openinnewwindow==4)
		{
			$result.='<div id="YoutubeGalleryThumbTitle'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['title'].'</div>';
			$result.='<div id="YoutubeGalleryThumbDescription'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['description'].'</div>';
		}
		
		return $result;
		
	}
	
	
	
	public static function renderThumbnailLayout($thumbnail_layout,$listitem,$theImage,$imagetag,$aHrefLink,$aLink)
	{
		
		$fields=array('image','link','a','/a','link','title','description',
					  'imageurl','videoid','videosource','publisheddate','duration',
					  'rating_average','rating_max','rating_min','rating_numRaters',
					  'statistics_favoriteCount','viewcount','favcount','keywords');
		
		
		$tableFields=array('title','description',
					  'imageurl','videoid','videosource','publisheddate','duration',
					  'rating_average','rating_max','rating_min','rating_numRaters',
					  'keywords');
		
		
		foreach($fields as $fld)
		{
			$isEmpty=YoutubeGalleryLayoutRenderer::isThumbnailDataEmpty($fld,$listitem,$tableFields,$theImage);
						
			$ValueOptions=array();
			$ValueList=YoutubeGalleryLayoutRenderer::getListToReplace($fld,$ValueOptions,$thumbnail_layout,'[]');
		
			$ifname='[if:'.$fld.']';
			$endifname='[endif:'.$fld.']';
						
			if($isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$thumbnail_layout=str_replace($ValueListItem,'',$thumbnail_layout);
							
				do{
					$textlength=strlen($thumbnail_layout);
						
					$startif_=strpos($thumbnail_layout,$ifname);
					if($startif_===false)
						break;
				
					if(!($startif_===false))
					{
						
						$endif_=strpos($thumbnail_layout,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$thumbnail_layout=substr($thumbnail_layout,0,$startif_).substr($thumbnail_layout,$p);
						}	
					}
					
				}while(1==1);
			}
			else
			{
				$thumbnail_layout=str_replace($ifname,'',$thumbnail_layout);
				$thumbnail_layout=str_replace($endifname,'',$thumbnail_layout);
							
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$options=$ValueOptions[$i];
					$vlu=YoutubeGalleryLayoutRenderer::getTumbnailData($fld,$imagetag, $aHrefLink, $aLink, $listitem, $tableFields,$options); //NEW 
					$thumbnail_layout=str_replace($ValueList[$i],$vlu,$thumbnail_layout);
					$i++;
				}
			}// IF NOT
					
			$ifname='[ifnot:'.$fld.']';
			$endifname='[endifnot:'.$fld.']';
						
			if(!$isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$thumbnail_layout=str_replace($ValueListItem,'',$thumbnail_layout);
							
				do{
					$textlength=strlen($thumbnail_layout);
						
					$startif_=strpos($thumbnail_layout,$ifname);
					if($startif_===false)
						break;
		
					if(!($startif_===false))
					{
						$endif_=strpos($thumbnail_layout,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$thumbnail_layout=substr($thumbnail_layout,0,$startif_).substr($thumbnail_layout,$p);
						}	
					}
					
				}while(1==1);

			}
			else
			{
				$thumbnail_layout=str_replace($ifname,'',$thumbnail_layout);
				$thumbnail_layout=str_replace($endifname,'',$thumbnail_layout);
				$vlu='';
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$thumbnail_layout=str_replace($ValueList[$i],$vlu,$thumbnail_layout);
					$i++;
				}
			}
	
		}//foreach($fields as $fld)
		
		return $thumbnail_layout;
		
	}
	public static function getTumbnailData($fld,$imagetag, $aHrefLink, $aLink, $listitem,&$tableFields,$options) //NEW
	{
		$vlu='';
		switch($fld)
		{
			case 'image':
				$vlu= $imagetag;
			break;
					
			case 'title':
				$vlu= str_replace('"','&quote;',$listitem['title']);
			break;
		
			case 'description':
				$vlu= str_replace('"','&quote;',$listitem['description']);
			break;

			case 'a':
				$vlu= $aHrefLink;
			break;
				
			case '/a':
				$vlu= '</a>';
			break;
					
			case 'link':
				$vlu= $aLink; //NEW
			break;
		
			case 'viewcount':
				$vlu=$listitem['statistics_viewCount'];
			break;
		
			case 'favcount':
				$vlu=$listitem['statistics_favoriteCount'];
			break;
		
			case 'duration':
				
				if($options=='')
					$vlu= $listitem['duration'];
				else
				{
					$secs=(int)$listitem['duration'];
					$vlu=date($options,mktime(0,0,$secs));
				}

			break;
		
			case 'publisheddate':
				
				if($options=='')
					$vlu= $listitem['publisheddate'];
				else
					$vlu=date($options,strtotime($listitem['publisheddate']));

			break;
			
			default:
				if(in_array($fld,$tableFields ))
					$vlu=$listitem[$fld];
			break;
		}
		
		return $vlu; 
	}

	
	public static function isThumbnailDataEmpty($fld,$listitem,&$tableFields,$theImage)
	{
		foreach($tableFields as $tf)
		{
			if($fld==$tf)
			{
				if($listitem[$tf]=='')
					return true;
				else
					return false;
			}
		}
		
		switch($fld)
		{
			case 'image':
				if($theImage=='')
					return true;
				else
					return false;
			break;
		
			case 'a':
					return false;
			break;
		
			case '/a':
					return false;
			break;
		
			case 'link':
					return false;
			break;
		
			case 'viewcount':
				//if($listitem['statistics_viewCount']==0)
				//	return true;
				//else
					return false;
			break;
		
			case 'favcount':
				if($listitem['statistics_favoriteCount']==0)
					return true;
				else
					return false;
			break;
		
		}
		return true;

	}
	
	
	public static function ShowActiveVideo($gallery_list,$width,$height,$videoid, &$videolist_row, &$theme_row,$videosource='')
	{
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($theme_row->prepareheadtags)
		{
			
			$conf = JFactory::getConfig();
			
			if($JoomlaVersionRelease>=3)
				$sitename = $conf->get('config.sitename');
			else
				$sitename = $conf->getValue('config.sitename');
			
			
			$mydoc = JFactory::getDocument();
			
			$title=YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list);
			
			$mydoc->setTitle($title.' - '.$sitename);
			
		}
		
		
		$result='';
		
		if($videoid)
		{
			$vpoptions=array();
			$vpoptions['width']=$width;
			$vpoptions['height']=$height;
			
			$vpoptions['videoid']=$videoid;
			$vpoptions['autoplay']=$theme_row->autoplay;
			$vpoptions['showinfo']=$theme_row->showinfo;
			$vpoptions['relatedvideos']=$theme_row->related;
			$vpoptions['repeat']=$theme_row->repeat;
			$vpoptions['border']=$theme_row->border;
			$vpoptions['color1']=$theme_row->color1;
			$vpoptions['color2']=$theme_row->color2;
		

			$vpoptions['controls']=$theme_row->controls;
			$vpoptions['playertype']=$theme_row->playertype;
			$vpoptions['youtubeparams']=$theme_row->youtubeparams;
		
			$vpoptions['fullscreen']=$theme_row->fullscreen;
				
			if($videosource!='')
				$vs=$videosource;
			else
				$vs=YoutubeGalleryLayoutRenderer::getVideoSourceByID($videoid,$gallery_list);
			
			
			if($theme_row->prepareheadtags)
			{
				$theImage=YoutubeGalleryLayoutRenderer::getVideoImageByID($videoid,$gallery_list);
				if($theImage!='')
				{
					$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
					$document = JFactory::getDocument();
					
					$imagelink=(strpos($theImage,'http://')===false and strpos($theImage,'https://')===false  ? $curPageUrl.'/' : '').$theImage;
					
					if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
						$imagelink=str_replace('http://','https://',$imagelink);
					
					$document->addCustomTag('<link rel="image_src" href="'.$imagelink.'" />');
					
				}
			}
			
			if((int)$vpoptions['width']==0)
				$width=400;
			else
				$width=(int)$vpoptions['width'];
			
			
			if((int)$vpoptions['height']==0)
				$height=200;
			else
				$height=(int)$vpoptions['height'];

			
			switch($vs)
			{
				case 'break':
					require_once('break.php');
					$result.=VideoSource_Break::renderBreakPlayer($vpoptions, $width, $height, $videolist_row, $theme_row);
					break;
				
	
				case 'vimeo':
					require_once('vimeo.php');
					$result.=VideoSource_Vimeo::renderVimeoPlayer($vpoptions, $width, $height, $videolist_row,$theme_row);
					break;
				
				case 'own3dtvlive':
					require_once('own3dtvlive.php');
					$result.=VideoSource_Own3DTvLive::renderOwn3DTvLivePlayer($vpoptions, $width, $height, $videolist_row,$theme_row);
					break;
				
				case 'own3dtvvideo':
					require_once('own3dtvvideo.php');
					$result.=VideoSource_Own3DTvVideo::renderOwn3DTvVideoPlayer($vpoptions, $width, $height, $videolist_row,$theme_row);
					break;
			
				case 'youtube':
					if($vpoptions['autoplay']==1)
					{
						$pl=YoutubeGalleryLayoutRenderer::getYoutubeVideoIdsOnly($gallery_list,$videoid);
						$shorten_pl=array();
						$i=0;
						foreach($pl as $p)
						{
							$i++;
							if($i>20)
								break;
							$shorten_pl[]=$p;
						}
						
						
						$YoutubeVideoList=implode(',',$shorten_pl);
					
						if($vpoptions['youtubeparams']=='')
							$vpoptions['youtubeparams']='playlist='.$YoutubeVideoList;
						else
							$vpoptions['youtubeparams'].=';playlist='.$YoutubeVideoList;
					}
					
					require_once('youtube.php');
					$temp=VideoSource_Youtube::renderYouTubePlayer($vpoptions, $width, $height, $videolist_row,$theme_row);
				
				

					
				
					if($temp!='')
					{
						if($theme_row->useglass or $theme_row->logocover)
							$result.='<div style="position: relative;width:'.$width.'px;height:'.$height.'px;padding:0;">';
						
						$result.=$temp;
					
						if($theme_row->logocover)
						{
						
							//border: #00ff00 dotted 1px;
							$result.='
							<div style="position: absolute;'.($theme_row->controls ? 'bottom:25px;' : 'bottom:0px;').'right:0px;
								margin-top:0px;margin-left:0px;">
							<img src="'.$theme_row->logocover.'" style="margin:0px;padding:0px;display:block;border: none;" />
							</div>';
						}
					
						if($theme_row->useglass)
						{
							//25px is a height of navigation bar of youtube player.
							//border: #ff0000 dotted 1px;
							$result.='
							<div style="position: absolute;background-image: url(\'components/com_youtubegallery/images/dot.png\');
								top:0px;left:0px;
								width:'.$width.'px;height:'.($height-25).'px;margin-top:0px;margin-left:0px;padding:0px;">
							</div>';
						}
					
					
					
						if($theme_row->useglass or $theme_row->logocover)
							$result.='</div>';
					}
				
				
					break;
				case 'google':
					require_once('google.php');
					$result.=VideoSource_Google::renderGooglePlayer($vpoptions, $width, $height, $videolist_row, $theme_row);
					break;
				case 'yahoo':
					require_once('yahoo.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;

					$result.=VideoSource_Yahoo::renderYahooPlayer($vpoptions, $width, $height, $videolist_row, $theme_row);
					
					break;
			
				case 'collegehumor':
					require_once('collegehumor.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
					$result.=VideoSource_CollegeHumor::renderCollegeHumorPlayer($vpoptions, $width, $height, $videolist_row, $theme_row);
					
					break;
				
				case 'dailymotion':
					require_once('dailymotion.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
					$result.=VideoSource_DailyMotion::renderDailyMotionPlayer($vpoptions, $width, $height, $videolist_row, $theme_row);
					
					break;
				
			}
		
		}
		
		
		
		


		if($videoid!='****youtubegallery-video-id****')
			$result=str_replace('****youtubegallery-video-id****',$videoid,$result);
			
			
		$result='<div id="YoutubeGallerySecondaryContainer'.$videolist_row->id.'" style="width:'.$width.'px;height:'.$height.'px;">'.$result.'</div>';
		
		
		
		return $result;
		
	}//function ShowAciveVideo()
	
	
	public static function addHotReloadScript(&$gallery_list,$width,$height,&$videolist_row, &$theme_row)
	{

			$vs=array('youtube','vimeo','break','own3dtvlive','own3dtvvideo','google','yahoo','collegehumor','dailymotion');
			
			$document = JFactory::getDocument();
			////<![CDATA[//]]>//]]>
			$hotrefreshscript='
<!-- Youtube Gallery Hot Video Switch -->
<script type="text/javascript">

	var YoutubeGalleryVideoSources'.$videolist_row->id.' = ["'.implode('", "',$vs).'"];
	var YoutubeGalleryPlayer'.$videolist_row->id.' = new Array;
';

			$i=0;
			
			foreach($vs as $v)
			{
				$player_code='<!-- '.$v.' player -->'.YoutubeGalleryLayoutRenderer::ShowActiveVideo($gallery_list,$width,$height,'****youtubegallery-video-id****', $videolist_row, $theme_row,$v);
				$hotrefreshscript.='
	YoutubeGalleryPlayer'.$videolist_row->id.'['.$i.']=\''.$player_code.'\';';
				$i++;
			}

			$hotrefreshscript.='

	function YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(videoid,videosource,id)
	{
		var i=YoutubeGalleryVideoSources'.$videolist_row->id.'.indexOf(videosource);
		if(i==-1)
			playercode="";
		else
			playercode=YoutubeGalleryPlayer'.$videolist_row->id.'[i];
		playercode=playercode.replace("****youtubegallery-video-id****",videoid);
		
		var title=document.getElementById("YoutubeGalleryThumbTitle'.$videolist_row->id.'_"+id).innerHTML
		var description=document.getElementById("YoutubeGalleryThumbDescription'.$videolist_row->id.'_"+id).innerHTML
		
		document.getElementById("YoutubeGallerySecondaryContainer'.$videolist_row->id.'").innerHTML=playercode;
		
		var tObj=document.getElementById("YoutubeGalleryVideoTitle'.$videolist_row->id.'");
		var dObj=document.getElementById("YoutubeGalleryVideoDescription'.$videolist_row->id.'");
		
		if(tObj)
		{
			tObj.innerHTML=title;
		}
		
		if(dObj)
		{
			dObj.innerHTML=description;
		}
		
	}
</script>

';

			$document->addCustomTag($hotrefreshscript);
		
		
	}
	
	public static function getYoutubeVideoIdsOnly(&$gallery_list,$current_videoid)
	{
		$theList1=array();
		
		$theList2=array();
		
			
		$current_videoid_found=false;
		
		foreach($gallery_list as $gl_row)	
        {
			if($gl_row['videoid']==$current_videoid)
			{
				$current_videoid_found=true;
			}
			else
			{

				if($gl_row['videosource']=='youtube')
				{
					if($current_videoid_found)
						$theList1[]=$gl_row['videoid'];
					else
						$theList2[]=$gl_row['videoid'];
				}
			}
			
			
		}//foreach
		
		return array_merge($theList1,$theList2);
	}
	
	public static function getThumbnailByID($videoid,&$gallery_list)
	{
		foreach($gallery_list as $gl_row)	
        {
			if($gl_row['videoid']==$videoid)
				return $gl_row['imageurl'];
		}
		return '';
	}
	
	public static function getVideoSourceByID($videoid,&$gallery_list)
	{
		foreach($gallery_list as $gl_row)	
        {
			if($gl_row['videoid']==$videoid)
				return $gl_row['videosource'];
		}
		return '';
	}
	
	
	public static function getVideoImageByID($videoid,&$gallery_list)
	{
		foreach($gallery_list as $gl_row)	
        {
			if($gl_row['videoid']==$videoid)
				return $gl_row['imageurl'];
		}
		return '';
	}

}