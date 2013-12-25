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

            			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_BTS_NAME'); ?>:
			<?php echo $this->item->bts_name; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_NETWORK'); ?>:
			<?php echo $this->item->network; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ADDRESS'); ?>:
			<?php echo $this->item->address; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_LATITUDE'); ?>:
			<?php echo $this->item->latitude; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_LONGITUDE'); ?>:
			<?php echo $this->item->longitude; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_PROVINCE_ID'); ?>:
			<?php echo $this->item->province_id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_PROVINCE'); ?>:
			<?php echo $this->item->province; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_DISTRICT'); ?>:
			<?php echo $this->item->district; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_COMMUNE'); ?>:
			<?php echo $this->item->commune; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_MSCMSS'); ?>:
			<?php echo $this->item->mscmss; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_BSC_NAME'); ?>:
			<?php echo $this->item->bsc_name; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_TRAUTC'); ?>:
			<?php echo $this->item->trautc; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_PCUMFS'); ?>:
			<?php echo $this->item->pcumfs; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_STATION_CODE'); ?>:
			<?php echo $this->item->station_code; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CO_SITE'); ?>:
			<?php echo $this->item->co_site; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_LOCALNUMBER'); ?>:
			<?php echo $this->item->localnumber; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ACTIVITYDATE'); ?>:
			<?php echo $this->item->activitydate; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ACTIVITYSTATUS'); ?>:
			<?php echo $this->item->activitystatus; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_SITE_ID'); ?>:
			<?php echo $this->item->site_id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_LAC'); ?>:
			<?php echo $this->item->lac; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_DEVICETYPE'); ?>:
			<?php echo $this->item->devicetype; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_STATIONTYPE'); ?>:
			<?php echo $this->item->stationtype; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CONFIGURATION'); ?>:
			<?php echo $this->item->configuration; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_COMBINE'); ?>:
			<?php echo $this->item->combine; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_TYPESTATION'); ?>:
			<?php echo $this->item->typestation; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_INDOORMAINTENANCE'); ?>:
			<?php echo $this->item->indoormaintenance; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_OUTDOORMAINTENANCE'); ?>:
			<?php echo $this->item->outdoormaintenance; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_MAINTENANCEBY'); ?>:
			<?php echo $this->item->maintenanceby; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_MANAGER'); ?>:
			<?php echo $this->item->manager; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_MOBILE'); ?>:
			<?php echo $this->item->mobile; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_PROJECT'); ?>:
			<?php echo $this->item->project; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_CAREMANAGEMENT'); ?>:
			<?php echo $this->item->caremanagement; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_BACKLOG'); ?>:
			<?php echo $this->item->backlog; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_STATION_NOTE'); ?>:
			<?php echo $this->item->note; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_bts&task=station.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BTS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_bts')):
								?>
									<a href="javascript:document.getElementById('form-station-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_BTS_DELETE_ITEM"); ?></a>
									<form id="form-station-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_bts&task=station.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[bts_name]" value="<?php echo $this->item->bts_name; ?>" />
										<input type="hidden" name="jform[network]" value="<?php echo $this->item->network; ?>" />
										<input type="hidden" name="jform[address]" value="<?php echo $this->item->address; ?>" />
										<input type="hidden" name="jform[latitude]" value="<?php echo $this->item->latitude; ?>" />
										<input type="hidden" name="jform[longitude]" value="<?php echo $this->item->longitude; ?>" />
										<input type="hidden" name="jform[province_id]" value="<?php echo $this->item->province_id; ?>" />
										<input type="hidden" name="jform[province]" value="<?php echo $this->item->province; ?>" />
										<input type="hidden" name="jform[district]" value="<?php echo $this->item->district; ?>" />
										<input type="hidden" name="jform[commune]" value="<?php echo $this->item->commune; ?>" />
										<input type="hidden" name="jform[mscmss]" value="<?php echo $this->item->mscmss; ?>" />
										<input type="hidden" name="jform[bsc_name]" value="<?php echo $this->item->bsc_name; ?>" />
										<input type="hidden" name="jform[trautc]" value="<?php echo $this->item->trautc; ?>" />
										<input type="hidden" name="jform[pcumfs]" value="<?php echo $this->item->pcumfs; ?>" />
										<input type="hidden" name="jform[station_code]" value="<?php echo $this->item->station_code; ?>" />
										<input type="hidden" name="jform[co_site]" value="<?php echo $this->item->co_site; ?>" />
										<input type="hidden" name="jform[localnumber]" value="<?php echo $this->item->localnumber; ?>" />
										<input type="hidden" name="jform[activitydate]" value="<?php echo $this->item->activitydate; ?>" />
										<input type="hidden" name="jform[activitystatus]" value="<?php echo $this->item->activitystatus; ?>" />
										<input type="hidden" name="jform[site_id]" value="<?php echo $this->item->site_id; ?>" />
										<input type="hidden" name="jform[lac]" value="<?php echo $this->item->lac; ?>" />
										<input type="hidden" name="jform[devicetype]" value="<?php echo $this->item->devicetype; ?>" />
										<input type="hidden" name="jform[stationtype]" value="<?php echo $this->item->stationtype; ?>" />
										<input type="hidden" name="jform[configuration]" value="<?php echo $this->item->configuration; ?>" />
										<input type="hidden" name="jform[combine]" value="<?php echo $this->item->combine; ?>" />
										<input type="hidden" name="jform[typestation]" value="<?php echo $this->item->typestation; ?>" />
										<input type="hidden" name="jform[indoormaintenance]" value="<?php echo $this->item->indoormaintenance; ?>" />
										<input type="hidden" name="jform[outdoormaintenance]" value="<?php echo $this->item->outdoormaintenance; ?>" />
										<input type="hidden" name="jform[maintenanceby]" value="<?php echo $this->item->maintenanceby; ?>" />
										<input type="hidden" name="jform[manager]" value="<?php echo $this->item->manager; ?>" />
										<input type="hidden" name="jform[mobile]" value="<?php echo $this->item->mobile; ?>" />
										<input type="hidden" name="jform[project]" value="<?php echo $this->item->project; ?>" />
										<input type="hidden" name="jform[caremanagement]" value="<?php echo $this->item->caremanagement; ?>" />
										<input type="hidden" name="jform[backlog]" value="<?php echo $this->item->backlog; ?>" />
										<input type="hidden" name="jform[note]" value="<?php echo $this->item->note; ?>" />
										<input type="hidden" name="option" value="com_bts" />
										<input type="hidden" name="task" value="station.remove" />
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
