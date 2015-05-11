<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');

JFormHelper::loadFieldClass('list');


/**
 * Form Field class.
 */
class JFormFieldAmprojectworkflow extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Amprojectworkflow';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db		= &JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS value, title AS text');
		$query->from('#__ampm_workflow');
		$query->order('title DESC');

		// Get the options.
		$db->setQuery($query->__toString());

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}


		$options	= array_merge(
			parent::getOptions(),
			$options
		);

		return $options;
	}
}