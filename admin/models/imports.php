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
class BtsModelimports extends JModelLegacy {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_BTS';
	public $_file = array();
	public $_type = '';
	public $_error = array();
	
	public function getPreviewData() {
		if (isset($_FILES['file_station']) || isset($_FILES['file_warning'])) {
			require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
			
			$this->_type 	= $_POST['type'];
			$inputFileType 	= 'Excel5';
			
			$this->_file 	= $_FILES['file_'.$this->_type];
			$inputFileName 	= $this->_file['tmp_name'];
			
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			// $objReader->setReadDataOnly(true);
			// $objReader->setLoadAllSheets();
			$objPHPExcel = $objReader->load($inputFileName);
			
			$previewData = array();
			$sheetCount = $objPHPExcel->getSheetCount();
			
			for ($i=0; $i<$sheetCount; $i++) {
				$sheetView = new StdClass;
				$sheet = $objPHPExcel->getSheet($i);
				$records = $sheet->toArray(null,true,true);
				
				$sheetView->name = $sheet->getTitle();
				$sheetView->records = array_splice($records, 0, 11);
				
				// validate header name
				foreach ($sheetView->records[0] as $header) {
					if (trim($header) && !BtsHelper::validateWarningFields(strtolower(trim($header)), $this->_type)) {
						$this->_error[] = 'Sheet: <strong>'.$sheetView->name.'</strong> &raquo; colum : <strong>'.$header.'</strong> is not matched';
					}
				}
				
				$previewData[] = $sheetView;
			}
			
			// copy temporary file to import folder
			$filePath = JPATH_ROOT.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$this->_file['name'];
			
			if (!copy($this->_file['tmp_name'], $filePath)) {
				echo "failed to copy ".$this->_file['tmp_name'];
			}
			
			$this->_file['tmp_name'] = $filePath;
			
			return $previewData;
		} else {
			return array();
		}
	}
	
	public function getFile() {
		return $this->_file;
	}
	
	public function getType() {
		return $this->_type;
	}
	
	public function getError() {
		return $this->_error;
	}
	
	public function import() {
		
		// require_once 
		require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'tables');
		$db = JFactory::getDbo();
		
		$this->_type 	= $_POST['type'];
		$inputFileType 	= 'Excel5';
		$inputFileName	= $_POST['file'];
		$clearData 		= (isset($_POST['clearData'])) ? true : false;
		
		$user = JFactory::getUser();
		
		
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		// $objReader->setReadDataOnly(true);
		$objReader->setLoadAllSheets();
		$objPHPExcel 	= $objReader->load($inputFileName);
		$sheetCount 	= $objPHPExcel->getSheetCount();
		
