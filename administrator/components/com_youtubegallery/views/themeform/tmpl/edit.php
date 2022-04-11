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


				<?php if($this->item->readonly):
				
				echo '<p style="margin-top:30px;margin-bottom:30px;text-align:center;font-size:20px;font-weight:bold;">
				To edit imported Themes please upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a>.</p>';
				
				?>
				
                <?php else: ?>
				
				
        <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                
                
                <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_FORM_DETAILS' ); ?> (Free Version)</legend>
                
             

				
				<table style="border:none;">
                        <tbody>
										<tr><td><?php echo $this->form->getLabel('themename'); ?></td><td>:</td><td><?php echo $this->form->getInput('themename'); ?></td></tr>
										<tr><td colspan="3"></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('width'); ?></td><td>:</td><td><?php echo $this->form->getInput('width'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('height'); ?></td><td>:</td><td><?php echo $this->form->getInput('height'); ?></td></tr>

						</tbody>
                </table>
               <p><br /><br /></p>
                <?php $d=($this->form->getvalue('customlayout')!='' ? 'none' : 'block' ); ?>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="layouttab_0" class="layouttab_content">
                        <div style="margin-top:-50px;">
                                 <?php //Layout Wizard ?> <h4>Layout Wizard | <a href="javascript: SwithTabs('layouttab_',2,1)">Custom Layout</a></h4>
                        </div>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('showlistname'); ?></td><td>:</td><td><?php echo $this->form->getInput('showlistname'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('listnamestyle'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('pagination'); ?></td><td>:</td><td><?php echo $this->form->getInput('pagination'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showactivevideotitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('showactivevideotitle'); ?></td></tr>
										<tr><td><?php echo $this->form->getLabel('activevideotitlestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('activevideotitlestyle'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playvideo'); ?></td><td>:</td><td><?php echo $this->form->getInput('playvideo'); ?></td></tr>

                                        <tr><td><?php echo $this->form->getLabel('descr_style'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('descr_position'); ?></td><td>:</td><td><?php echo $this->form->getInput('descr_position'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('description'); ?></td><td>:</td><td><?php echo $this->form->getInput('description'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('cssstyle'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
                                </tbody>
                        </table>
                </div>
                
                <?php $d=($this->form->getvalue('customlayout')!='' ? 'block' : 'none' ); ?>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="layouttab_1" class="layouttab_content">
                        <div style="margin-top:-50px;">
                                 <?php //Layout Wizard ?> <h4><a href="javascript: SwithTabs('layouttab_',2,0)">Layout Wizard</a> | Custom Layout</h4>
                        </div>
                        <table style="border:none;">
                                <tbody>
                                        <tr>
                                                <td valign="top"><b><?php echo $this->form->getLabel('customlayout'); ?><b/><br/>
														<?php echo $this->form->getInput('customlayout'); ?></td>
                                                <td valign="top">
                                                        
                                                        <b>Layout tags:</b>
                                                        
                                                        <table>
                                                                <tbody>
                                                                        <tr><td valign="top">[listname]</td><td>:</td><td>List Name</td></tr>
																		<tr><td valign="top">[videotitle]</td><td>:</td><td>Show Active Video Title</td></tr>
                                                                        <tr><td valign="top">[videodescription]</td><td>:</td><td>Show Active Video Description</td></tr>
                                                                        <tr><td valign="top">[videoplayer]</td><td>:</td><td>Player</td></tr>
                                                                        <tr><td valign="top">[navigationbar]</td><td>:</td><td>Navigation Bar (list or table of thumbnails)</td></tr>
																		<tr><td valign="top">[navigationbar:columns,bar width]</td><td>:</td><td>
																		
																				Navigation Bar (list or table of thumbnails)
																				with parameters to overwrite number of columns and bar width
																				<br/>

																		</td></tr>
																		<tr><td valign="top">[thumbnails]</td><td>:</td><td>List of thumbnails (no formating except Navigation Bar Custom Layout)</td></tr>
                                                                        <tr><td valign="top">[count]</td><td>:</td><td>Number of videos (thumbnails)</td></tr>
                                                                        <tr><td valign="top">[pagination]</td><td>:</td><td>Pagination</td></tr>
																		<tr><td valign="top">[width]</td><td>:</td><td>Video Area Width</td></tr>
																		<tr><td valign="top">[height]</td><td>:</td><td>Video Area Height</td></tr>
										                                <tr><td valign="top">[instanceid]</td><td>:</td><td>Instance ID</td></tr>
                                                                        <tr><td valign="top">[if:TAGNAME] and [endif:TAGNAME]</td><td>:</td><td>Conditional tags</td></tr>
																		<tr><td valign="top">[notif:TAGNAME] and [endif:TAGNAME]</td><td>:</td><td>Conditional tags</td></tr>
																		<tr><td valign="top" colspan="3" ><b>Tags to get values directly from Theme Settings:</b><br/>
																		[bgcolor], [cols], [cssstyle], [navbarstyle], [thumbnailstyle], [linestyle], [activevideotitlestyle],
																		[listnamestyle], [color1], [color2], [descr_style], [rel], [hrefaddon], [mediafolder]
																		</td></tr>
                                                                </tbody>
                                                        </table>
                                                        
                                                        <br />
                                                        <b>Example:</b><br/>
                                                        
                                                        
                                                        <textarea cols="30" rows="12" readonly="readonly" style="color: #00ff00;background: black;"><h3>[listname]</h3>
[if:videodescription]
		<h4>[videodescription]</h4>
[endif:videodescription]

<!-- Video Player -->
[videoplayer]

[if:videotitle]
		<h3>[videotitle]</h3>
[endif:videotitle]

[if:count]
		<hr  style="border-color:#E7E7E9;border-style:solid;border-width:1px;"  />
		[navigationbar:number of columns,width in px]
[endif:count]
														</textarea>
                                                
                                                </td>                                
                                                      
                                        
                                        </tr>
                                                                                
                                </tbody>
                        </table>
                </div>
                
                

                <?php //Navigation Bar ?><h4>Navigation Bar</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
										
										
                                        <tr><td><?php echo $this->form->getLabel('navbarstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('navbarstyle'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('cols'); ?></td><td>:</td><td><?php echo $this->form->getInput('cols'); ?></td></tr>
										
					<tr><td><?php echo $this->form->getLabel('linestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('linestyle'); ?></td></tr><!-- depricated - can be done with navbarstyle -->
										
					<tr><td><?php echo $this->form->getLabel('orderby'); ?></td><td>:</td><td><?php echo $this->form->getInput('orderby'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('customlimit'); ?></td><td>:</td><td><?php echo $this->form->getInput('customlimit'); ?></td></tr>
                                </tbody>
                        </table>
						
			<br/><br/>
										
								
			<?php $d=($this->form->getvalue('customnavlayout')!='' ? 'none' : 'block' ); ?>
			<div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="navlayouttab_0" class="layouttab_content">
				<div style="margin-top:-50px;">
					<?php //Nav Layout Wizard ?> <h4>Navigation Bar Layout Wizard | <a href="javascript: SwithTabs('navlayouttab_',2,1)">Custom Navigation Bar Layout</a></h4>
				</div>
				<table style="border:none;">
					<tbody>
						<tr><td><?php echo $this->form->getLabel('bgcolor'); ?></td><td>:</td><td><?php echo $this->form->getInput('bgcolor'); ?></td></tr><!-- depricated - part of layout -->
						<tr><td><?php echo $this->form->getLabel('thumbnailstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('thumbnailstyle'); ?></td></tr><!-- depricated - part of layout  -->
						<tr><td><?php echo $this->form->getLabel('showtitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('showtitle'); ?></td></tr><!-- depricated - part of layout -->
					</tbody>
				</table>
			</div>
										
						
			<?php $d=($this->form->getvalue('customnavlayout')!='' ? 'block' : 'none' ); ?>
			<div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="navlayouttab_1" class="layouttab_content">
				<div style="margin-top:-50px;">
					<?php //Nav Layout Wizard ?> <h4><a href="javascript: SwithTabs('navlayouttab_',2,0)">Navigation Bar Layout Wizard</a> | Custom Navigation Bar Layout</h4>
				</div>
				<table style="border:none;">
				<tbody>
                                        <tr>
                                                <td valign="top">
							<b><?php echo $this->form->getLabel('customnavlayout'); ?></b><br/>
							<?php echo $this->form->getInput('customnavlayout'); ?>
						</td>
												
						<td valign="top" width="350">
							<b>Layout tags:</b><br/>
                                                        
					                <table>
						                <tbody>
									<tr><td valign="top">[image]</td><td>:</td><td>Returns Thumnail Image as a Tag</td></tr>
									<tr><td valign="top">[imageurl]</td><td>:</td><td>Only URL to thumbnail image</td></tr>
                                    <tr><td valign="top">[title]</td><td>:</td><td>Video Title</td></tr>
									<tr><td valign="top">[description]</td><td>:</td><td>Video Description</td></tr>
									<tr><td valign="top">[link]</td><td>:</td><td>Link to the Video (on this website)</td></tr>
									<tr><td valign="top">[a] and [/a]</td><td>:</td><td>Complete anchor tag with the link and title</td></tr>
									<tr><td valign="top">[publisheddate]</td><td>:</td><td>Publish date</td></tr>
									<tr><td valign="top">[publisheddate:date format]</td><td>:</td><td>Publish date with custom date format. Example: [publisheddate:F j, Y]. <a href="http://php.net/manual/en/function.date.php" target="_blank">More here.</a></td></tr>
									<tr><td valign="top">[duration]</td><td>:</td><td>Duration in Seconds</td></tr>
									<tr><td valign="top">[duration:time format]</td><td>:</td><td>Duration with custom format. Example: [duration:H:i:s]. <a href="http://php.net/manual/en/function.date.php" target="_blank">More here.</a></td></tr>
									<tr><td valign="top">[viewcount]</td><td>:</td><td>View Count</td></tr>
									<tr><td valign="top">[favcount]</td><td>:</td><td>Favorites Count</td></tr>
									<tr><td valign="top">[rating_average]</td><td>:</td><td>Average Rating</td></tr>
									<tr><td valign="top">[rating_max]</td><td>:</td><td>Max. Rating</td></tr>
									<tr><td valign="top">[rating_min]</td><td>:</td><td>Min. Rating</td></tr>
									<tr><td valign="top">[rating_numRaters]</td><td>:</td><td>Number of Raters</td></tr>
									<tr><td valign="top">[keywords]</td><td>:</td><td>Keywords</td></tr>
									<tr><td valign="top">[videosource]</td><td>:</td><td>Video Source</td></tr>
									<tr><td valign="top">[videoid]</td><td>:</td><td>Video ID</td></tr>

									<tr><td valign="top" colspan="3">[if:TAGNAME] and [endif:TAGNAME] - Conditional tags</td></tr>
									<tr><td valign="top" colspan="3">[notif:TAGNAME] and [endif:TAGNAME] - Conditional tags</td></tr>
									
                                                                </tbody>
                                                        </table>
						</td>
												
                                                <td valign="top">
							<b><label id="jform_customnavlayout-lbl" for="jform_customnavlayout" class="hasTip" title="Custom Layout::Example">Example:</label></b><br/>
                                                        
                                                        
                                                        <textarea cols="15" rows="12" readonly="readonly" style="color: #00ff00;background: black;">

[image]<br/>
[title]<br/>

[if:viewcount]
		Views: [viewcount]
[endif:viewcount]
														
</textarea>
                                                
                                                </td>                                
                                        </tr>
                                </tbody>
				</table>
			</div>
		</div>
		
			
			
                
                <?php //Player Settings ?><h4>Player Settings</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('border'); ?></td><td>:</td><td><?php echo $this->form->getInput('border'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color1'); ?></td><td>:</td><td><?php echo $this->form->getInput('color1'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color2'); ?></td><td>:</td><td><?php echo $this->form->getInput('color2'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('autoplay'); ?></td><td>:</td><td><?php echo $this->form->getInput('autoplay'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('repeat'); ?></td><td>:</td><td><?php echo $this->form->getInput('repeat'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('fullscreen'); ?></td><td>:</td><td><?php echo $this->form->getInput('fullscreen'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('related'); ?></td><td>:</td><td><?php echo $this->form->getInput('related'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showinfo'); ?></td><td>:</td><td><?php echo $this->form->getInput('showinfo'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('controls'); ?></td><td>:</td><td><?php echo $this->form->getInput('controls'); ?></td></tr>
										<tr><td><?php echo $this->form->getLabel('muteonplay'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
										<tr><td><?php echo $this->form->getLabel('volume'); ?></td><td>:</td><td><?php echo $this->form->getInput('volume'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playertype'); ?></td><td>:</td><td><?php echo $this->form->getInput('playertype'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('youtubeparams'); ?></td><td>:</td><td><?php echo $this->form->getInput('youtubeparams'); ?></td></tr>
                                        
                                </tbody>
                        </table>
                </div>
			
			
                <?php //Misc ?><h4>Misc</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
                                        
                                        <tr><td><?php echo $this->form->getLabel('openinnewwindow'); ?></td><td>:</td><td><?php echo $this->form->getInput('openinnewwindow'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('rel'); ?></td><td>:</td><td><?php echo $this->form->getInput('rel'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('hrefaddon'); ?></td><td>:</td><td><?php echo $this->form->getInput('hrefaddon'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('useglass'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('logocover'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
                                        
                                        <tr><td><?php echo $this->form->getLabel('prepareheadtags'); ?></td><td>:</td><td><?php echo $this->form->getInput('prepareheadtags'); ?></td></tr>
										<tr><td><?php echo $this->form->getLabel('responsive'); ?></td><td>:</td><td><?php echo $this->form->getInput('responsive'); ?></td></tr>
										<tr><td><?php echo $this->form->getLabel('nocookie'); ?> <span style="font-size:10px;color:#dd9900;">*NEW*</span></td><td>:</td><td><?php echo $this->form->getInput('nocookie'); ?></td></tr>
										
										<tr><td><?php echo $this->form->getLabel('mediafolder'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
										<tr><td><?php echo $this->form->getLabel('headscript'); ?></td><td>:</td><td>AVAILABLE IN "PRO" VERSION ONLY</td></tr>
										<tr><td><?php echo $this->form->getLabel('themedescription'); ?></td><td>:</td><td><?php echo $this->form->getInput('themedescription'); ?></td></tr>
										

                                </tbody>
                        </table>
                </div>
				
				

        </fieldset>
		
		<p><a href="http://joomlaboat.com/youtube-gallery-themes" target="_blank" style="font-weight: bold;margin-left:20px;">Get more Themes</a></p>
		
		<?php endif; ?>
        
                <input type="hidden" name="task" value="themeform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        
</form>