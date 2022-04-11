<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/

// no direct access
defined('_JEXEC') or die ('Restricted access'); ?>

<?php /*
If needed, it can be used in this form:
$cparams->get('sender_email_address', '')
*/?>
<form class="foxform" action="<?php echo($link); ?>" method="post">

	<?php for ($n = 0; $n < 2; ++$n) { ?>
		<div>
			<input class="foxtext" type="text"
			value="<?php echo($FieldsBuilder->Fields['sender' . $n]['Name']); ?>"
			title="<?php echo($FieldsBuilder->Fields['sender' . $n]['Name']); ?>"
			name="<?php echo($FieldsBuilder->Fields['sender' . $n]['PostName']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['sender' . $n]['Width'] . $FieldsBuilder->Fields['sender' . $n]['Unit']); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;">
		</div>
	<?php } ?>


	<?php for ($n = 0; $n < 10; ++$n) {
		if (!isset($FieldsBuilder->Fields['text' . $n]) || !$FieldsBuilder->Fields['text' . $n]['Display']) continue; ?>
		<div>
			<input class="foxtext" type="<?php echo($FieldsBuilder->Fields['text' . $n]['Type']); ?>"
			value="<?php echo($FieldsBuilder->Fields['text' . $n]['Name']); ?>"
			title="<?php echo($FieldsBuilder->Fields['text' . $n]['Name']); ?>"
			name="<?php echo($FieldsBuilder->Fields['text' . $n]['PostName']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['sender' . $n]['Width'] . $FieldsBuilder->Fields['sender' . $n]['Unit']); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;">
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 3; ++$n) {
		if (!isset($FieldsBuilder->Fields['dropdown' . $n]) || !$FieldsBuilder->Fields['dropdown' . $n]['Display']) continue; ?>
		<div>
			<select class="foxtext" name="<?php echo($FieldsBuilder->Fields['dropdown' . $n]['PostName']); ?>">
			<option value=""><?php echo($FieldsBuilder->Fields['dropdown' . $n]['Name']); ?></option>
			<?php
				$options = explode(",", $FieldsBuilder->Fields['dropdown' . $n]['Values']);
				for ($o = 0; $o < count($options); ++$o) {
			?>
					<option value=" <?php echo($options[$o]); ?>"><?php echo($options[$o]); ?></option>
			<?php } ?>
			</select>
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 3; ++$n) {
		if (!isset($FieldsBuilder->Fields['textarea' . $n]) || !$FieldsBuilder->Fields['textarea' . $n]['Display']) continue; ?>
		<div>
			<textarea class="foxtext"
			name="<?php echo($FieldsBuilder->Fields['textarea' . $n]['PostName']); ?>"
			title="<?php echo($FieldsBuilder->Fields['textarea' . $n]['Name']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['textarea' . $n]['Width'] . $FieldsBuilder->Fields['textarea' . $n]['Unit']); ?> !important;height:<?php echo($FieldsBuilder->Fields['textarea' . $n]['Height'] . 'px'); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;"
			><?php echo($FieldsBuilder->Fields['textarea' . $n]['Name']); ?></textarea>
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 5; ++$n) {
		if (!isset($FieldsBuilder->Fields['checkbox' . $n]) || !$FieldsBuilder->Fields['checkbox' . $n]['Display']) continue; ?>
		<div>
			<div class="foxcheckbox" style="float:left;margin-right:10px;padding:0;">
				<input type="checkbox" value="<?php echo(JText::_('JYES')) ?>"			
				name="<?php echo($FieldsBuilder->Fields['checkbox' . $n]['PostName']); ?>">
			</div>
			<?php echo($FieldsBuilder->Fields['checkbox' . $n]['Name']); ?>
		</div>
	<?php } ?>


	<div>
<?php /*
Rather then removing this credits, you may find more useful to gain a permanent backlink
submitting your site at http://www.fox.ra.it/fox-contact-form-showcase.html ;)
*/?>
		<span class="foxpowered"><a href="http://www.fox.ra.it/joomla-extensions/fox-contact-form.html" title="Joomla 1.6 contact form">fox contact</a></span>
		<button class="foxbutton" type="submit"><?php echo(JText::_('JSUBMIT')); ?></button>
<?php /*
Invece di rimuovere questi crediti, potresti trovare piu' utile guadagnare un backlink permanente
segnalando il tuo sito su http://www.fox.ra.it/fox-contact-form-showcase.html ;)
*/?>
	</div>
</form>

<?php
// Debug
$application = &JFactory::getApplication();
if ($application->getCfg("debug")) echo($FieldsBuilder->Dump());
?>

