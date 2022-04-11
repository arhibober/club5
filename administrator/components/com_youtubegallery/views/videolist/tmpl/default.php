<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

?>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&view=videolist'); ?>" method="post" name="adminForm" id="youtubegallery-form">

	<h3>Videos of this page: <?php echo count($this->items); ?></h3>
	<?php

		if(count($this->items)==0)
			echo '<p><b>No videos found. Try to "Refresh" the gallery, or add videos. </b><br/>To refresh go to previous page, check the "checkbox" next to this gallery and click "Refresh" button in toolbar.</p>';
	?>

	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'COM_YOUTUBEGALLERY_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				<a href="http://joomlaboat.com/contact-us" target="_blank" style="font-weight: bold;margin-left:20px;">Help (Contact Tech-Support)</a>
			</td>

		</tr>
	</table>



        <table class="adminlist">
                <thead><?php echo $this->loadTemplate('head');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body');?></tbody>
        </table>
        <div>
                <input type="hidden" id="task" name="task" value="" />
				
				
<?php 				/* <input type="hidden" name="view" value="videolist" /> */ ?>
				<input type="hidden" name="listid" value="<?php echo JRequest::getInt( 'listid'); ?>" />

                <?php echo JHtml::_('form.token'); ?>
        </div>
		
	<p>
		If status of the video is "-", it means that it's not checked yet.<br/>Go to front-end and find this video in your gallery, as soon as you see it's thumbnail, it's data will be written here.
	</p>
	
</form>


