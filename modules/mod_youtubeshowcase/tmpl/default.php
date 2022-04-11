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
defined( '_JEXEC' ) or die( 'Restricted access' );
?>


<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_youtubeshowcase/js/jquery.min.js"></script>
<script>
String.prototype.startsWith = function(str){
    return (this.indexOf(str) === 0);
}


jQuery.fn.ytplaylist = function(options) {
 
  // default settings
  var options = jQuery.extend( {
    holderId: 'ytvideo',
	playerHeight: <?php echo $video_panel_height;?>,
	playerWidth: <?php echo $video_panel_width;?>,
	addThumbs: false,
	thumbSize: 'small',
	showInline: false,
	autoPlay: true,
	showRelated: true,
	allowFullScreen: false
  },options);
 
  return this.each(function() {
							
   		var $el = $(this);
		
		var autoPlay = "";
		var showRelated = "&rel=0";
		var fullScreen = "";
		if(options.autoPlay) autoPlay = "&autoplay=1"; 
		if(options.showRelated) showRelated = "&rel=1"; 
		if(options.allowFullScreen) fullScreen = "&fs=1"; 
		
		//throw a youtube player in
		function playOld(id) {
		   var html  = '';
	
		   html += '<object height="'+options.playerHeight+'" width="'+options.playerWidth+'">';
		   html += '<param name="movie" value="http://www.youtube.com/v/'+id+autoPlay+showRelated+fullScreen+'"> </param>';
		   html += '<param name="wmode" value="transparent"> </param>';
		   if(options.allowFullScreen) { 
		   		html += '<param name="allowfullscreen" value="true"> </param>'; 
		   }
		   html += '<embed src="http://www.youtube.com/v/'+id+autoPlay+showRelated+fullScreen+'"';
		   if(options.allowFullScreen) { 
		   		html += ' allowfullscreen="true" '; 
		   	}
		   html += 'type="application/x-shockwave-flash" wmode="transparent"  height="'+options.playerHeight+'" width="'+options.playerWidth+'"></embed>';
		   html += '</object>';
			
		   return html;
		};
		
		
		function playNew (id) {
		  var html = '';
		  html += '<iframe width="'+ options.playerWidth +'" height="'+ options.playerHeight +'"';
		  html += ' src="http://www.youtube.com/embed/'+ id +'" frameborder="0"';
		  hml += ' allowfullscreen></iframe>';
		}
		
		
		//grab a youtube id from a (clean, no querystring) url (thanks to http://jquery-howto.blogspot.com/2009/05/jyoutube-jquery-youtube-thumbnail.html)
		function youtubeid(url) {
			var ytid = url.match("[\\?&]v=([^&#]*)");
			ytid = ytid[1];
			return ytid;
		};
		
		
		
		//
		$el.children('li').each(function() {
            $(this).find('a').each(function() {
                var thisHref = $(this).attr('href');
                
                //old-style youtube links
                if (thisHref.startsWith('http://www.youtube.com')) {
                    $(this).addClass('yt-vid');
                    $(this).data('yt-id', youtubeid(thisHref) );
                }
                //new style youtu.be links
                else if (thisHref.startsWith('http://youtu.be')) {
                    $(this).addClass('yt-vid');
                    var id = thisHref.substr(thisHref.lastIndexOf("/") + 1);
                    $(this).data('yt-id', id );
                }
                else {
                    //must be an image link (naive)
                    $(this).addClass('img-link');
                }
                
               // alert(thisHref);
            });
		});
		
		
		//load video on request
		$el.children("li").children("a.yt-vid").click(function() {
			
			if(options.showInline) {
				$("li.currentvideo").removeClass("currentvideo");
				$(this).parent("li").addClass("currentvideo").html(playOld($(this).data("yt-id")));
			}
			else {
				$("#"+options.holderId+"").html(playOld($(this).data("yt-id")));
				$(this).parent().parent("ul").find("li.currentvideo").removeClass("currentvideo");
				$(this).parent("li").addClass("currentvideo");
			}	
			return false;
		});

		$el.find("a.img-link").click(function() {
		    var $img = $('<img/>');
		    $img.attr({
		            src:$(this).attr('href') })
		        .css({
		            display: 'none',
		            position: 'absolute',
		            left: '0px',
		            top: '50%'});

		    if(options.showInline) {
		        $("li.currentvideo").removeClass("currentvideo");
		        $(this).parent("li").addClass("currentvideo").html($img);
	        }
	        else {
	            
	            $("#"+options.holderId+"").html($img);
				$(this).closest("ul").find("li.currentvideo").removeClass("currentvideo");
				$(this).parent("li").addClass("currentvideo");
				
	        }
            //wait for image to load (webkit!), then set width or height
            //based on dimensions of the image
            setTimeout(function() {
                if ( $img.height() < $img.width() ) {
                    $img.width(options.playerWidth).css('margin-top',parseInt($img.height()/-2, 10)).css({
                        height: 'auto'
                    });
                }
                else {
                    $img.css({
                        height: options.playerHeight,
                        width: 'auto',
                        top: '0px',
                        position: 'relative'
                    });
                }
                $img.fadeIn();
            }, 100);
            
            
		    return false;
	    });
		
		
		//do we want thumns with that?
		if(options.addThumbs) {
			
			$el.children().each(function(i){
				
				//replace first link
				var $link = $(this).find('a:first');
				var replacedText = $(this).text();
				
				if ($link.hasClass('yt-vid')) {
				    
				    if(options.thumbSize == 'small') {
    					var thumbUrl = "http://img.youtube.com/vi/"+$link.data("yt-id")+"/2.jpg";
    				}
    				else {
    					var thumbUrl = "http://img.youtube.com/vi/"+$link.data("yt-id")+"/0.jpg";
    				}

    				var thumbHtml = "<img src='"+thumbUrl+"' alt='"+replacedText+"' />";
    				$link.empty().html(thumbHtml+replacedText).attr("title", replacedText);
				    
				}
				else {
				    //is an image link
				    var $img = $('<img/>').attr('src',$link.attr('href'));
				    $link.empty().html($img).attr("title", replacedText);
				}	
				
			});	
			
		}
		
		//load inital video
		var firstVid = $el.children("li:first-child").addClass("currentvideo").children("a").click();
        
			
		
   
  });
 
};
</script>
<script type="text/ecmascript">
	
		$(function() {
			$("ul.demo2").ytplaylist({addThumbs:true, autoPlay: <?php if($video_auto_play == 1) { ?> true <?php } else { ?> false <?php } ?>, holderId: 'ytvideo2',allowFullScreen: <?php if($allow_full_screen == 1) { ?> true <?php } else { ?> false <?php } ?>});
		});
	
