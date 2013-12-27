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

            			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_STATION_ID'); ?>:
			<?php echo $this->item->station_id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_NOTE'); ?>:
			<?php echo $this->item->note; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_CREATED_TIME'); ?>:
			<?php echo $this->item->created_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_APPROVED'); ?>:
			<?php echo $this->item->approved; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_APPROVED_BY'); ?>:
			<?php echo $this->item->approved_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_NOTE_APPROVED_TIME'); ?>:
			<?php echo $this->item->approved_time; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_bts&task=note.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BTS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_bts')):
								?>
									<a href="javascript:document.getElementById('form-note-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_BTS_DELETE_ITEM"); ?></a>
									<form id="form-note-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=note.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[station_id]" value="<?php echo $this->item->station_id; ?>" />
										<input type="hidden" name="jform[note]" value="<?php echo $this->item->note; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[created_time]" value="<?php echo $this->item->created_time; ?>" />
										<input type="hidden" name="jform[approved]" value="<?php echo $this->item->approved; ?>" />
										<input type="hidden" name="jform[approved_by]" value="<?php echo $this->item->approved_by; ?>" />
										<input type="hidden" name="jform[approved_time]" value="<?php echo $this->item->approved_time; ?>" />
										<input type="hidden" name="option" value="com_bts" />
										<input type="hidden" name="task" value="note.remove" />
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
