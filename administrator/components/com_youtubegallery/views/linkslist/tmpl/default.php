<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'COM_YOUTUBEGALLERY_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				<a href="http://joomlaboat.com/contact-us" target="_blank" style="font-weight: bold;margin-left:20px;">Help (Contact Tech-Support)</a>
			</td>
			<td nowrap="nowrap">
				<?php
				
				echo JText::_( 'COM_YOUTUBEGALLERY_CATEGORY' ).'&nbsp;';
				echo $this->lists['categories'].'&nbsp;';
				
				?>
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
                <input type="hidden" id="boxchecked" name="boxchecked" value="0" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>


