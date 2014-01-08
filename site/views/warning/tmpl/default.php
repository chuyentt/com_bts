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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_bts', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_bts');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_bts')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_STATION_ID'); ?>:
			<?php echo $this->item->station_id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_WARNING_DESCRIPTION'); ?>:
			<?php echo $this->item->warning_description; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_DEVICE'); ?>:
			<?php echo $this->item->device; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_LEVEL'); ?>:
			<?php echo $this->item->level; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_WARNING_TIME'); ?>:
			<?php echo $this->item->warning_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_MAINTENANCE_BY'); ?>:
			<?php echo $this->item->maintenance_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_MAINTENANCE_TIME'); ?>:
			<?php echo $this->item->maintenance_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_APPROVE_BY'); ?>:
			<?php echo $this->item->approve_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_APPROVE_TIME'); ?>:
			<?php echo $this->item->approve_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_MAINTENANCE_STATE'); ?>:
			<?php echo $this->item->maintenance_state; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_WARNING_APPROVE_STATE'); ?>:
			<?php echo $this->item->approve_state; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_bts&task=warning.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BTS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_bts')):
								?>
									<a href="javascript:document.getElementById('form-warning-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_BTS_DELETE_ITEM"); ?></a>
									<form id="form-warning-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=warning.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[station_id]" value="<?php echo $this->item->station_id; ?>" />
										<input type="hidden" name="jform[warning_description]" value="<?php echo $this->item->warning_description; ?>" />
										<input type="hidden" name="jform[device]" value="<?php echo $this->item->device; ?>" />
										<input type="hidden" name="jform[level]" value="<?php echo $this->item->level; ?>" />
										<input type="hidden" name="jform[warning_time]" value="<?php echo $this->item->warning_time; ?>" />
										<input type="hidden" name="jform[maintenance_by]" value="<?php echo $this->item->maintenance_by; ?>" />
										<input type="hidden" name="jform[maintenance_time]" value="<?php echo $this->item->maintenance_time; ?>" />
										<input type="hidden" name="jform[approve_by]" value="<?php echo $this->item->approve_by; ?>" />
										<input type="hidden" name="jform[approve_time]" value="<?php echo $this->item->approve_time; ?>" />
										<input type="hidden" name="jform[maintenance_state]" value="<?php echo $this->item->maintenance_state; ?>" />
										<input type="hidden" name="jform[approve_state]" value="<?php echo $this->item->approve_state; ?>" />
										<input type="hidden" name="option" value="com_bts" />
										<input type="hidden" name="task" value="warning.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php
else:
    echo JText::_('COM_BTS_ITEM_NOT_LOADED');
endif;
?>
