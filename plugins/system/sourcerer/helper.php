<?php
/**
 * Plugin Helper File
 *
 * @package     Sourcerer
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
* Plugin that replaces Sourcerer code with its HTML / CSS / JavaScript / PHP equivalent
*/
class plgSystemSourcererHelper
{
	function __construct( &$params )
	{
		// Set plugin parameters
		$this->src_params = new stdClass();
		$this->src_params->syntax_word =		$params->syntax_word;
		$this->src_params->syntax_start =		'{'.$this->src_params->syntax_word.'}';
		$this->src_params->syntax_start_0 =		'{'.$this->src_params->syntax_word.' 0}';
		$this->src_params->syntax_end =			'{/'.$this->src_params->syntax_word.'}';

		// Matches the start and end tags with everything in between
		// Also matches any surrounding breaks and paragraph tags, to prevent unwanted empty lines in output.
		$break_tags_start =			'(<p(?: [^>]*)?>\s*)?(?:<span [^>]*>\s*)*';
		$break_tags_end =			'(?:\s*</span>)*(\s*</p>)?';
		$this->src_params->regex =	'#('.$break_tags_start.'('.preg_quote( $this->src_params->syntax_start, '#' ).'|'.preg_quote( $this->src_params->syntax_start_0, '#' ).')(.*?)'.preg_quote( $this->src_params->syntax_end, '#' ).$break_tags_end.')#s';

		// Escape any regex characters!
		$this->src_params->tags_syntax =	array ( array( '<', '>' ), array( '\[\[', '\]\]' ) );
		$this->src_params->splitter =		'<!-- START: SRC_SPLIT -->';

		$this->src_params->debug_php =		$params->debug_php;
		$this->src_params->debug_php_article = $this->src_params->debug_php;
		$this->src_params->user_is_admin =	0;
		$user = JFactory::getUser();
		if ( $user->usertype == 'Super Administrator' || $user->usertype == 'Administrator' ) {
			$this->src_params->user_is_admin = 1;
		}

		$this->src_params->areas = array();
		$this->src_params->areas['default'] = array();
		$this->src_params->areas['default']['enable_css'] =				$params->enable_css;
		$this->src_params->areas['default']['enable_js'] =				$params->enable_js;
		$this->src_params->areas['default']['enable_php'] =				$params->enable_php;
		$this->src_params->areas['default']['forbidden_php'] =			$params->forbidden_php;
		$this->src_params->areas['default']['forbidden_tags'] =			$params->forbidden_tags;

		$this->src_params->areas['articles'] = $this->src_params->areas['default'];
		$this->src_params->areas['articles']['enable'] =				$params->articles_enable;
		$this->src_params->areas['articles']['enable_css'] =			$params->articles_enable_css;
		$this->src_params->areas['articles']['enable_js'] =				$params->articles_enable_js;
		$this->src_params->areas['articles']['enable_php'] =			$params->articles_enable_php;
		$this->src_params->areas['articles']['forbidden_php'] =			$this->src_params->areas['default']['forbidden_php'].','.$params->articles_forbidden_php;
		$this->src_params->areas['articles']['forbidden_tags'] =		$this->src_params->areas['default']['forbidden_tags'].','.$params->articles_forbidden_tags;
		$this->src_params->areas['articles']['security'] =				(array) $params->articles_security_level;
		$this->src_params->areas['articles']['security_css'] =			$params->articles_security_level_default_css ? (array) $params->articles_security_level : (array) $params->articles_security_level_css;
		$this->src_params->areas['articles']['security_js'] =			$params->articles_security_level_default_js ? (array) $params->articles_security_level : (array) $params->articles_security_level_js;
		$this->src_params->areas['articles']['security_php'] =			$params->articles_security_level_default_php ? (array) $params->articles_security_level : (array) $params->articles_security_level_php;

		$this->src_params->areas['components'] = $this->src_params->areas['default'];
		$this->src_params->areas['components']['enable'] =				$params->components_enable;
		$this->src_params->areas['components']['components'] =			$params->components;
		if ( !is_array( $this->src_params->areas['components']['components'] ) ) {
			$this->src_params->areas['components']['components'] = explode( ',', $this->src_params->areas['components']['components'] );
		}
		$this->src_params->areas['components']['enable_css'] =			$params->components_enable_css;
		$this->src_params->areas['components']['enable_js'] =			$params->components_enable_js;
		$this->src_params->areas['components']['enable_php'] =			$params->components_enable_php;
		$this->src_params->areas['components']['forbidden_php'] =		$this->src_params->areas['default']['forbidden_php'].','.$params->components_forbidden_php;
		$this->src_params->areas['components']['forbidden_tags'] =		$this->src_params->areas['default']['forbidden_tags'].','.$params->components_forbidden_tags;

		$this->src_params->areas['other'] = $this->src_params->areas['default'];
		$this->src_params->areas['other']['enable'] =					$params->other_enable;
		$this->src_params->areas['other']['enable_css'] =				$params->other_enable_css;
		$this->src_params->areas['other']['enable_js'] =				$params->other_enable_js;
		$this->src_params->areas['other']['enable_php'] =				$params->other_enable_php;
		$this->src_params->areas['other']['forbidden_php'] =			$this->src_params->areas['default']['forbidden_php'].','.$params->other_forbidden_php;
		$this->src_params->areas['other']['forbidden_tags'] =			$this->src_params->areas['default']['forbidden_tags'].','.$params->other_forbidden_tags;

		foreach ( $this->src_params->areas as $areaname => $area ) {
			if ( $area['enable_css'] == -1 )	$this->src_params->areas[$areaname]['enable_css'] =		$this->src_params->areas['default']['enable_css'];
			if ( $area['enable_js'] == -1 )		$this->src_params->areas[$areaname]['enable_js'] =		$this->src_params->areas['default']['enable_js'];
			if ( $area['enable_php'] == -1 )	$this->src_params->areas[$areaname]['enable_php'] =		$this->src_params->areas['default']['enable_php'];
		}

		$this->src_params->currentarea = 'default';
	}

////////////////////////////////////////////////////////////////////
// ARTICLES
////////////////////////////////////////////////////////////////////

