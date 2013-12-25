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
class BtsModelDuplicate extends JModelLegacy {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_BTS';
	public $_file = array();
	public $_type = '';
	public $_error = array();
	
	
	public function run() {
		$db = JFactory::getDbo();
		
		// get data of stations
		$query = "SELECT id, bts_name, LOWER(bts_name) AS bts_alias, latitude, longitude FROM #__bts_station";
		$db->setQuery($query);
		$data = $db->loadObjectList();
		
		$btsByCoordinates = array();
		foreach ($data as $item) {
			$lat = round($item->latitude, 3);
			$long = round($item->longitude, 3);
			$btsByCoordinates[$lat.'-'.$long][] = $item->id;
		}
		
		foreach ($btsByCoordinates as $items) {
			if (count($items) == 2) {
				$query = "UPDATE  `#__bts_station` SET  `duplicate` =  '".$items[0]."' WHERE  `id` = ".$items[1];
				$db->setQuery($query);
				$db->query();
				
				$query = "UPDATE  `#__bts_station` SET  `duplicate` =  '".$items[1]."' WHERE  `id` = ".$items[0];
				$db->setQuery($query);
				$db->query();
			}
		}
		
		return true;
	}
}
