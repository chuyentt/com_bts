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

$task = '';
if (count($this->sheets) && count($this->file)) $task = 'imports.import';

?>
<style type="text/css">
	.tbl_import {width:100%;border:1px solid #ccc;}
	.tbl_import td {padding:5px;word-break:break-all;}
	.tbl_import tr > td:nth-child(1) {width:5%;}
	.tbl_import tr > td:nth-child(2) {width:12%;}
	.tbl_import tr > td:nth-child(3) {width:12%;}
	.tbl_import tr > td:nth-child(4) {width:20%;}
	.tbl_import tr > td:nth-child(5) {width:5%;}
	.tbl_import tr > td:nth-child(6) {width:10%;}
	.tbl_import tr > td:nth-child(7) {width:25%;}
	.tbl_import tr > td:nth-child(8) {width:5%;}
	.tbl_import tr > td:nth-child(9) {width:5%;}
	.tbl_import tr > td:nth-child(10) {width:5%;}
	#btn-import-station, #btn-import-warning {margin-right:10px;}
	.errors {margin:15px 0;}
	.errors .alert {margin:5px 0;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_bts&view=imports'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="station-form" class="form-validate">
	<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

		<div class="row-fluid">
		<?php if (!$task) { ?>
			<div class="span6 form-horizontal">
				<fieldset class="adminform">
					<div class="control-group">
						<div class="control-label">
							<span class="label label-success">Station</span>
							<?php echo JText::_('COM_BTS_FORM_LBL_IMPORT_STATION'); ?>
						</div>
						<div class="controls">
							<input class="input_box" name="file_station" type="file" size="57" />
						</div>
					</div>
					<div class="form-actions">
						<input class="btn btn-success" id="btn-station" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_PREVIEW'); ?>" />
					</div>
				</fieldset>
			</div>
			<div class="span6 form-horizontal">
				<fieldset class="adminform">
					<div class="control-group">
						<div class="control-label">
							<span class="label label-warning">Warning</span>
							<?php echo JText::_('COM_BTS_FORM_LBL_IMPORT_WARNING'); ?>
						</div>
						<div class="controls">
							<input class="input_box" name="file_warning" type="file" size="57" />
						</div>
					</div>
					<div class="form-actions">
						<input class="btn btn-warning" id="btn-warning" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_PREVIEW'); ?>" />
					</div>
				</fieldset>
			</div>
		<?php } ?>
   
			<?php if (count($this->sheets)) { ?>
			<div class="span12 form-horizontal">
				<h3>Import data for <?php echo strtoupper($this->type); ?>S</h3>
				<p>
					Preview data for file: <?php echo $this->file['name']; ?>
					<input class="btn btn-default pull-right" id="btn-import-back" type="button" value="<?php echo JText::_('COM_BTS_FORM_BTN_IMPORT_BACK'); ?>" onclick="window.location = 'index.php?option=com_bts&view=imports'" /> 
					
					<?php if ($this->type == 'station' && !count($this->error)) { ?>
						<input class="btn btn-primary pull-right" id="btn-import-station" type="submit" value="<?php echo JText::_('COM_BTS_FORM_BTN_IMPORT'); ?>" />
						<div class="pull-right">
							<input type="checkbox" name="clearData" id="clearData" />
							<label for="clearData"><?php echo JText::_('COM_BTS_FORM_IMPORT_REPLACE'); ?></label>
						</div>
					<?php } ?>
					
					<?php if ($this->type == 'warning' && !count($this->error)) { ?>
						<input class="btn btn-primary pull-right" id="btn-import-warning" type="submit" value="<?php echo JText::_('COM_BTS_FORM_BTN_IMPORT'); ?>" />
						<div class="pull-right">
							<input type="checkbox" name="clearData" id="clearData" />
							<label for="clearData"><?php echo JText::_('COM_BTS_FORM_IMPORT_REPLACE'); ?></label>
						</div>
					<?php } ?>
				</p>
				
				<?php if (count($this->error)) { ?>
				<div class="row-fluid errors">
					<?php foreach ($this->error as $e) { ?> 
						<div class="alert alert-error"><?php echo $e; ?></div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'sheet0')); ?>
					<?php foreach ($this->sheets as $k=>$sheet) { ?>
						<?php 
							echo JHtml::_('bootstrap.addTab', 'myTab', 'sheet'.$k, $sheet->name); 
							$this->sheet = $sheet;
							$this->headerColumns = $sheet->records[0];
							echo $this->loadTemplate($this->type);
						?>
							
						<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php } ?>
				<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			</div>
			<?php } ?>
			
			<input type="hidden" name="task" value="<?php echo $task; ?>" />
			<input type="hidden" name="type" value="<?php echo $this->type; ?>" id="task_type" />
			<input type="hidden" name="file" value="<?php echo (count($this->file)) ? $this->file['tmp_name'] : ''; ?>" />
			<?php echo JHtml::_('form.token'); ?>

		</div>
    </div>
</form>
<script type="text/javascript">
	(function($){
		$('#btn-warning').click(function() {$('#task_type').val('warning');$( "#station-form" ).submit();});
		$('#btn-station').click(function() {$('#task_type').val('station');$( "#station-form" ).submit();});
	})(jQuery);
</script>