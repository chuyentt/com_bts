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

<table class="tbl_import tbl_station">
	<?php 
		$rowOrdering = 0;
		foreach ($headerColumns as $k => $header) { ?>
		<tr>
			<th><?php echo $header; ?></th>
			<?php 
				for ($r=1; $r<8; $r++) {
					echo '<td>'.$sheet->records[$r][$k].'</td>';
				} 
			?>
		</tr>
	<?php 
			$rowOrdering++;
		} 
	?>
</table>
