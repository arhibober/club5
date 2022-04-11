<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
?>

<script language="javascript">
        function SwithTabs(nameprefix, count, activeindex)
        {
                for(i=0;i<count;i++)
                {
                        var obj=document.getElementById(nameprefix+i);
                        obj.style.display="none";
                }
                
                var obj=document.getElementById(nameprefix+activeindex);
                obj.style.display="block";
        }
</script>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="youtubegallery-form" class="form-validate">
        <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                
                
                <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_FORM_DETAILS' ); ?> (Free Version)</legend>
                
                
                <?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?>
                
				<p><br/>
                </p>
				
                <?php echo $this->form->getLabel('listname'); ?>
				<?php echo $this->form->getInput('listname'); ?>
                
				
				
				
                <p><br/>
                </p>
                <?php //Links  <h4></h4> ?>
                
                       
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('videolist'); ?>
												<br/>
												<?php echo $this->form->getInput('videolist'); ?>
										</td>
										<td style="vertical-align: bottom;" valign="bottom">
										
										<?php //-------------------------- ?>
										
										
										  <div style="border: none;padding-left:10px;margin:0px;">
                       

						<p>			
						<b>Example:</b><br/><br/>
						<b>Youtube Video</b><br/>
						http://www.youtube.com/watch?v=VSGMqfGmjG0<br/>
						http://www.youtube.com/watch?v=baLkXC_qWJY&feature=related<br/>
						</p>
						<p>
						<b>Youtube Video Playlist, Channel, and Standard Feeds</b><br/>
						http://www.youtube.com/playlist?list=PL5298F5DAD70298FC&feature=mh_lolz<br/>
						http://www.youtube.com/user/ivankomlev/favorites<br/>
						http://www.youtube.com/user/designcompasscorp<br/>
						youtubestandard:<i>video_feed</i><br/>
						
						<a href="http://joomlaboat.com/youtube-gallery/youtube-gallery-standard-feeds" target="_blank">More about Standard Video Feeds</a><br/>
						</p>
						<p>
						<b>Vimeo Video</b><br/>
						http://vimeo.com/8761657<br/>
						</p>
						<p>
						<b>Vimeo User Videos</b><br/>
						http://vimeo.com/user12346578<br/>
						</p>
						<p>
						<b>Vimeo Channel</b><br/>
						http://vimeo.com/channels/123456<br/>
						</p>
						
						<p>
						<b>Break.com</b><br/>
						http://www.break.com/pranks/biker-falls-off-dock-wall-2392751<br/>
						</p>
						
						<p>
						<b>Daily Motion</b><br/>
						http://www.dailymotion.com/video/xrcy5b<br/>
						</p>
												
						<p>
						<b>College Humor Video</b><br/>
						http://www.collegehumor.com/video/6446891/what-pi-sounds-like<br/>
						</p>
						<p>
						<b>Own3D.tv Video (live and uploaded)</b><br/>
						http://own3d.tv/l/153518<br/>
						http://own3d.tv/v/816530<br/>
						
						</p>
						
						<p>
						<b>Yahoo Video</b><br/>
						http://video.yahoo.com/watch/2342109/7336957<br/>
						
						</p>
						<p>
						Also you may have your own title, description and thumbnail for each video.	To do this type comma then "title","description","imageurl","special_parameters"<br/>
						Should look like: <b>http://www.youtube.com/watch?v=baLkXC_qWJY</b>,"<b>Video Title</b>","<b>Video description</b>","<b>images/customthumbnail.jpg</b>"<br/>
						or<br/>
						<b>http://www.youtube.com/watch?v=baLkXC_qWJY</b>,"<b>Video Title</b>",,"<b>images/customthumbnail.jpg</b>"
						</p>
						<p><b>Special parameters:</b> max-results=<i>NUMBER</i>,start-index=<i>NUMBER</i>,orderby=<i>FIELD_NAME</i>
						<br/><a href="http://joomlaboat.com/youtube-gallery/youtube-gallery-special-parameters" target="_blank">More about Special Parameters</a>
						</p>
		
                </div>
                <?php //-------------------------- ?>
										
										</td></tr>
                                </tbody>
                        </table>
                

                
                
                        <table style="border:none;">
                                <tbody>

                                        <tr><td><?php echo $this->form->getLabel('updateperiod'); ?></td><td>:</td><td><?php echo $this->form->getInput('updateperiod'); ?></td></tr>

                                </tbody>
                        </table>
                


        </fieldset>
        <div>
                <input type="hidden" name="task" value="linksform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>