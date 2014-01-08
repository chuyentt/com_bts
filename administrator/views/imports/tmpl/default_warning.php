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

$headerColumns = $this->headerColumns;
$sheet = $this->sheet;

?>

<table class="tbl_import">
	<?php foreach ($sheet->records as $k => $columns) { ?>
	<tr>
		<?php 
		foreach ($columns as $c) {
			if ($k==0) echo '<th>'.$c.'</th>'; else  echo '<td>'.$c.'</td>';
		} 
		?>
	</tr>
	<?php } ?>
</table>