<?php

/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
defined('_JEXEC') or die;

/**
 * Methods supporting a list of Bts records.
 */
class BtsModelExports extends JModelLegacy {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_BTS';
	public $_file = array();
	public $_type = '';
	public $_error = array();
	
	
	public function getExport() {
		$this->_type 	= (isset($_POST['type'])) ? $_POST['type'] : false;
		$exportedFile = '';
		if ($this->_type) {
		
			$user = JFactory::getUser();
			$canEdit = $user->authorise('core.edit', 'com_bts') || $user->authorise('core.create', 'com_bts');
			
			if (!$canEdit) {
				JError::raiseError('500', JText::_('JERROR_ALERTNOAUTHOR'));
			}
		
			require_once JPATH_COMPONENT.'/helpers/bts.php';
			require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
			$db = JFactory::getDbo();
				
			
			
			$objPHPExcel = new PHPExcel();
			$excelColumns = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Geomatics")
										 ->setLastModifiedBy("Chuyen Trung Tran")
										 ->setTitle("BTS ".$this->_type)
										 ->setSubject("BTS ".$this->_type)
										 ->setDescription($this->_type)
										 ->setKeywords($this->_type);

			// Create a first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			$fields = BtsHelper::validateWarningFields('', $this->_type, true);
			if ($this->_type == 'station') {
				$extraFields = array('state');
			
				// get data of stations
				$query = "SELECT * FROM #__bts_station";
				$db->setQuery($query);
				$data = $db->loadObjectList();
			} else {
				// set more exported fields
				$extraFields = array('state','level','maintenance_by','maintenance_time','approve_by','approve_time','maintenance_state','approve_state');
				
				// get data for warning
				$query = "SELECT warning.*, station.bts_name, station.network ".
						" FROM #__bts_warning AS warning ".
						" LEFT JOIN #__bts_station AS station ON station.id = warning.station_id".
						" ORDER BY bts_name"
				;
				$db->setQuery($query);
				$data = $db->loadObjectList();
			}
			
			$newFields = array_merge($extraFields, $fields);
			$fields = $newFields;
			
			// set header for sheet
			foreach ($fields as $key => $col) {
				$objPHPExcel->getActiveSheet()->setCellValue($excelColumns[$key].'1', $col);
			}
			
			// add data
			$rowIndex = 1;
			foreach ($data as $bts) {
				$rowIndex++;
				foreach ($fields as $key => $col) {
					$objPHPExcel->getActiveSheet()->setCellValue($excelColumns[$key].$rowIndex, $bts->$col);
				}
			}

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			// Redirect output to a client’s web browser (Excel2007)
			$exportedFile = $this->_type.'_exported';
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment;filename='.$exportedFile.'.xls');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			jexit();
			
			// $exportedFileName = $this->_type.'_exported.xls';
			$exportedFile = $this->_type.'_exported.xls';
		}
		return $exportedFile;
	}
}