</script>

<style type="text/css">
#ytvideo,
#ytvideo2 {
    float: left;
	margin-right:10px;
	width:<?php echo $video_panel_width;?>px;
	height:<?php echo $video_panel_height;?>px;
	overflow:hidden;
	background: #999;
	position: relative;
	text-align: center;
}
.yt_holder {
    background: #f3f3f3;
    padding: 10px;
    float: left;
    border: 1px solid #e3e3e3;
	margin-bottom:15px;
	margin-left:<?php echo $left_margin;?>px;
	margin-top:<?php echo $top_margin;?>px;

}
.youul {
    float: left;
    margin: 0;
    padding: 0;
    width: <?php echo $thumb_panel_width;?>px;
	overflow:scroll;
	height:<?php echo $thumb_panel_height;?>px;
	
	
	
}

.youli{
    list-style-type: none;
    display:block;
    background: #f1f1f1;
    float: left;
    width: 120px;
    margin-bottom: 5px;
	padding:2px;
	text-decoration:none;
	text-align:center;

}

ul li img {
    width: <?php $ii = $thumb_panel_width - 30; echo $ii;?>px;
    float: left;
    margin-right: 5px;
    border: 1px solid #999;
}

ul li a {
    font-family: georgia;
    text-decoration: none;
    display: block;
    color: #000;
}

.currentvideo {
	background: #e6e6e6;
	outline: 1px solid red;
}	
</style>

<div id="page">
<div class="yt_holder">
<div id="ytvideo2"></div>
<ul class="demo2" style=" float: left;margin: 0;padding: 0;width: <?php echo $thumb_panel_width;?>px;overflow:scroll;height:<?php echo $thumb_panel_height;?>px;">
<li class="youli"><a href="<?php echo $youtube_url1;?>"><?php echo $youtube_video1_title;?></a></li>
<li class="youli"><a href="<?php echo $youtube_url2;?>"><?php echo $youtube_video2_title;?></a></li>
<li class="youli"><a href="<?php echo $youtube_url3;?>"><?php echo $youtube_video3_title;?></a></li>
<?php if($youtube_video_number >= 4 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url4;?>"><?php echo $youtube_video4_title;?></a></li>
<?php } if($youtube_video_number >= 5 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url5;?>"><?php echo $youtube_video5_title;?></a></li>
<?php } if($youtube_video_number >= 6 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url6;?>"><?php echo $youtube_video6_title;?></a></li>
<?php } if($youtube_video_number >= 7 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url7;?>"><?php echo $youtube_video7_title;?></a></li>
<?php } if($youtube_video_number >= 8 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url8;?>"><?php echo $youtube_video8_title;?></a></li>
<?php } if($youtube_video_number >= 9 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url9;?>"><?php echo $youtube_video9_title;?></a></li>
<?php } if($youtube_video_number >= 10 ) { ?>
<li class="youli"><a href="<?php echo $youtube_url10;?>"><?php echo $youtube_video10_title;?></a></li>
<?php } ?>
</ul>
</div>
</div>

<div style="padding:0px;margin:0px;"></div>

