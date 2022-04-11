<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
//<input type="hidden" name="view" value="themelist" />

//JRequest::setVar( 'view', 'themelist');

?>
<form action="index.php?option=com_youtubegallery&view=themelist" method="post" name="adminForm" id="youtubegallery-form" class="form-validate" enctype="multipart/form-data">


				<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
				
				<div style="width: 400px;margin:50px auto;font-size:18px;position: relative;">
					<?php echo JText::_('COM_YOUTUBEGALLERY_THEME_UPLOADFILE'); ?>: <input name="themefile" id="themefile" type="file" style="font-size:18px;" />
					
					<br/>
					
					<div style="width: 200px;margin:20px auto;font-size:18px;position: relative;">
						<input type="submit" value="<?php echo JText::_('COM_YOUTUBEGALLERY_THEME_UPLOADFILE_BUTTON'); ?>" />
					</div>
				</div>
				
				
                <input type="hidden" name="task" value="themeimport.upload" />
				
				
                <?php echo JHtml::_('form.token'); ?>

</form>