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
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'station.cancel'){
            Joomla.submitform(task, document.getElementById('station-form'));
        }
        else{
            
            if (task != 'station.cancel' && document.formvalidator.isValid(document.id('station-form'))) {
                
                Joomla.submitform(task, document.getElementById('station-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_bts&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="station-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('bts_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('bts_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('network'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('network'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('latitude'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('latitude'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('longitude'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('longitude'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('province_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('province_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('province'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('province'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('district'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('district'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('commune'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('commune'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('mscmss'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('mscmss'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('bsc_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('bsc_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('trautc'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('trautc'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('pcumfs'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('pcumfs'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('station_code'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('station_code'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('co_site'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('co_site'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('localnumber'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('localnumber'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('activitydate'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('activitydate'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('activitystatus'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('activitystatus'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('site_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('site_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('lac'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('lac'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('devicetype'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('devicetype'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('stationtype'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('stationtype'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('configuration'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('configuration'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('combine'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('combine'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('typestation'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('typestation'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('indoormaintenance'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('indoormaintenance'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('outdoormaintenance'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('outdoormaintenance'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('maintenanceby'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('maintenanceby'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('manager'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('manager'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('mobile'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('project'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('project'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('caremanagement'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('caremanagement'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('backlog'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('backlog'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('note'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('note'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>