	function replaceInArticles ( &$article, $params = '' )
	{
		if ( $params && $params->get( 'nn_search' ) ) {
			$this->src_params->debug_php_article = 0;
		}
		if ( isset( $article->created_by ) ) {
			$user = &JFactory::getUser( $article->created_by );
			$groups = $user->getAuthorisedGroups();
			array_unshift( $groups, -1 );

			// Set if security is passed
			// passed = creator is equal or higher than security group level
			$pass = array_intersect( $this->src_params->areas['articles']['security'], $groups );
			$this->src_params->areas['articles']['security_pass'] = ( !empty( $pass ) );
			$pass = array_intersect( $this->src_params->areas['articles']['security_css'], $groups );
			$this->src_params->areas['articles']['security_pass_css'] = ( !empty( $pass ) );
			$pass = array_intersect( $this->src_params->areas['articles']['security_js'], $groups );
			$this->src_params->areas['articles']['security_pass_js'] = ( !empty( $pass ) );
			$pass = array_intersect( $this->src_params->areas['articles']['security_php'], $groups );
			$this->src_params->areas['articles']['security_pass_php'] = ( !empty( $pass ) );
		}

		if ( isset( $article->text ) ) {
			$this->replace( $article->text, 'articles', $article );
		}
		if ( isset( $article->description ) ) {
			$this->replace( $article->description, 'articles', $article );
		}
		if ( isset( $article->title ) ) {
			$this->replace( $article->title, 'articles', $article );
		}
		if ( isset( $article->author ) ) {
			if ( isset( $article->author->name ) ) {
				$this->replace( $article->author->name, 'articles', $article );
			} else if ( is_string( $article->author ) ) {
				$this->replace( $article->author, 'articles', $article );
			}
		}
	}

////////////////////////////////////////////////////////////////////
// COMPONENTS
////////////////////////////////////////////////////////////////////

	function replaceInComponents()
	{
		$document	=& JFactory::getDocument();
		$docType = $document->getType();

		// FEED
		if ( ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) && isset( $document->items ) ) {
			for ( $i = 0; $i < count( $document->items ); $i++ ) {
				$this->replaceInArticles( $document->items[$i] );
			}
		}

		if ( isset( $document->_buffer ) ) {
			$this->tagArea( $document->_buffer, 'SRC', 'component' );
		}

		// PDF
		if ( $docType == 'pdf' ) {
			// Still to do for Joomla 1.6+
		}
	}

