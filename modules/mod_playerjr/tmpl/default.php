<?php
/**
*Jw Player Module : mod_playerjr
* @version mod_playerjr$Id$
* @package mod_playerjr
* @subpackage default.php
* @author joomlarulez.
* @copyright (C) www.joomlarulez.com
* @license Limited  http://www.gnu.org/licenses/gpl.html
* @final 2.7.0
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

//Check if the user have player file
if ($is_modplayer_playlist != '')
{

// Construct the Global parameter for the playlist
echo 
"<div align=\"center\">";
	// set JS
	$document->addScript( "".$playersite."modules/mod_playerjr/script/swfobject_2_2.js");
	// By pass the adsolution div id or not
	if ($is_modplayer_playlist_adsenabled == true)
	{	
	echo
	"
		<div class='ltas-ad' id='mediaspacemod".$moduleclasspl_sfx."'>
			<div id='jwplayer".$moduleclasspl_sfx."'>".$is_modplayer_flash."</div>
		</div>";
	}
	else
	{
	echo
	"
		<div id='jwplayer".$moduleclasspl_sfx."'>".$is_modplayer_flash."</div>";
	}
	echo 
	"
	<script type='text/javascript'>
		var flashvars =
		{
			'".$is_modplayer_playlisttype."': '".$is_modplayer_playlist."',
			";
			$i = 1;
			while (list($key, $value) = each($is_modplayer_flashvars)) {
				if ($i > 1)
				{
			echo
			",
			";
				}
				echo
			"'".$key."': '".$value."'";
			$i++;
			}
			reset($is_modplayer_flashvars);
			echo
			"
		};
		var params =
		{
			'allowfullscreen': 'true',
			'allowscriptaccess': 'always',
			'wmode': '".$is_modplayer_flashvars["wmode"]."',
			'allownetworking': 'all'
		};
		var attributes =
		{
			'id': 'jwplayer".$moduleclasspl_sfx."',
			'name': 'jwplayer".$moduleclasspl_sfx."'
		};
		swfobject.embedSWF('".$playersite."modules/mod_playerjr/player-licensed5.swf', 'jwplayer".$moduleclasspl_sfx."', '".$is_modplayer_flashvars["width"]."', '".$is_modplayer_flashvars["height"]."', '9', false, flashvars, params, attributes);
	</script>
</div>";
// Valid or not the joomlarulezlink
if ($is_modplayer_playlist_joomlarulezlink == '1')
{			
echo 
"
<div align=\"center\">
	Powered By: <a class=\"blank\" href=\"http://www.joomlarulez.com\" target=\"_blank\">http://www.joomlarulez.com</a>
</div>
";
}
}
?>