<?php
/**
 * @package jDownloads
 * @version 2.5  
 * @copyright (C) 2007 - 2013 - Arno Betz - www.jdownloads.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * 
 * jDownloads is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * View class for a list of downloads
 */
class jdownloadsViewDownloads extends JViewLegacy
{
    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;

	/**
	 * Display the view
     * @return    mixed    False on error, null otherwise.
	 */
	public function display($tpl = null)
	{
        
        global $jlistConfig;
        
        $app    = JFactory::getApplication();
        $user    = JFactory::getUser();

        // Initialise variables
        $state        = $this->get('State');
        $items        = $this->get('Items');
        $pagination   = $this->get('Pagination');
        
        $this->ipad_user = false;
        
        // we must get all 'allowed' category IDs 
        $this->authorised_cats = JDHelper::getAuthorisedJDCategories('core.create', $user);
        $authorised = $user->authorise('core.create', 'com_jdownloads') || (count($this->authorised_cats));
        if ($authorised === true) {
            // It exist a global permissions or at minimum a single category where the user has the permission to 'create'.
            $this->view_upload_button = true;                
        } else {
            $this->view_upload_button = false;
        }         
        
        // check whether we have an ipad/iphone user for flowplayer aso...
        if ((bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')){        
            $this->ipad_user = true;
        }        

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        if ($items === false) {
            return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));

        }

        // add all needed cripts and css files
        $document = JFactory::getDocument();
        $document->addScript(JURI::base().'components/com_jdownloads/assets/js/jdownloads.js');
        $document->addScript(JURI::base().'components/com_jdownloads/assets/rating/js/ajaxvote.js');
        
        // loadscript for flowplayer
        if ($jlistConfig['flowplayer.use']){
            $document->addScript(JURI::base().'components/com_jdownloads/assets/flowplayer/flowplayer-3.2.12.min.js');
            // load also the ipad plugin when required
             if ($this->ipad_user){
                $document->addScript(JURI::base().'components/com_jdownloads/assets/flowplayer/flowplayer.ipad-3.2.12.min.js');
            }
        }       
        
        if ($jlistConfig['use.lightbox.function']){
            $document->addScript(JURI::base().'components/com_jdownloads/assets/lightbox/lightbox.js');
            $document->addStyleSheet( JURI::base()."components/com_jdownloads/assets/lightbox/lightbox.css", 'text/css', null, array() );
        }
            
        $document->addScriptDeclaration('var live_site = "'.JURI::base().'";');
        $document->addScriptDeclaration('function openWindow (url) {
                fenster = window.open(url, "_blank", "width=550, height=480, STATUS=YES, DIRECTORIES=NO, MENUBAR=NO, SCROLLBARS=YES, RESIZABLE=NO");
                fenster.focus();
                }');

        if ($jlistConfig['use.css.buttons.instead.icons']){
           $document->addStyleSheet( JURI::base()."components/com_jdownloads/assets/css/jdownloads_buttons.css", "text/css", null, array() ); 
        }
        $document->addStyleSheet( JURI::base()."components/com_jdownloads/assets/css/jdownloads_fe.css", "text/css", null, array() );
        $document->addStyleSheet( JURI::base()."components/com_jdownloads/assets/rating/css/ajaxvote.css", "text/css", null, array() );         

        $custom_css_path = JPATH_ROOT.'/components/com_jdownloads/assets/css/jdownloads_custom.css';
        if (JFile::exists($custom_css_path)){
            $document->addStyleSheet( JURI::base()."components/com_jdownloads/assets/css/jdownloads_custom.css", 'text/css', null, array() );                
        }   
        
        $this->jd_image_path = JPATH_ROOT  . '/images/jdownloads';        
        
        $params = &$state->params;
                
        // Compute the download slugs and prepare introtext (runs content plugins).
        for ($i = 0, $n = count($items); $i < $n; $i++)
        {
            $item = &$items[$i];
            $item->slug = $item->file_alias ? ($item->file_id . ':' . $item->file_alias) : $item->file_id;

            // No link for ROOT category
            if ($item->parent_alias == 'root') {
                $item->parent_slug = null;
            }

            $item->event = new stdClass();

            $item->text = ""; 
            $dispatcher = JDispatcher::getInstance();

            JPluginHelper::importPlugin('content');
            $results = $dispatcher->trigger('onContentPrepare', array ('com_jdownloads.downloads', &$item, &$this->params, 0));

            $results = $dispatcher->trigger('onContentAfterTitle', array('com_jdownloads.downloads', &$item, &$item->params, 0));
            $item->event->afterDisplayTitle = trim(implode("\n", $results));

            $results = $dispatcher->trigger('onContentBeforeDisplay', array('com_jdownloads.downloads', &$item, &$item->params, 0));
            $item->event->beforeDisplayContent = trim(implode("\n", $results));

            $results = $dispatcher->trigger('onContentAfterDisplay', array('com_jdownloads.downloads', &$item, &$item->params, 0));
            $item->event->afterDisplayContent = trim(implode("\n", $results));
        }        
        
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

        $this->maxLevelcat = $params->get('maxLevelcat', -1);
        $this->assignRef('state', $state);        
        $this->assignRef('params',        $params);
        $this->assignRef('items',        $items);
        $this->assignRef('pagination', $pagination);

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus    = $app->getMenu();
        $title    = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_JDOWNLOADS_DOWNLOADS'));
        }        
        
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
}
