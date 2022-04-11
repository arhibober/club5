<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
        <th width="5">
                <?php echo JText::_('COM_YOUTUBEGALLERY_ID'); ?>
        </th>
        <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
        </th>                     
        <th align="left" style="text-align:left;">
                <?php echo JText::_('COM_YOUTUBEGALLERY_THEMENAME'); ?>
        </th>
        
        <th align="left" style="text-align:left;">
                Media Folder
        </th>
        
        <th align="left" style="text-align:left;">
                Export (in Pro Version only)
        </th>
</tr>

