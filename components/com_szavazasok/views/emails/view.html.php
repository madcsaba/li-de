<?php
/**
* @version		$Id: default_viewlist.php 118 2012-10-02 08:52:27Z michel $
* @package		Szavazasok
* @subpackage 	Views
* @copyright	Copyright (C) 2014, . All rights reserved.
* @license #
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class SzavazasokViewEmails extends JViewLegacy {
	public function display($tpl = null)	{
		$app = &JFactory::getApplication('site');
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$user 		= &JFactory::getUser();
		$params		= $app ->getParams();				
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		if (is_object( $menu )) {
			$menu_params = $menus->getParams($menu->id) ;
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title', JText::_('SZAVAZASOK_VITA'));
			}
		}		
		$items = $this->get( 'Items' );
		$this->assignRef( 'items', $items);
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
?>