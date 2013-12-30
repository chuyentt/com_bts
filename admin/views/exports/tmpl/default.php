<?php
/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_bts/assets/css/bts.css');

?>
<form action="<?php echo JRoute::_('index.php?option=com_bts&view=exports'); ?>" method="post" name="adminForm" id="station-form" class="form-validate">
	<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

		<div class="row-fluid">
			<input class="btn btn-success" id="btn-station" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_EXPORTS_STATION'); ?>" />
			<input class="btn btn-warning" id="btn-warning" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_EXPORTS_WARNING'); ?>" />
			<input class="btn btn-primary" id="btn-note" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_EXPORTS_NOTE'); ?>" />
			
			<?php if ($this->exported) { ?>
				<br>
				<div class="alert alert-info">Export successfully! Please download with link: <a href="<?php echo $this->exported; ?>"><?php echo $this->exported; ?></a></div>
			<?php } ?>
			
			<input type="hidden" name="task" value="exports.export" />
			<input type="hidden" name="type" value="" id="task_type" />
			<?php echo JHtml::_('form.token'); ?>

		</div>
    </div>
</form>
<script type="text/javascript">
	(function($){
		$('#btn-warning').click(function() {$('#task_type').val('warning');$( "#station-form" ).submit();});
		$('#btn-station').click(function() {$('#task_type').val('station');$( "#station-form" ).submit();});
		$('#btn-note').click(function() {$('#task_type').val('note');$( "#station-form" ).submit();});
	})(jQuery);
</script>