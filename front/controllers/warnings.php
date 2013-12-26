<?php
/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Warnings list controller class.
 */
class BtsControllerWarnings extends BtsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Warnings', $prefix = 'BtsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function save() {
		
		// Check for request forgeries.
		// JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Station', 'BtsModel');
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		
		if ($user->id && count($_POST) && isset($_POST['station_id'])) {
		
			$query = "SELECT id, maintenance_state, approve_state FROM #__bts_warning WHERE `station_id` = ".$_POST['station_id'];
			$db->setQuery($query);
			$warnings = $db->loadObjectList();
			
			$originalVal = array();
			foreach ($warnings as $w) {
				$originalVal[$w->id] = $w->maintenance_state;
			}
			unset($_POST['station_id']);
			foreach ($_POST as $key => $value) {
				// check if value changed
				if ($originalVal[$key] != $value) {
					$row = array();
					$row['id'] = $key;
					$row['maintenance_state'] = $value;
					$row['approve_state'] = 0;
					if ($row['maintenance_state']==2) {
						$row['approve_state'] = 1;
						$row['approve_by'] = $user->id;
						$row['approve_time'] = date('Y-m-d H:i:s');
					} else {
						$row['approve_state'] = 0;
						$row['maintenance_by'] = $user->id;
						$row['maintenance_time'] = date('Y-m-d H:i:s');
					}
					
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
		
		echo json_encode($_POST);
		jexit();
	}
}