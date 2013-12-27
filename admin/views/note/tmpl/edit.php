<?php
/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
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
        if(task == 'note.cancel'){
            Joomla.submitform(task, document.getElementById('note-form'));
        }
        else{
            
            if (task != 'note.cancel' && document.formvalidator.isValid(document.id('note-form'))) {
                
                Joomla.submitform(task, document.getElementById('note-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_bts&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="note-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
			<div class="control-group">
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
				<div class="control-label"><?php echo $this->form->getLabel('note'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('note'); ?></div>
			</div>

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php echo $this->form->getInput('created_time'); ?>				<input type="hidden" name="jform[approved]" value="<?php echo $this->item->approved; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('approved_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('approved_by'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('approved_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('approved_time'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>