////////////////////////////////////////////////////////////////////
// OTHER AREAS
////////////////////////////////////////////////////////////////////
	function replaceInOtherAreas()
	{
		$document =& JFactory::getDocument();
		$docType = $document->getType();

		// not in pdf's
		if ( $docType == 'pdf' ) { return; }

		$html = JResponse::getBody();

		$this->protect( $html );
		$this->replaceInTheRest( $html );
		$this->unprotect( $html );

		$this->cleanLeftoverJunk( $html );

		JResponse::setBody( $html );
	}

	function replaceInTheRest( &$str )
	{
		if ( $str == '' ) { return; }

		$document	=& JFactory::getDocument();
		$docType = $document->getType();

		// COMPONENT
		if ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) {
			$s = '#(<item[^>]*>)#s';
			$str = preg_replace( $s, '\1<!-- START: SRC_COMPONENT -->', $str );
			$str = str_replace( '</item>', '<!-- END: SRC_COMPONENT --></item>', $str );
		}
		if ( strpos( $str, '<!-- START: SRC_COMPONENT -->' ) === false ) {
			$this->tagArea( $str, 'SRC', 'component' );
		}

		if ( in_array( JRequest::getCmd( 'option' ), $this->src_params->areas['components']['components'] ) ) {
			// For all components that are selected, set the 'enable' to false
			$this->src_params->areas['components']['enable'] = $this->src_params->areas['components']['enable_css'] = $this->src_params->areas['components']['enable_js'] = $this->src_params->areas['components']['enable_php'] = 0;
		}

		$components = $this->getTagArea( $str, 'SRC', 'component' );
		foreach ( $components as $component ) {
			$this->replace( $component['1'], 'components', '' );
			$str = str_replace( $component['0'], $component['1'], $str );
		}

		// EVERYWHERE
		$this->replace( $str, 'other' );
	}

	function tagArea( &$str, $ext = 'EXT', $area = '' )
	{
		if ( $area ) {
			if ( is_array( $str ) ) {
				foreach ( $str as $key => $val ) {
					$this->tagArea( $val, $ext, $area );
					$str[ $key ] = $val;
				}
			} else if ( $str ) {
				$str = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->'.$str.'<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
				if ( $area == 'article_text' ) {
					$str = preg_replace( '#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->\1<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->', $str );
				}
			}
		}
	}

	function getTagArea( &$str, $ext = 'EXT', $area = '' )
	{
		$matches = array();
		if ( $str && $area ) {
			$start = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$end = '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$matches = explode( $start, $str );
			array_shift( $matches );
			foreach ( $matches as $i => $match ) {
				list( $text ) = explode( $end, $match, 2 );
				$matches[$i] = array(
					$start.$text.$end,
					$text
				);
			}
		}
		return $matches;
	}

	function replace( &$string, $area = 'articles', $article = '' )
	{
		$string_array = $this->stringToSplitArray( $string, $this->src_params->regex );
		$string_array_count = count( $string_array );
		if ( $string_array_count > 1 ) {
			for ( $i = 1; $i < $string_array_count-1; $i++ ) {
				if ( fmod( $i, 2 ) ) {
					$sub_string_array = preg_replace( $this->src_params->regex, implode( $this->src_params->splitter, array( '\2', '\3', '\4', '\5' ) ), $string_array[$i] );
					$sub_string_array = explode( $this->src_params->splitter, $sub_string_array );

					$string_array[$i] = $sub_string_array['2'];

					if ( $sub_string_array['1'] == $this->src_params->syntax_start ) {
						$this->cleanText( $string_array[$i] );
					}

					$this->replaceTags( $string_array[$i], $area, $article );

					// Restore leading/trailing paragraph tags if not both present
					if ( !( $sub_string_array['0'] && $sub_string_array['3'] ) ) {
						$string_array[$i] = $sub_string_array['0'].$string_array[$i].$sub_string_array['3'];
					}
				}
			}
		}
		$string = implode( '', $string_array );
	}

	function replaceTags( &$string, $area = 'articles', $article = '' )
	{
		$this->replaceTagsByType( $string, $area, 'php', $article );
		if ( strpos( $string, '<!-- SORCERER DEBUGGING -->' ) === false ) {
			$this->replaceTagsByType( $string, $area, 'all', '' );
			$this->replaceTagsByType( $string, $area, 'js', '' );
			$this->replaceTagsByType( $string, $area, 'css', '' );
		}
	}

	function replaceTagsByType( &$string, $area = 'articles', $type = 'all', $article = '' )
	{
		$type_ext = '_'.$type;
		if ( $type == 'all' ) {
			$type_ext = '';
		}
		$enable = isset( $this->src_params->areas[$area]['enable'.$type_ext] ) ? $this->src_params->areas[$area]['enable'.$type_ext] : 1;
		$security_pass = isset( $this->src_params->areas[$area]['security_pass'.$type_ext] ) ? $this->src_params->areas[$area]['security_pass'.$type_ext] : 1;

		switch ( $type ) {
			case 'php':
				$this->replaceTagsPHP( $string, $enable, $security_pass, $article );
				break;
			case 'js':
				$this->replaceTagsJS( $string, $enable, $security_pass );
				break;
			case 'css':
				$this->replaceTagsCSS( $string, $enable, $security_pass );
				break;
			default:
				$this->replaceTagsAll( $string, $enable, $security_pass );
				break;
		}
	}

	// Replace any html style tags by a comment tag if not permitted
	// Match:
	// <...>
	function replaceTagsAll( &$string, $enabled = 1, $security_pass = 1 )
	{
		if ( !$enabled ) {
			// replace source block content with HTML comment
			$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::_( 'SRC_OUTPUT_REMOVED_NOT_ENABLED' ).' -->';
		} else if ( !$security_pass ) {
			// replace source block content with HTML comment
			$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::_( 'SRC_OUTPUT_REMOVED_SECURITY' ).' -->';
		} else {
			$this->cleanTags( $string );

			$forbidden_tags_array = explode( ',', $this->src_params->areas[$this->src_params->currentarea]['forbidden_tags'] );
			$this->cleanArray( $forbidden_tags_array );
			// remove the comment tag syntax from the array - they cannot be disabled
			$forbidden_tags_array = array_diff( $forbidden_tags_array, array( '!--' ) );
			// reindex the array
			$forbidden_tags_array = array_merge( $forbidden_tags_array );

			$has_forbidden_tags = 0;
			foreach ( $forbidden_tags_array as $forbidden_tag ) {
				if ( !( strpos( $string, '<'.$forbidden_tag ) == false ) ) {
					$has_forbidden_tags = 1;
					break;
				}
			}

			if ( $has_forbidden_tags ) {
				// double tags
				$tag_regex = '#<\s*([a-z\!][^>\s]*?)(?:\s+.*?)?>.*?</\1>#si';
				if ( preg_match_all( $tag_regex, $string, $matches, PREG_SET_ORDER ) > 0 ) {
					foreach ( $matches as $match ) {
						if ( in_array( $match['1'], $forbidden_tags_array ) ) {
							$tag = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_TAG_REMOVED_FORBIDDEN', $match['1'] ).' -->';
							$string = str_replace( $match['0'], $tag, $string );
						}
					}
				}
				// single tags
				$tag_regex = '#<\s*([a-z\!][^>\s]*?)(?:\s+.*?)?>#si';
				if ( preg_match_all( $tag_regex, $string, $matches, PREG_SET_ORDER ) > 0 ) {
					foreach ( $matches as $match ) {
						if ( in_array( $match['1'], $forbidden_tags_array ) ) {
							$tag = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_TAG_REMOVED_FORBIDDEN', $match['1'] ).' -->';
							$string = str_replace( $match['0'], $tag, $string );
						}
					}
				}
			}
		}
	}
	// Replace the PHP tags with the evaluated PHP scripts
	// Or replace by a comment tag the PHP tags if not permitted
	function replaceTagsPHP( &$src_string, $src_enabled = 1, $src_security_pass = 1, $article = '' )
	{
		if ( ( strpos( $src_string, '<?' ) === false ) && ( strpos( $src_string, '[[?' ) === false ) ) { return; }

		global $src_vars;

		$document	=& JFactory::getDocument();
		$docType = $document->getType();

		// Match ( read {} as <> ):
		// {?php ... ?}
		// {? ... ?}
		$src_string_array = $this->stringToSplitArray( $src_string, '-start-'.'\?(?:php)?[\s<](.*?)\?'.'-end-', 1 );
		$src_string_array_count = count( $src_string_array );

		if ( $src_string_array_count > 1 ) {
			if ( !$src_enabled ) {
				// replace source block content with HTML comment
				$src_string_array = array();
				$src_string_array['0'] = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_CODE_REMOVED_NOT_ALLOWED', JText::_( 'SRC_PHP' ), JText::_( 'SRC_PHP' ) ).' -->';
			} else if ( !$src_security_pass ) {
				// replace source block content with HTML comment
				$src_string_array = array();
				$src_string_array['0'] = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_CODE_REMOVED_SECURUITY', JText::_( 'SRC_PHP' ), JText::_( 'SRC_PHP' ) ).' -->';
			} else {
				// if source block content has more than 1 php block, combine them
				if ( $src_string_array_count > 3 ) {
					for ( $i = 2; $i < $src_string_array_count-1; $i++ ) {
						if ( fmod( $i, 2 ) == 0 ) {
							$src_string_array['1'] .= "<!-- SRC_SEMICOLON --> ?>".trim( $src_string_array[$i] )."<?php ";
						} else {
							$src_string_array['1'] .= $src_string_array[$i];
						}
						unset( $src_string_array[$i] );
					}
				}

				// fixes problem with _REQUEST being stripped if there is an error in the code
				$src_backup_REQUEST = $_REQUEST;
				$src_backup_vars = array_keys( get_defined_vars() );

				$src_script = trim( $src_string_array['1'] ).'<!-- SRC_SEMICOLON -->';
				$src_script = preg_replace( '#(;\s*)?<\!-- SRC_SEMICOLON -->#s', ';', $src_script );

				$src_errorline = 0;
				$src_php_succes = 0;

				$src_forbidden_php_array = explode( ',', $this->src_params->areas[$this->src_params->currentarea]['forbidden_php'] );
				$this->cleanArray( $src_forbidden_php_array );
				$src_forbidden_php_regex = '#('.implode( '|', $src_forbidden_php_array ).')\s*\(#si';

				if ( preg_match_all( $src_forbidden_php_regex, $src_script, $src_functions, PREG_SET_ORDER ) > 0 ) {
					$src_functionsArray = array();
					foreach ( $src_functions as $src_function ) $src_functionsArray[] = $src_function['1'].')';
					$src_string_array['1'] = JText::_( 'SRC_PHP_FORBIDDEN' ).':<br /><span style="font-family: monospace;"><ul style="margin:0px;"><li>'.implode( '</li><li>', $src_functionsArray ).'</li></ul></span>';
					$src_comment = JText::_( 'SRC_PHP_CODE_REMOVED_FORBIDDEN' ).': ( '.implode( ', ', $src_functionsArray ).' )';
				} else {
					// evaluate the script
					ob_start();
						if ( is_array( $src_vars ) ) {
							foreach ( $src_vars as $src_key=>$src_value ) {
								${$src_key} = $src_value;
							}
						}
						if ( !isset( $mainframe ) && !( strpos( $src_script, '$mainframe' ) === false ) ) {
							global $mainframe;
						}
						if ( !isset( $Itemid ) && !( strpos( $src_script, '$Itemid' ) === false ) ) {
							global $Itemid;
						}
						if ( !isset( $user ) && !( strpos( $src_script, '$user' ) === false ) ) {
							$user =& JFactory::getUser();
						}
						if ( !isset( $database ) && !( strpos( $src_script, '$database' ) === false ) ) {
							$database =& JFactory::getDBO();
						}
						$src_script .= "\n".'$src_php_succes = 1;';
						eval( $src_script );
						$src_string_array['1'] = ob_get_contents();
					ob_end_clean();
					if ( !( strpos( $src_string_array['1'], "eval()'d code" ) === false ) ) {
						foreach ( $src_backup_REQUEST as $src_key=>$src_value ) {
							$_REQUEST[$src_key] = $src_value;
						}
						$src_php_succes = 0;
						preg_match( '#on line <b>([0-9]+)#si', $src_string_array['1'], $src_errormatch );
						if ( count( $src_errormatch ) ) $src_errorline = $src_errormatch['1'];
					}

					$src_comment = JText::_( 'SRC_PHP_CODE_REMOVED_ERRORS' );
				}

				if ( !$src_php_succes ) {
					if ( $docType == 'html' ) {
						if ( ( $this->src_params->debug_php && !$article ) || ( $this->src_params->debug_php_article && $article ) ) {
							if ( $this->src_params->user_is_admin ) {
								$this->createDebuggingOutput( $src_string_array['1'], $src_script, $src_errorline );
							} else {
								$src_string_array['1'] = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.$src_comment.' '.JText::_( 'SRC_LOGIN_TO_SHOW_PHP_DEBUGGING' ).' -->';
							}
						} else {
							$src_string_array['1'] = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.$src_comment.' -->';
						}
					} else {
						$src_string_array['1'] = '';
					}
				} else {
					$src_new_vars = get_defined_vars();
					$src_diff_vars = array_diff( array_keys( $src_new_vars ), $src_backup_vars );
					foreach ( $src_diff_vars as $src_diff_key ) {
						if ( substr( $src_diff_key, 0, 5 ) != '_src_' && substr( $src_diff_key, 0, 4 ) != 'src_' ) {
							$src_vars[$src_diff_key] = $src_new_vars[$src_diff_key];
						}
					}
				}
			}
		}
		$src_string = implode( '', $src_string_array );
	}

	// Replace the JavaScript tags by a comment tag if not permitted
	function replaceTagsJS( &$string, $enabled = 1, $security_pass = 1 )
	{
		// quick check to see if i is necessary to do anything
		if ( ( strpos( $string, 'script' ) === false ) ) { return; }

		// Match:
		// <script ...>...</script>
		$tag_regex =
			'(-start-'.'\s*script\s[^'.'-end-'.']*?[^/]\s*'.'-end-'
			.'(.*?)'
			.'-start-'.'\s*\/\s*script\s*'.'-end-)'
			;
		$string_array = $this->stringToSplitArray( $string, $tag_regex, 1 );
		$string_array_count = count( $string_array );

		// Match:
		// <script ...>
		// single script tags are not xhtml compliant and should not occur, but just incase they do...
		if ( $string_array_count == 1 ) {
			$tag_regex = '(-start-'.'\s*script\s.*?'.'-end-)';
			$string_array = $this->stringToSplitArray( $string, $tag_regex, 1 );
			$string_array_count = count( $string_array );
		}
		if ( $string_array_count > 1 ) {
			if ( !$enabled ) {
				// replace source block content with HTML comment
				$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_OUTPUT_REMOVED_NOT_ALLOWED', array( JText::_( 'SRC_JAVASCRIPT' ) ), array( JText::_( 'SRC_JAVASCRIPT' ) ) ).' -->';
			} else if ( !$security_pass ) {
				// replace source block content with HTML comment
				$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_OUTPUT_REMOVED_SECURUITY', array( JText::_( 'SRC_JAVASCRIPT' ) ), array( JText::_( 'SRC_JAVASCRIPT' ) ) ).' -->';
			}
		}
	}

	// Replace the CSS tags by a comment tag if not permitted
	function replaceTagsCSS( &$string, $enabled = 1, $security_pass = 1 )
	{
		// quick check to see if i is necessary to do anything
		if ( ( strpos( $string, 'style' ) === false ) && ( strpos( $string, 'link' ) === false ) ) { return; }

		// Match:
		// <script ...>...</script>
		$tag_regex =
			'(-start-'.'\s*style\s[^'.'-end-'.']*?[^/]\s*'.'-end-'
			.'(.*?)'
			.'-start-'.'\s*\/\s*style\s*'.'-end-)'
			;
		$string_array = $this->stringToSplitArray( $string, $tag_regex, 1 );
		$string_array_count = count( $string_array );

		// Match:
		// <script ...>
		// single script tags are not xhtml compliant and should not occur, but just incase they do...
		if ( $string_array_count == 1 ) {
			$tag_regex = '(-start-'.'\s*link\s[^'.'-end-'.']*?(rel="stylesheet"|type="text/css").*?'.'-end-)';
			$string_array = $this->stringToSplitArray( $string, $tag_regex, 1 );
			$string_array_count = count( $string_array );
		}

		if ( $string_array_count > 1 ) {
			if ( !$enabled ) {
				// replace source block content with HTML comment
				$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_OUTPUT_REMOVED_NOT_ALLOWED', array( JText::_( 'SRC_CSS' ) ), array( JText::_( 'SRC_CSS' ) ) ).' -->';
			} else if ( !$security_pass ) {
				// replace source block content with HTML comment
				$string = '<!-- '.JText::_( 'SRC_COMMENT' ).': '.JText::sprintf( 'SRC_OUTPUT_REMOVED_SECURUITY', array( JText::_( 'SRC_CSS' ) ), array( JText::_( 'SRC_CSS' ) ) ).' -->';
			}
		}
	}

	function stringToSplitArray( $string, $search, $tags = 0 )
	{
		if ( $tags) {
			foreach ( $this->src_params->tags_syntax as $src_tag_syntax ) {
				$tag_search = str_replace( '-start-', $src_tag_syntax['0'], $search );
				$tag_search = str_replace( '-end-', $src_tag_syntax['1'], $tag_search );
				$tag_search = '#'.$tag_search.'#si';
				$string = preg_replace( $tag_search, $this->src_params->splitter.'\1'.$this->src_params->splitter, $string );
			}
		} else {
			$string = preg_replace( $search, $this->src_params->splitter.'\1'.$this->src_params->splitter, $string );
		}
		return explode( $this->src_params->splitter, $string );
	}

	function cleanTags( &$string )
	{
		foreach ( $this->src_params->tags_syntax as $src_tag_syntax ) {
			$tag_regex = '#'.$src_tag_syntax['0'].'\s*(\/?\s*[a-z\!][^'.$src_tag_syntax['1'].']*?(?:\s+.*?)?)'.$src_tag_syntax['1'].'#si';
			$string = preg_replace( $tag_regex, '<\1\2>', $string );
		}
	}

	function cleanArray( &$array )
	{
		// trim all values
		$array = array_map( 'trim', $array );
		// remove dublicates
		$array = array_unique( $array );
		// remove empty (or false) values
		$array = array_filter( $array );
	}

	function cleanText( &$string )
	{
		// replace chr style enters with normal enters
		$string = str_replace( array( chr(194).chr(160), '&#160;' ), ' ', $string );

		// replace linbreak tags with normal linebreaks (paragraphs, enters, etc).
		$enter_tags = array( 'p', 'br' );
		$regex = '#</?(('.implode( ')|(', $enter_tags ).'))+[^>]*?>\n?#si';
		$string = preg_replace( $regex, " \n", $string );

		// replace indent characters with spaces
		$string = preg_replace( '#<'.'img [^>]*/sourcerer/images/tab\.png[^>]*>#si', '    ', $string );

		// strip all other tags
		$regex = '#<(/?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?)>#si';
		$string = preg_replace( $regex, "", $string );

		// reset htmlentities
		$string = html_entity_decoder( $string );

		// convert protected html entities &_...; -> &...;
		$string = preg_replace( '#&_([a-z0-9\#]+?);#i', '&\1;', $string );
	}

	function createDebuggingOutput( &$string, $script, $errorlinenr )
	{
		$script = str_replace( "\n".'$src_php_succes = 1;', '', $script );
		$script = htmlentities( $script );
		$scriptLines = explode( "\n", $script );
		$count = count( $scriptLines );
		if ( $errorlinenr > $count ) {
			$string = str_replace( 'on line <b>'.$errorlinenr.'</b>' , 'on line <b>'.$count.'</b>', $string );
			$errorlinenr = $count;
		}
		$script = $this->createNumberedTable( $scriptLines, $errorlinenr );
		$this->trimBr( $string );
		$id = rand( 1000, 9999 );
		$string =
			"\n".'<!-- SORCERER DEBUGGING -->'
			."\n".'<div style="clear: both;border: 3px solid #CC3333;background-color: #FFFFFF;">'
				."\n\t".'<div id="sourcerer_debugging_'.$id.'_collapsed">'
					."\n\t\t".'<div style="float:right;padding: 2px 5px;color:#999999;cursor:pointer;cursor:hand;" onclick="document.getElementById(\'sourcerer_debugging_'.$id.'_expanded\').style.display=\'block\';document.getElementById(\'sourcerer_debugging_'.$id.'_collapsed\').style.display=\'none\';">'.JText::_( 'SRC_SHOW' ).'</div>'
					."\n\t\t".'<div style="float:left;font-size:1.2em;padding: 2px 5px;"><strong>'.JText::_( 'SRC_PHP_DEBUGGING' ).'</strong></div>'
				."\n\t".'</div>'
				."\n\t".'<div style="clear: both;"></div>'
				."\n\t".'<div id="sourcerer_debugging_'.$id.'_expanded" style="display:none;">'
					."\n\t\t".'<div style="float:right;padding: 2px 5px;color:#999999;cursor:pointer;cursor:hand;" onclick="document.getElementById(\'sourcerer_debugging_'.$id.'_expanded\').style.display=\'none\';document.getElementById(\'sourcerer_debugging_'.$id.'_collapsed\').style.display=\'block\';">'.JText::_( 'SRC_HIDE' ).'</div>'
					."\n\t\t".'<div style="float:left;font-size:1.2em;padding: 2px 5px;"><strong>'.JText::_( 'SRC_PHP_DEBUGGING' ).'</strong></div>'
					."\n\t\t".'<div style="background-color: #339933;color: #FFFFFF;padding: 2px 5px;"><strong>'.JText::_( 'SRC_PHP_CODE' ).'</strong></div>'
					."\n\t\t".'<div style="clear:both;max-height:200px;overflow:auto;position:relative;">'.$script.'</div>'
					."\n\t\t".'<div style="background-color: #CC3333;color: #FFFFFF;padding: 2px 5px;"><strong>'.JText::_( 'SRC_PHP_ERROR' ).'</strong></div>'
					."\n\t\t".'<div style="background-color: #FFDDDD;padding: 2px 5px;">'.$string.'</div>'
					."\n\t\t".'<div style="font-size:0.8em;font-style:italic;padding: 2px 5px;">'
						."\n\t\t\t".'<div style="float:right;"><a href="http://www.nonumber.nl/sourcerer" target="_blank">'.JText::_( 'SRC_MORE_ABOUT' ).'</a></div>'
						."\n\t\t\t".'<div style="float:left;">'.JText::_( 'SRC_TO_HIDE_THIS_ERROR_TURN_OFF_PHP_DEBUGGING' ).'</div>'
					."\n\t\t".'</div>'
				."\n\t".'</div>'
				."\n\t".'<div style="clear: both;"></div>'
			."\n".'</div>';
	}

	function createNumberedTable( &$scriptLines, $errorlinenr )
	{
		$output = '';
		foreach ( $scriptLines as $linenr => $scriptLine ) {
			$linenr++;
			$scriptLine = str_replace( '    ', '&nbsp;&nbsp;&nbsp;&nbsp;', $scriptLine );
			$bgcolor = '#FFFFFF';
			if ( fmod ( $linenr, 2 ) == 1 ) {
				$bgcolor = '#F7F7F7';
			}
			if ( $errorlinenr == $linenr ) {
				$bgcolor = '#FFDDDD';
			}
			$output .=
				"\n\t\t".'<div style="background-color: '.$bgcolor.';position:relative;">'
					."\n\t\t\t".'<div style="font-family:monospace;color:#999999;text-align:right;padding: 1px 5px;width: 24px;position: absolute;left:0;">'.$linenr.'</div>'
					."\n\t\t\t".'<div style="margin-left: 34px;border-left: 1px solid #DDDDDD;font-family:monospace;padding: 1px 5px;">'.$scriptLine.'</div>'
				."\n\t\t".'</div>'
				;
		}
		return $output;
	}

	function trimBr( &$string )
	{
		while ( substr( $string, 0, 6 ) == '<br />' ) {
			$string = trim( substr( $string, 6, strlen( $string ) ) );
		}
		while ( substr( $string, strlen( $string )-6, 6 ) == '<br />' ) {
			$string = trim( substr( $string, 0, strlen( $string )-6 ) );
		}
		$string = trim( $string );
	}

	/*
	 * Protect input and text area's
	 */
	function protect( &$string )
	{
		if (	in_array( JRequest::getCmd( 'task' ), array( 'edit' ) )
			||	in_array( JRequest::getCmd( 'view' ), array( 'edit', 'form' ) )
			||	in_array( JRequest::getCmd( 'layout' ), array( 'edit', 'form', 'write' ) )
			||	in_array( JRequest::getCmd( 'option' ), array( 'com_contentsubmit', 'com_cckjseblod' ) )
		) {
			// Protect complete adminForm (to prevent articles from being created when editing articles and such)
			$unprotected = array( $this->src_params->syntax_start, $this->src_params->syntax_start_0, $this->src_params->syntax_end );
			$protected = array( $this->protectStr( $unprotected['0'] ), $this->protectStr( $unprotected['1'] ), $this->protectStr( $unprotected['2'] ) );
			$string = preg_replace( '#(<'.'form [^>]*(id|name)="adminForm")#si', '<!-- TMP_START_EDITOR -->\1', $string );
			$string = explode( '<!-- TMP_START_EDITOR -->', $string );
			foreach ( $string as $i => $str ) {
				if ( !empty( $str ) != '' && fmod( $i, 2 ) ) {
					if (
							!( strpos( $str, $unprotected['0'] ) === false )
						|| 	!( strpos( $str, $unprotected['1'] ) === false )
						|| 	!( strpos( $str, $unprotected['2'] ) === false )
					) {
						$str = explode( '</form>', $str, 2 );
						$str['0'] = str_replace( $unprotected, $protected, $str['0'] );
						$string[$i] = implode( '</form>', $str );
					}
				}
			}
			$string = implode( '', $string );
		}
	}

	function unprotect( &$string )
	{
		$unprotected = array( $this->src_params->syntax_start, $this->src_params->syntax_start_0, $this->src_params->syntax_end );
		$protected = array( $this->protectStr( $unprotected['0'] ), $this->protectStr( $unprotected['1'] ), $this->protectStr( $unprotected['2'] ) );
		$string = str_replace( $protected, $unprotected, $string );
	}

	function protectStr( $string )
	{
		$string = base64_encode( $string );
		return $string;
	}

	function cleanLeftoverJunk( &$str )
	{
		$str = preg_replace( '#<\!-- (START|END): SRC_[^>]* -->#', '', $str );
	}
}

if ( !function_exists( 'html_entity_decoder' ) ) {
	function html_entity_decoder( $given_html, $quote_style = ENT_QUOTES, $charset = 'UTF-8' )
	{
		if ( is_array( $given_html ) ) {
			foreach( $given_html as $i => $html ) {
				$given_html[$i] = html_entity_decoder( $html );
			}
			return $given_html;
		}
		return html_entity_decode( $given_html, $quote_style, $charset );
	}
}