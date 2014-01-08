<?php

/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Bts records.
 */
class BtsModelMap extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('a.id, a.address, a.bts_name, LOWER(a.bts_name) AS bts_alias,  a.network, a.latitude, a.longitude, a.duplicate, a.province_id, a.bsc_name');
        
        $query->from('`#__bts_station` AS a');
		$query->where('a.state = 1');
        
		// Join over the users for the checked out user.
		// $query->select('uc.name AS editor');
		// $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over the warning table
		$query->select('warning.level');
		$query->join('LEFT', '#__bts_warning AS warning ON warning.station_id = a.id');
		
		// $query->where('warning.state = 1');
		// $query->where('warning.approve_state = 0');
        
		// remove by executing slowly
        // $query->where(
			// 'warning.level = (
				// SELECT MAX(w.level)
				// FROM #__bts_warning AS w
				// WHERE w.station_id = a.id AND w.network = a.network
			// )'
		// );
		
		$query->order('a.network, a.bts_name');
		
		// Join over the created by field 'created_by'
		// $query->select('created_by.id AS created_by');
		// $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		
		// Filter by search in title
		// $search = $this->getState('filter.search');
		// if (!empty($search)) {
			// if (stripos($search, 'id:') === 0) {
				// $query->where('a.id = ' . (int) substr($search, 3));
			// } else {
				// $search = $db->Quote('%' . $db->escape($search, true) . '%');
				
			// }
		// }
		// echo $query;
        return $query;
    }

    public function getItems() {
	
        $items = parent::getItems();
		
		// find max warning level and remove duplicated stations
		$btsByID = array();
		foreach ($items as $item) {
			if (isset($btsByID[$item->id])) {
				if ($item->level > $btsByID[$item->id]->level) $btsByID[$item->id]->level = $item->level;
			} else {
				$btsByID[$item->id] = $item;
			}
		}
		
        $groupedBTS = array();
		foreach ($btsByID as $item) {
			// $item->bts_name = strtolower($item->bts_name);
			if ($item->level == 1) {
				$item->icon_url = JURI::root().'components/com_bts/assets/images/marker_yellow.png';
			} elseif ($item->level == 2) {
				$item->icon_url = JURI::root().'components/com_bts/assets/images/marker_orange.png';
			} elseif ($item->level == 3) {
				$item->icon_url = JURI::root().'components/com_bts/assets/images/marker_red.png';
			} else {
				$item->level = 0;
				$item->icon_url = JURI::root().'components/com_bts/assets/images/marker_green.png';
			}
			
			$groupedBTS[] = $item;
            
            // regroup data by bts_alias
/*             if (isset($groupedBTS[$item->bts_alias])) {
                $stations = explode('-',$groupedBTS[$item->bts_alias]->network);
                if (!in_array($item->network, $stations)) {
                    $groupedBTS[$item->bts_alias]->network .= '-'.$item->network;
                }
				if ($item->level > $groupedBTS[$item->bts_alias]->level) $groupedBTS[$item->bts_alias]->level = $item->level;
                $groupedBTS[$item->bts_alias]->items[] = $item;
            } else {
                $obj = new stdclass;
                $obj->bts_id        = $item->id;
                $obj->address       = $item->address;
                $obj->bts_name      = $item->bts_name;
                $obj->bts_alias     = $item->bts_alias;
                $obj->latitude      = $item->latitude;
                $obj->longitude     = $item->longitude;
                $obj->level  = $item->level;
                $obj->icon_url      = $item->icon_url;
                $obj->network       = $item->network;
                $obj->items         = array($item);
                $groupedBTS[$item->bts_alias] = $obj;
            }
 */		}
        // var_dump($groupedBTS); die;
		return $groupedBTS;
    }

}
