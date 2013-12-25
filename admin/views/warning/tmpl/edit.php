<?php
/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_bts/assets/css/bts.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
	js('input:hidden.station_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('station_idhidden')){
			js('#jform_station_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_station_id").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'warning.cancel'){
            Joomla.submitform(task, document.getElementById('warning-form'));
        }
        else{
            
            if (task != 'warning.cancel' && document.formvalidator.isValid(document.id('warning-form'))) {
                
                Joomla.submitform(task, document.getElementById('warning-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_bts&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="warning-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('station_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('station_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->station_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="station_id" name="jform[station_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('warning_description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('warning_description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('device'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('device'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('level'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('level'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('warning_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('warning_time'); ?></div>
			</div>
				<input type="hidden" name="jform[maintenance_by]" value="<?php echo $this->item->maintenance_by; ?>" />
				<input type="hidden" name="jform[maintenance_time]" value="<?php echo $this->item->maintenance_time; ?>" />
				<input type="hidden" name="jform[approve_by]" value="<?php echo $this->item->approve_by; ?>" />
				<input type="hidden" name="jform[approve_time]" value="<?php echo $this->item->approve_time; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('maintenance_state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('maintenance_state'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('approve_state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('approve_state'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>