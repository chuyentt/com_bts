<?php
/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Vnpbtsnodeb helper.
 */
class BtsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_STATIONS'),
			'index.php?option=com_bts&view=stations',
			$vName == 'stations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_WARNINGS'),
			'index.php?option=com_bts&view=warnings',
			$vName == 'warnings'
		);
/*		
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_STATUS'),
			'index.php?option=com_bts&view=status',
			$vName == 'status'
		);
*/	
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_NOTES'),
			'index.php?option=com_bts&view=notes',
			$vName == 'notes'
		);		
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_LOGS'),
			'index.php?option=com_bts&view=logs',
			$vName == 'logs'
		);	
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_IMPORTS'),
			'index.php?option=com_bts&view=imports',
			$vName == 'imports'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_EXPORTS'),
			'index.php?option=com_bts&view=exports',
			$vName == 'exports'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_BTS_TITLE_DUPLICATED_BTS'),
			'index.php?option=com_bts&view=duplicate',
			$vName == 'duplicate'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_bts';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	
	/**
	 * Validate header of warning excel file. They have to match with field names in warning table
	 *
	 * @return	Boolean
	 */
	public function validateWarningFields($field, $table, $getFields = false) {
		if ($table == 'warning') {
			$fields = array('vnp','network','bsc_name','bts_name','bts_no','device','warning_description','warning_time','warning_date');
		} elseif ($table == 'station') {
			$fields = array('province_id','province','district','commune','address','mscmss','bsc_name','trautc','pcumfs','station_code','network','co_site','bts_name','localnumber','activitydate','activitystatus','site_id','lac','latitude','longitude','devicetype','stationtype','configuration','combine','typestation','indoormaintenance','outdoormaintenance','maintenanceby','manager','mobile','project','caremanagement','backlog','note');
		} elseif ($table == 'note') {
			$fields = array('created_by','created_time','approved_by','approved_time','note','approved');
		}
		
		if ($getFields) return $fields;
		
		return in_array($field, $fields);
	}
    
    /**
	 * Validate header of warning excel file. They have to match with field names in warning table
	 *
	 * @return	Boolean
	 */
	public function getStationConfigFields($useSql = false) {
        $app = JFactory::getApplication();
        $comParams = $app->getParams('com_bts')->toArray();
        $selectedFields = array();
        foreach ($comParams as $key => $val) {
            if (strpos($key, 'station_info_') !== false && $val) {
                $field = str_replace('station_info_','',$key);
                $selectedFields[] = ($useSql) ? 'a.'.$field : $field;
            }
        }
        return $selectedFields;
    }
	
	/**
	 * return state buttons; $task[0]: unpublish; $task[1]: publish; 
	 *
	 * @return	String
	 */
	public static function btnState($prefix = '', $tasks, $alts, $actions, $value, $i, $disable = false, $img1 = 'upgrade_tick.png', $img0 = 'upgrade_cross.png' )  {

		$img = $value ? $img1 : $img0;
		$task = $value ? $tasks[0] : $tasks[1];
		$alt = $value ? $alts[1] : $alts[0];
		$action = $value ? $actions[0] : $actions[1];
		
		if ($disable) {
			return JHtml::_('image', 'admin/' . $img, $alt, null, true);
		} else {
			return '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">'
			. JHtml::_('image', 'admin/' . $img, $alt, null, true) . '</a>';
		}
	}
	
	/**
	 * return text of warning level 
	 *
	 * @return	String, Array
	 */
	public static function getWarningLevel($key=null) {
		$warningLevelText = array(
			0 => JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_NORMAL'),
			1 => JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_WARNING'),
			2 => JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_DANGE'),
			3 => JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_VERY_DANGE')
		);
		if ($key === '') return '';
		if ($key !== null) return $warningLevelText[$key];
			else return $warningLevelText;
	}
}
