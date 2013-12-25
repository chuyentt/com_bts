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
<form action="<?php echo JRoute::_('index.php?option=com_bts&view=duplicate'); ?>" method="post" name="adminForm" id="station-form" class="form-validate">
	<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

		<div class="row-fluid">
			<div class="alert alert-block"><?php echo JText::_('COM_BTS_DUPLICATED_BTS_WARNING'); ?></div>
			<input class="btn btn-success" id="btn-duplicate" type="button" value="<?php echo JText::_('COM_BTS_DUPLICATED_BTS_RUN'); ?>" />
			
			<br>
			<br>
			<div class="progress progress-striped active hidden" id="progress">
				<div class="bar" style="width: 100%;"></div>
			</div>
			
			<div class="alert alert-success" style="display: none" id="alert-success">
				<?php echo JText::_('COM_BTS_DUPLICATED_BTS_ALERT_SUCCESS'); ?>
			</div>
			
			<div class="alert alert-error" style="display: none" id="alert-error">
				<?php echo JText::_('COM_BTS_DUPLICATED_BTS_ALERT_ERROR'); ?>
			</div>
			
			<?php echo JHtml::_('form.token'); ?>

		</div>
    </div>
</form>
<script type="text/javascript">
	(function($){
		$('#btn-duplicate').click(function() {
			$('#progress').removeClass('hidden');
			$('#progress').fadeIn();
			$.getJSON( "index.php?option=com_bts&task=duplicate.run", function( data ) {
				$('#progress').fadeOut();
				if (data[0]==1) $('#alert-success').fadeIn();
					else $('#alert-error').fadeIn();
			});
		});
	})(jQuery);
</script>