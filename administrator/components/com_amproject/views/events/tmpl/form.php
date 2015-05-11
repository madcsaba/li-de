<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Set toolbar items for the page
$edit		= JRequest::getVar('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_( 'Events' ).': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit) {
	JToolBarHelper::cancel();
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'cancel', 'Close' );
}
?>

<script language="javascript" type="text/javascript">


Joomla.submitbutton = function(task)
{
	if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}

</script>

	 	<form method="post" action="index.php" id="adminForm" name="adminForm">
	 	<div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-60  <?php endif; ?>span8 form-horizontal fltlft">
		  <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
							
				<?php echo $this->form->getLabel('tablename'); ?>
				
				<?php echo $this->form->getInput('tablename');  ?>
					
				<?php echo $this->form->getLabel('table_id'); ?>
				
				<?php echo $this->form->getInput('table_id');  ?>
					
				<?php echo $this->form->getLabel('volume'); ?>
				
				<?php echo $this->form->getInput('volume');  ?>
					
				<?php echo $this->form->getLabel('type'); ?>
				
				<?php echo $this->form->getInput('type');  ?>
					
				<?php echo $this->form->getLabel('user_id'); ?>
				
				<?php echo $this->form->getInput('user_id');  ?>
					
				<?php echo $this->form->getLabel('time'); ?>
				
				<?php echo $this->form->getInput('time');  ?>
					
				<?php echo $this->form->getLabel('newstate'); ?>
				<?php echo $this->form->getInput('newstate');  ?>
					
				<?php echo $this->form->getLabel('oldrec'); ?>
				<?php echo $this->form->getInput('oldrec');  ?>
					
				<?php echo $this->form->getLabel('newrec'); ?>
				<?php echo $this->form->getInput('newrec');  ?>
			
						
          </fieldset>                      
        </div>
        <div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-30  <?php endif; ?>span2 fltrgt">
			        

        </div>                   
		<input type="hidden" name="option" value="com_amproject" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="events" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>