		/*--- IMPORT STATION --*/
		if ($this->_type == 'station') {
			$sheet = $objPHPExcel->getSheet(0);
			$sheetData = $sheet->toArray(null,true,true, true);
			// var_dump($sheetData); die;
			reset($sheetData);
			$headers = current($sheetData);
			$sheetData = array_slice($sheetData,1);
			
			$originalData = array();
			foreach ($headers as $key => $header) {
				$originalData[$header] = $key;
			}
			
			// clear all data
			if ($clearData) {
				$query = "TRUNCATE TABLE #__bts_station";
				$db->setQuery($query);
				$db->query();
			}
			
			// get existing stations for comparing before insert
			$query = "SELECT id, CONCAT_WS('-',LOWER(bts_name),LOWER(network)) AS alias FROM #__bts_station";
			$db->setQuery($query);
			$stationAlias = $db->loadAssocList('alias');
			
			$rows = array();
			foreach ($sheetData as $data) {
			
				$row = $originalData;
				foreach ($row as $att => $column) {
					$row[$att] = $data[$column];
				}
				if (trim($row['bts_name'])) {
					$row['id'] = 0;
					
					// check duplicated station
					$alias = strtolower(trim($row['bts_name'])).'-'.strtolower(trim($row['network']));
					if (isset($stationAlias[$alias])) $row['id'] = $stationAlias[$alias]['id'];
					
					$row['ordering'] = 1;
					$row['state'] = 1;
					$row['created_by'] = $user->id;;
					
					$row['indoormaintenance'] = date('Y-m-d', strtotime($row['indoormaintenance']));
					$row['outdoormaintenance'] = date('Y-m-d', strtotime($row['outdoormaintenance']));
					$row['activitydate'] = date('Y-m-d', strtotime($row['activitydate']));
				
				// check existing station
				// $query = "SELECT * FROM #__bts_station WHERE `bts_name` LIKE '".$row['bts_name']."'";
				// $db->setQuery($query);
				// $station = $db->loadObject();
				// if (!$station) {
				
					$rowStation = JTable::getInstance('station', 'BtsTable');
					
					if (!$rowStation->bind($row)) {
						$this->setError($rowStation->getError());
						return false;
					}
					
					if (!$rowStation->store()) {
						$this->setError($rowStation->getError());
						return false;
					}
					
				}
				// }
			}
			
		} else if ($this->_type == 'warning') {
		/*--- IMPORT WARNING --*/
			
			// get list of published stations
			$query = "SELECT id, network, bts_name, LOWER(bts_name) AS bts_alias FROM #__bts_station WHERE `state` = 1";
			$db->setQuery($query);
			$stationsList = $db->loadObjectList();
            $stations = array();
            foreach ($stationsList as $s) {
                $stations[$s->bts_alias][] = $s;
            }
			
			// clear all data
			if ($clearData) {
				$query = "TRUNCATE TABLE #__bts_warning";
				$db->setQuery($query);
				$db->query();
			}
			
			// get existing stations for comparing before insert
			// $query = "SELECT warning.id, CONCAT_WS('-',LOWER(station.bts_name),LOWER(station.network),LOWER(REPLACE(warning.warning_description,' ','')),DATE_FORMAT(warning.warning_time,'%Y%m%d%H%i%s')) AS alias ".
			$query = "SELECT warning.id, CONCAT_WS('-',LOWER(station.bts_name),LOWER(station.network),DATE_FORMAT(warning.warning_time,'%Y%m%d%H%i%s')) AS alias ".
					" FROM #__bts_warning AS warning ".
					" LEFT JOIN #__bts_station AS station ON station.id = warning.station_id ".
					" WHERE warning.state = 1 AND warning.maintenance_state = 0 AND warning.approve_state = 0"
					;
			$db->setQuery($query);
			$warningAlias = $db->loadAssocList('alias');
			print_r($warningAlias); 
			for ($i=0; $i<$sheetCount; $i++) {
				$sheet = $objPHPExcel->getSheet($i);
				
				// get Warning type by name
				$sheetName = $sheet->getTitle();
				$warningType = 0;
				switch ($sheetName) {
					case 'EAS_2G&SRAN':
					case 'Phuluc2_DS_Cell MLL':
						$warningType = 1; // Yellow type
						break;
					case 'HW_2G&SRAN2G':
					case 'HW_3G&SRAN3G':
					case 'GCLK_2G':
						$warningType = 2; // Orange type
						break;
					case 'Site-0_2G&3G&SRAN':
						$warningType = 3; // Red type
						break;
					default:
						$warningType = 0; // Green type
					break;
				}
			
				$sheetData = $sheet->toArray(null,true,true,true);
				reset($sheetData);
				$headers = current($sheetData);
				$sheetData = array_slice($sheetData,1);
			
				$originalData = array();
				foreach ($headers as $key => $header) {
					$originalData[strtolower(trim($header))] = $key;
				}
				
				foreach ($sheetData as $data) {
					$row = $originalData;
					foreach ($row as $att => $column) {
						$row[$att] = $data[$column];
					}
					$row['id'] = 0;
					// var_dump($row);
					// check duplicated station
					$date = explode('-',trim($row['warning_date']));
					$insert = true;
					if (count($date)==3) {
						$time = '20'.$date[2].$date[0].$date[1].trim(str_replace(':','',$row['warning_time']));
						$alias = strtolower($row['bts_name']).'-'.strtolower($row['network']).'-'.$time;
						echo $alias.'<br>';
						if (isset($warningAlias[$alias])) {
							$insert = false;
						}
						
					} else $insert = false;
					
					if ($insert) {
						$row['ordering'] = 1;
						$row['state'] = 1;
						$row['level'] = $warningType;
						$row['created_by'] = $user->id;
						
						// get station ID in station table
						$row['station_id'] = 0;
						if (isset($stations[strtolower($row['bts_name'])])) {
							foreach ($stations[strtolower($row['bts_name'])] as $station) {
								if ($station->network == $row['network']) {
									$row['station_id'] = $station->id;
									break;
								}
							}
						}
						
						// if (isset($stations[strtolower(trim($row['bts_name']))])) {
						if ($row['station_id']) {
							
							// handle warning date
							// $time = date("H:i:s", strtotime($row['time']));
							$date = explode('-',trim($row['warning_date']));
							if (trim($row['warning_time'])) $row['warning_time'] = '20'.$date[2].'-'.$date[0].'-'.$date[1].' '.$row['warning_time'];
								else $row['warning_time'] = '20'.$date[2].'-'.$date[0].'-'.$date[1].' '.date('H:i:s');
								
							$rowWarning = JTable::getInstance('warning', 'BtsTable');
							
							if (!$rowWarning->bind($row)) {
								$this->setError($rowWarning->getError());
								return false;
							}
							
							if (!$rowWarning->store()) {
								$this->setError($rowWarning->getError());
								return false;
							}
						}
					}
				}
			}
			die;
		}
		
		return true;
	}

}
