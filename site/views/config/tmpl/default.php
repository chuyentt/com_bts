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

?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_FIELD'); ?>:
			<?php echo $this->item->field; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_LABEL'); ?>:
			<?php echo $this->item->label; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_DISPLAYABLE'); ?>:
			<?php echo $this->item->displayable; ?></li>
			<li><?php echo JText::_('COM_BTS_FORM_LBL_CONFIG_EDITABLE'); ?>:
			<?php echo $this->item->editable; ?></li>


        </ul>

    </div>
    
<?php
else:
    echo JText::_('COM_BTS_ITEM_NOT_LOADED');
endif;
?>
