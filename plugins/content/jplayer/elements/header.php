<?php
/*
 * JPlayer for Joomla! 1.6
 * Author: MaxSVK
 * Version: 1.6.2
 * Last Update: 2011-05-29
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldHeader extends JFormField
{
	public $type = 'header';

	protected function getInput()
	{}

	public function getLabel()
	{
		// Initialize variables.
		$label = '';
		$replace = '';

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTip' : '';
		$class = $this->required == true ? $class.' required' : $class;

		// Add replace checkbox
		$replace = '<input type="checkbox" name="update['.$this->name.']" value="1" />';

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="'.$class.'"';

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			$label .= ' title="'.htmlspecialchars(trim(JText::_($text), ':').'::' . JText::_($this->description), ENT_COMPAT, 'UTF-8').'"';
		}

		// Add the label text and closing tag.
		$label .= ' style="font-weight:bold; color:#ffffff; padding:4px; background:#0B55C4;">'.JText::_($text).'</label>';

		return $label;
	}

}
?>
