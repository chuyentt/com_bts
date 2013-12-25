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
		
		if ($_POST['station_id']) {
			$station_id = $_POST['station_id'];
			unset($_POST['station_id']);
			
			// get current state
			$db = JFactory::getDbo();
			$query = "SELECT * FROM #__bts_warning WHERE station_id = ".$station_id;
			$db->setQuery($query);
			$warnings = $db->loadObjectList();
			
			$original = array();
			foreach ($warnings as $warning) {
				$original[$warning->id]['maintenance_state'] = $warning->maintenance_state;
				$changes[$parts[1]][] = $parts[0];
			}
			
			// check change
			$changes = array();
			foreach ($_POST as $key => $value) {
				$parts = explode('-', $key);
				$changes[$parts[1]][] = $parts[0];
			}
		}
		
		echo json_encode($_POST);
		jexit();
	}
}