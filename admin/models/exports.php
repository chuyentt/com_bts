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
jimport('joomla.application.component.helper');
 
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
				$query = "SELECT * FROM #__bts_station AS a";
				$db->setQuery($query);
				$data = $db->loadObjectList();
			} elseif ($this->_type == 'warning') {
				// set more exported fields
				$extraFields = array('state','level','maintenance_by','maintenance_time','approve_by','approve_time','maintenance_state','approve_state');
				
				// get condition by time
				$timeWhere = array();
				$from = JComponentHelper::getParams('com_bts')->get('export_warning_from');
				$to = JComponentHelper::getParams('com_bts')->get('export_warning_to');
				if ($from && $from != '0000-00-00')$timeWhere[] = "a.warning_time >= '".$from."' ";
				if ($to && $to != '0000-00-00') $timeWhere[] = "a.warning_time <= '".$to."' ";
				
				// get data for warning
				$query = "SELECT a.*, station.bts_name, station.network, ".
						" DATE_FORMAT(a.warning_time, '%d-%m-%Y %H:%i:%s') AS warning_time, DATE_FORMAT(a.maintenance_time, '%d-%m-%Y %H:%i:%s') AS maintenance_time, DATE_FORMAT(a.approve_time, '%d-%m-%Y %H:%i:%s') AS approve_time ".
						" FROM #__bts_warning AS a ".
						" LEFT JOIN #__bts_station AS station ON station.id = a.station_id"
				;
				if (count($timeWhere)) $query .= ' WHERE '.implode(' AND ', $timeWhere);
				$query .= " ORDER BY a.warning_time";
				$db->setQuery($query);
				$data = $db->loadObjectList();
			} elseif ($this->_type == 'note') {
				$extraFields = array('state','bts_name','network');
				
				// get condition by time
				$timeWhere = array();
				$from = JComponentHelper::getParams('com_bts')->get('export_notes_from');
				$to = JComponentHelper::getParams('com_bts')->get('export_notes_to');
				if ($from && $from != '0000-00-00')$timeWhere[] = "a.created_time >= '".$from."' ";
				if ($to && $to != '0000-00-00') $timeWhere[] = "a.created_time <= '".$to."' ";
				
				// get data for note
				$query = "SELECT a.*, station.bts_name, station.network, ".
						" DATE_FORMAT(a.created_time, '%d-%m-%Y %H:%i:%s') AS created_time, DATE_FORMAT(a.approved_time, '%d-%m-%Y %H:%i:%s') AS approved_time, ".
						" created_by.name AS created_by, approved_by.name AS approved_by  ".
						" FROM #__bts_note AS a ".
						" LEFT JOIN #__bts_station AS station ON station.id = a.station_id".
						" LEFT JOIN #__users AS created_by ON created_by.id = a.created_by".
						" LEFT JOIN #__users AS approved_by ON approved_by.id = a.approved_by"
				;
				if (count($timeWhere)) $query .= ' WHERE '.implode(' AND ', $timeWhere);
				$query .= " ORDER BY a.created_time";
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
