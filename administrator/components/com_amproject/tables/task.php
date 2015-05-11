<?php
/**
* @version		$Id:task.php  1 2014-12-30 07:54:22Z FT $
* @package		Amproject
* @subpackage 	Tables
* @copyright	Copyright (C) 2014, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Jimtawl TableTask class
*
* @package		Amproject
* @subpackage	Tables
*/
class TableTask extends JTable
{
	
   /** @var int id- Primary Key  **/
   public $id = null;

   /** @var varchar title  **/
   public $title = null;

   /** @var text description  **/
   public $description = null;

   /** @var int state  **/
   public $state = null;

   /** @var int ordering  **/
   public $ordering = null;

   /** @var amprojectworkflow workflow_id  **/
   public $workflow_id = null;

   /** @var time start  **/
   public $start = null;

   /** @var time deadline  **/
   public $deadline = null;

   /** @var int priority  **/
   public $priority = null;

   /** @var int manager_id  **/
   public $manager_id = null;

   /** @var text process_desc  **/
   public $process_desc = null;

   /** @var text result_desc  **/
   public $result_desc = null;

   /** @var amprojecttaskshema taskshema_id  **/
   public $taskshema_id = null;

   /** @var int volume  **/
   public $volume = null;

   /** @var boolean volume  **/
   public $tracking = null;




	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	public function __construct(& $db) 
	{
		parent::__construct('#__ampm_task', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '')
	{ 
		
		return parent::bind($array, $ignore);		
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()
	{
		if ($this->id === 0) {
			//get next ordering

			
			$this->ordering = $this->getNextOrder( );

		}


		/** check for valid name */
		/**
		if (trim($this->title) == '') {
			$this->setError(JText::_('Your Task must contain a title.')); 
			return false;
		}
		**/		

		return true;
	}
}