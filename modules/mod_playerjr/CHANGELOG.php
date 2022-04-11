<?php
/**
*Jw Player Module : mod_playerjr
* @version mod_playerjr$Id$
* @package mod_playerjr
* @subpackage CHANGELOG.php
* @author joomlarulez.
* @copyright (C) www.joomlarulez.com
* @license Limited  http://www.gnu.org/licenses/gpl.html
* @final 2.7.0
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
1. Copyright and disclaimer
---------------------------
This application is opensource software released under the GPL.  Please
see source code and the JoomlaRuleZ! License Guidelines 
http://www.joomlarulez.com/joomlarulez-license.html


2. Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
JW Player Module 2.7.0, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.

Legend:

* -> Security Fix
# -> Bug Fix
$ -> Language fix or change
+ -> Addition
^ -> Change
- -> Removed
! -> Note

-------------------- JW Player Module 2.7.0 03 April 2011 ------------------

^ Upgrade to JW Player 5.5.1641.
^ Upgrade jwplayer.js.

^ Optimize mod_playerjr.php script, reducing code size around 50%.
^ change xml name flashvars this for optimize script, this mean dev have to check those flasvars after upgrade
volume, stretching, smoothing, shuffle, repeat, icons, bufferlength, autostart, wmode, start, streamer, image, 
screencolor, lightcolor, frontcolor, backcolor, width, height, playlistsize, controlbar, playlist

-------------------- JW Player Module 2.6.1 30 January 2011 ------------------

# fix language error under J1.6.

-------------------- JW Player Module 2.6.0 13 January 2011 ------------------

+ Add Joomla! 1.6 Support.
^ Modify xml config file for J1.6 support.
^ Modify ini language file for J1.6 support.
+ Add sys.ini language file for J1.6 support. 

^ Set flashvars only if need.
^ Upgrade to JW Player 5.4.1530.
^ Upgrade jwplayer.js.

-------------------- JW Player Module 2.5.0 19 December 2010 ------------------

^ Upgrade to JW Player 5.4.1492
^ Upgrade jwplayer.js

^ Allow playlist flashvar left.
^ Change backend Layout.
^ Set joomlarulez link label and description in language ini file.

-------------------- JW Player Module 2.4.1 29 October 2010 ------------------

^ Upgrade to JW Player 5.3.1397
^ Upgrade jwplayer.js

-------------------- JW Player Module 2.4.0 24 October 2010 ------------------

^ Upgrade to JW Player 5.3.1356.
^ replace video.flv by video.mp4.

+ Add jwplayer.js.
+ Add thumbnail automatically for youtube video.

# Set image flashvar only if need.

-------------------- JW Player Module 2.3.0 17 July 2010 ------------------

^ Upgrade to JW Player 5.2.1151.

-------------------- JW Player Module 2.2.0 14 June 2010 ------------------

^ Upgrade to JW Player 5.2.1065.

+ Add top Playlist position flashvars

-------------------- JW Player Module 2.1.2 04 April 2010 ------------------

^ Change xml installer by an upgrade installer

-------------------- JW Player Module 2.1.1 20 March 2010 ------------------

^ Upgrade to JW Player 5.1.897.

-------------------- JW Player Module 2.1.0 08 March 2010 ------------------

^ Upgrade to JW Player 5.1.854.

-------------------- JW Player Module 2.0.2 17 January 2010 ------------------

# Fix HTML error under IE8 in backend administration

-------------------- JW Player Module 2.0.1 31 December 2009 ------------------

# Flashwars file to playlistfile according to rss link or single file

-------------------- JW Player Module 2.0.0 20 December 2009 ------------------

^ Upgrade to JW Player 5.0.753.

^ Upgrade to swfobject 2.2.

- Layout : Remove logo for  JW 5.x Version.

- Remove Fix Mootools and Jquery Conflict, not longuer need since upgrade to swfobject 2.2.

+ Add en-GB.ini Language Backend.

^ Clean up Code.
^ Reduce variables set when unused, RAM server optimization.
^ Change XML Layout.

-------------------- JW Player Module 1.5.2 15 November 2009 ------------------

^ Upgrade to the new Adsolution Channel Code

-------------------- JW Player Module 1.5.1 12 November 2009 ------------------

^ Upgrade to JW Player 4.6.485

# Fix error notice Undefined variable, when fixmootools and fixjquery is force to no

-------------------- JW Player Module 1.5.0 28 October 2009 ------------------

^ Upgrade to JW Player 4.6

- Removed expressInstall.swf in the package (no longuer need)

+ Add Top Position for Control Bar

-------------------- JW Player Module 1.4.0 30 September 2009 ------------------

+ Add some Link helper in xml package
^ Change layout in xml package 

+ Behaviour : Buffer Length

+ Add Thumbnail, location of a preview image

+ Add Auto detect mootools/jquery use (Always have choice to force prevent conflict to Yes or No)

+ Add choice to display or not Joomlarulez.com Link, if not please make a donation

# Fix conflict with jquery

- Remove ID example for adsolution plugin (this for prevent some missunderstood, ID example are only in description)

-------------------- JW Player Module 1.3.0 17 August 2009 ------------------

+ Automatic Module Setting Suffix (Multiple Player with different Playlist on same page)

+ Behaviour : Icons (Hide or not the play button)
+ Behaviour : Logo (Location of an external jpg, png or gif image to show in a corner of the display.)
+ Behaviour : Smoothing (Setting to get performance improvements with old computers / big files)
+ Behaviour : Stretching (Defines how to resize images in the display)

+ Add RTMP/HTTP Streamer support (Single File)
+ Add Start RTMP/HTTP (Single File)

-------------------- JW Player Module 1.2.1 27 July 2009 ------------------

+ Add CDATA information during install 
+ Add Module class SFX

-------------------- JW Player Module 1.2.0 19 July 2009 ------------------

^ Update JW Player to 4.5

# Fix midroll error in Adsolution Plugin

+ Add expressInstall.swf in package

+ Add LICENSES.php
+ Add CREDITS.php

-------------------- JW Player Module 1.1.2 27 May 2009 ------------------

^ Change Player Unbranded by a Branded player, License Holders have to upload by FTP the player Unbranded, See How-To here :
http://www.joomlarulez.com/faq/44-faq/56-how-to-install-your-personal-license-player.html

-------------------- JW Player Module 1.1.1 19 May 2009 ------------------

# Fix Mootools conflict under IE7

^ Put JS in HTML Head

+ Add Choose Link display to Adobe Flash Player if not installed

-------------------- JW Player Module 1.1.0 06 May 2009 ------------------

+ Ad Solution Plugin Full Support : No Hack

+ Youtube Link support for External RSS Link

^ License-holders can download an unbranded player 
with their Licence number and replace themself
the player in the directory of JW Module Player.

+ Layout : Playlist position
+ Layout : PlaylistControlbar
+ Layout : Transparence of the Playlist

+ Behaviour : Auto Start
+ Behaviour : Repeat
+ Behaviour : Shuffle
+ Behaviour : Volume

+ Changelog : Package include changelog.php

-------------------- JW Player Module 1.0.1 01 May 2009 ------------------

# Fix XML Error

-------------------- JW Player Module 1.0 20 March 2009 ------------------
Public Release