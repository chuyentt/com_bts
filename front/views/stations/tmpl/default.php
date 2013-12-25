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
            document.getElementById('form-station-delete-' + item_id).submit();
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
								<a href="<?php echo JRoute::_('index.php?option=com_bts&view=station&id=' . (int)$item->id); ?>"><?php echo $item->bts_name; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_bts')):
									?>
										<a href="javascript:document.getElementById('form-station-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_BTS_UNPUBLISH_ITEM"); else: echo JText::_("COM_BTS_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-station-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=station.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[bts_name]" value="<?php echo $item->bts_name; ?>" />
											<input type="hidden" name="jform[network]" value="<?php echo $item->network; ?>" />
											<input type="hidden" name="jform[address]" value="<?php echo $item->address; ?>" />
											<input type="hidden" name="jform[latitude]" value="<?php echo $item->latitude; ?>" />
											<input type="hidden" name="jform[longitude]" value="<?php echo $item->longitude; ?>" />
											<input type="hidden" name="jform[province_id]" value="<?php echo $item->province_id; ?>" />
											<input type="hidden" name="jform[province]" value="<?php echo $item->province; ?>" />
											<input type="hidden" name="jform[district]" value="<?php echo $item->district; ?>" />
											<input type="hidden" name="jform[commune]" value="<?php echo $item->commune; ?>" />
											<input type="hidden" name="jform[mscmss]" value="<?php echo $item->mscmss; ?>" />
											<input type="hidden" name="jform[bsc_name]" value="<?php echo $item->bsc_name; ?>" />
											<input type="hidden" name="jform[trautc]" value="<?php echo $item->trautc; ?>" />
											<input type="hidden" name="jform[pcumfs]" value="<?php echo $item->pcumfs; ?>" />
											<input type="hidden" name="jform[station_code]" value="<?php echo $item->station_code; ?>" />
											<input type="hidden" name="jform[co_site]" value="<?php echo $item->co_site; ?>" />
											<input type="hidden" name="jform[localnumber]" value="<?php echo $item->localnumber; ?>" />
											<input type="hidden" name="jform[activitydate]" value="<?php echo $item->activitydate; ?>" />
											<input type="hidden" name="jform[activitystatus]" value="<?php echo $item->activitystatus; ?>" />
											<input type="hidden" name="jform[site_id]" value="<?php echo $item->site_id; ?>" />
											<input type="hidden" name="jform[lac]" value="<?php echo $item->lac; ?>" />
											<input type="hidden" name="jform[devicetype]" value="<?php echo $item->devicetype; ?>" />
											<input type="hidden" name="jform[stationtype]" value="<?php echo $item->stationtype; ?>" />
											<input type="hidden" name="jform[configuration]" value="<?php echo $item->configuration; ?>" />
											<input type="hidden" name="jform[combine]" value="<?php echo $item->combine; ?>" />
											<input type="hidden" name="jform[typestation]" value="<?php echo $item->typestation; ?>" />
											<input type="hidden" name="jform[indoormaintenance]" value="<?php echo $item->indoormaintenance; ?>" />
											<input type="hidden" name="jform[outdoormaintenance]" value="<?php echo $item->outdoormaintenance; ?>" />
											<input type="hidden" name="jform[maintenanceby]" value="<?php echo $item->maintenanceby; ?>" />
											<input type="hidden" name="jform[manager]" value="<?php echo $item->manager; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[project]" value="<?php echo $item->project; ?>" />
											<input type="hidden" name="jform[caremanagement]" value="<?php echo $item->caremanagement; ?>" />
											<input type="hidden" name="jform[backlog]" value="<?php echo $item->backlog; ?>" />
											<input type="hidden" name="jform[note]" value="<?php echo $item->note; ?>" />
											<input type="hidden" name="option" value="com_bts" />
											<input type="hidden" name="task" value="station.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_bts')):
									?>
										<a href="javascript:deleteItem(<?php echo $item->id; ?>);"><?php echo JText::_("COM_BTS_DELETE_ITEM"); ?></a>
										<form id="form-station-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=station.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[bts_name]" value="<?php echo $item->bts_name; ?>" />
											<input type="hidden" name="jform[network]" value="<?php echo $item->network; ?>" />
											<input type="hidden" name="jform[address]" value="<?php echo $item->address; ?>" />
											<input type="hidden" name="jform[latitude]" value="<?php echo $item->latitude; ?>" />
											<input type="hidden" name="jform[longitude]" value="<?php echo $item->longitude; ?>" />
											<input type="hidden" name="jform[province_id]" value="<?php echo $item->province_id; ?>" />
											<input type="hidden" name="jform[province]" value="<?php echo $item->province; ?>" />
											<input type="hidden" name="jform[district]" value="<?php echo $item->district; ?>" />
											<input type="hidden" name="jform[commune]" value="<?php echo $item->commune; ?>" />
											<input type="hidden" name="jform[mscmss]" value="<?php echo $item->mscmss; ?>" />
											<input type="hidden" name="jform[bsc_name]" value="<?php echo $item->bsc_name; ?>" />
											<input type="hidden" name="jform[trautc]" value="<?php echo $item->trautc; ?>" />
											<input type="hidden" name="jform[pcumfs]" value="<?php echo $item->pcumfs; ?>" />
											<input type="hidden" name="jform[station_code]" value="<?php echo $item->station_code; ?>" />
											<input type="hidden" name="jform[co_site]" value="<?php echo $item->co_site; ?>" />
											<input type="hidden" name="jform[localnumber]" value="<?php echo $item->localnumber; ?>" />
											<input type="hidden" name="jform[activitydate]" value="<?php echo $item->activitydate; ?>" />
											<input type="hidden" name="jform[activitystatus]" value="<?php echo $item->activitystatus; ?>" />
											<input type="hidden" name="jform[site_id]" value="<?php echo $item->site_id; ?>" />
											<input type="hidden" name="jform[lac]" value="<?php echo $item->lac; ?>" />
											<input type="hidden" name="jform[devicetype]" value="<?php echo $item->devicetype; ?>" />
											<input type="hidden" name="jform[stationtype]" value="<?php echo $item->stationtype; ?>" />
											<input type="hidden" name="jform[configuration]" value="<?php echo $item->configuration; ?>" />
											<input type="hidden" name="jform[combine]" value="<?php echo $item->combine; ?>" />
											<input type="hidden" name="jform[typestation]" value="<?php echo $item->typestation; ?>" />
											<input type="hidden" name="jform[indoormaintenance]" value="<?php echo $item->indoormaintenance; ?>" />
											<input type="hidden" name="jform[outdoormaintenance]" value="<?php echo $item->outdoormaintenance; ?>" />
											<input type="hidden" name="jform[maintenanceby]" value="<?php echo $item->maintenanceby; ?>" />
											<input type="hidden" name="jform[manager]" value="<?php echo $item->manager; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[project]" value="<?php echo $item->project; ?>" />
											<input type="hidden" name="jform[caremanagement]" value="<?php echo $item->caremanagement; ?>" />
											<input type="hidden" name="jform[backlog]" value="<?php echo $item->backlog; ?>" />
											<input type="hidden" name="jform[note]" value="<?php echo $item->note; ?>" />
											<input type="hidden" name="option" value="com_bts" />
											<input type="hidden" name="task" value="station.remove" />
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


									<?php if(JFactory::getUser()->authorise('core.create','com_bts')): ?><a href="<?php echo JRoute::_('index.php?option=com_bts&task=station.edit&id=0'); ?>"><?php echo JText::_("COM_BTS_ADD_ITEM"); ?></a>
	<?php endif; ?>