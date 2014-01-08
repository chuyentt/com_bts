<?php
/**
 * @version     1.0.1
 * @package     mod_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>
<div class="row-striped">
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=stations"><?php echo JText::_('COM_BTS_TITLE_STATIONS'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=warnings"><?php echo JText::_('COM_BTS_TITLE_WARNINGS'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=notes"><?php echo JText::_('COM_BTS_TITLE_NOTES'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=logs"><?php echo JText::_('COM_BTS_TITLE_LOGS'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=imports"><?php echo JText::_('COM_BTS_TITLE_IMPORTS'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=exports"><?php echo JText::_('COM_BTS_TITLE_EXPORTS'); ?></a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="index.php?option=com_bts&view=duplicate"><?php echo JText::_('COM_BTS_TITLE_DUPLICATED_BTS'); ?></a>
		</div>
	</div>
</div>
