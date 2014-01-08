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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_bts', JPATH_ADMINISTRATOR);
?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }
    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }
    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        text-align: center;
        width: 200px !important;
    }
    .front-end-edit .radio label {
        float: none;
    }
    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }    
    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }
    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
    }

    #jform_rules-lbl{
        display:none;
    }

    #access-rules a:hover{
        background:#f5f5f5 url('../images/slider_minus.png') right  top no-repeat;
        color: #444;
    }

    fieldset.radio label{
        width: 50px !important;
    }
</style>
<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            js('#form-warning').submit(function(event){
                 
            }); 
        
            
					js('input:hidden.station_id').each(function(){
						var name = js(this).attr('name');
						if(name.indexOf('station_idhidden')){
							js('#jform_station_id option[value="'+js(this).val()+'"]').attr('selected',true);
						}
					});
					js("#jform_station_id").trigger("liszt:updated");
        });
    });
    
</script>

<div class="warning-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-warning" action="<?php echo JRoute::_('index.php?option=com_bts&task=warning.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
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

        </ul>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_bts&task=warning.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_bts" />
            <input type="hidden" name="task" value="warningform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
