<?php
/**
 * NoNumber! Elements Helper File: Assignments: Users
 *
 * @package     NoNumber! Elements
 * @version     2.9.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

/**
* Assignments: Users
*/
class NoNumberElementsAssignmentsUsers
{
	var $_version = '2.9.0';

	/**
	 * passUserGroupLevels
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUserGroupLevels( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();
		$groups = $user->getAuthorisedGroups();

		return $main->passSimple( $groups, $selection, $assignment );
	}

	/**
	 * passUsers
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUsers( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();

		return $main->passSimple( $user->get( 'id' ), $selection, $assignment );
	}
}