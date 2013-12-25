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
?>
<script type="text/javascript">
    function deleteItem(item_id){
        if(confirm("<?php echo JText::_('COM_BTS_DELETE_MESSAGE'); ?>")){
            document.getElementById('form-warning-delete-' + item_id).submit();
        }
    }
</script>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_bts'))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_bts&view=warning&id=' . (int)$item->id); ?>"><?php echo $item->warning_description; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_bts')):
									?>
										<a href="javascript:document.getElementById('form-warning-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_BTS_UNPUBLISH_ITEM"); else: echo JText::_("COM_BTS_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-warning-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=warning.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[station_id]" value="<?php echo $item->station_id; ?>" />
											<input type="hidden" name="jform[warning_description]" value="<?php echo $item->warning_description; ?>" />
											<input type="hidden" name="jform[device]" value="<?php echo $item->device; ?>" />
											<input type="hidden" name="jform[level]" value="<?php echo $item->level; ?>" />
											<input type="hidden" name="jform[warning_time]" value="<?php echo $item->warning_time; ?>" />
											<input type="hidden" name="jform[maintenance_by]" value="<?php echo $item->maintenance_by; ?>" />
											<input type="hidden" name="jform[maintenance_time]" value="<?php echo $item->maintenance_time; ?>" />
											<input type="hidden" name="jform[approve_by]" value="<?php echo $item->approve_by; ?>" />
											<input type="hidden" name="jform[approve_time]" value="<?php echo $item->approve_time; ?>" />
											<input type="hidden" name="jform[maintenance_state]" value="<?php echo $item->maintenance_state; ?>" />
											<input type="hidden" name="jform[approve_state]" value="<?php echo $item->approve_state; ?>" />
											<input type="hidden" name="option" value="com_bts" />
											<input type="hidden" name="task" value="warning.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_bts')):
									?>
										<a href="javascript:deleteItem(<?php echo $item->id; ?>);"><?php echo JText::_("COM_BTS_DELETE_ITEM"); ?></a>
										<form id="form-warning-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=warning.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[station_id]" value="<?php echo $item->station_id; ?>" />
											<input type="hidden" name="jform[warning_description]" value="<?php echo $item->warning_description; ?>" />
											<input type="hidden" name="jform[device]" value="<?php echo $item->device; ?>" />
											<input type="hidden" name="jform[level]" value="<?php echo $item->level; ?>" />
											<input type="hidden" name="jform[warning_time]" value="<?php echo $item->warning_time; ?>" />
											<input type="hidden" name="jform[maintenance_by]" value="<?php echo $item->maintenance_by; ?>" />
											<input type="hidden" name="jform[maintenance_time]" value="<?php echo $item->maintenance_time; ?>" />
											<input type="hidden" name="jform[approve_by]" value="<?php echo $item->approve_by; ?>" />
											<input type="hidden" name="jform[approve_time]" value="<?php echo $item->approve_time; ?>" />
											<input type="hidden" name="jform[maintenance_state]" value="<?php echo $item->maintenance_state; ?>" />
											<input type="hidden" name="jform[approve_state]" value="<?php echo $item->approve_state; ?>" />
											<input type="hidden" name="option" value="com_bts" />
											<input type="hidden" name="task" value="warning.remove" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
								?>
							</li>
						<?php endif; ?>

<?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_BTS_NO_ITEMS');
        endif;
        ?>
    </ul>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>


									<?php if(JFactory::getUser()->authorise('core.create','com_bts')): ?><a href="<?php echo JRoute::_('index.php?option=com_bts&task=warning.edit&id=0'); ?>"><?php echo JText::_("COM_BTS_ADD_ITEM"); ?></a>
	<?php endif; ?>