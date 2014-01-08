<?php

/**
 * @version     1.0.1
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
class BtsModelWarnings extends JModelList {

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

        
		if(empty($ordering)) {
			$ordering = 'a.ordering';
		}

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
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
		$query->select("DATE_FORMAT(a.maintenance_time,'%d-%m-%Y %H:%i') AS maintenance_time");
		$query->select("DATE_FORMAT(a.approve_time,'%d-%m-%Y %H:%i') AS approve_time");

        $query->from('`#__bts_warning` AS a');
        
        $station_id = JFactory::getApplication()->input->get('station_id');
		if ($station_id!=0) {
			$query->where("a.station_id = '".$station_id."'");
		}

        
		// Join over the users for the checked out user.
		// $query->select('uc.name AS editor');
		// $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		
		// Join over the created by field 'maintenance_by'
		$query->select('maintenance_by.name AS maintenance_by');
		$query->join('LEFT', '#__users AS maintenance_by ON maintenance_by.id = a.maintenance_by');
		
		// Join over the created by field 'approve_by'
		$query->select('approve_by.name AS approve_by');
		$query->join('LEFT', '#__users AS approve_by ON approve_by.id = a.approve_by');
		
		// Join over the foreign key 'station_id'
		$query->select('#__bts_station_986074.bts_name AS bts_name');
		$query->join('LEFT', '#__bts_station AS #__bts_station_986074 ON #__bts_station_986074.id = a.station_id');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.warning_description LIKE '.$search.' )');
            }
        }

        

		//Filtering station_id
		$filter_station_id = $this->state->get("filter.station_id");
		if ($filter_station_id) {
			$query->where("a.station_id = '".$filter_station_id."'");
		}

		//Filtering maintenance_by
		$filter_maintenance_by = $this->state->get("filter.maintenance_by");
		if ($filter_maintenance_by) {
			$query->where("a.maintenance_by = '".$filter_maintenance_by."'");
		}

		//Filtering maintenance_time

		//Filtering approve_by
		$filter_approve_by = $this->state->get("filter.approve_by");
		if ($filter_approve_by) {
			$query->where("a.approve_by = '".$filter_approve_by."'");
		}

		//Filtering approve_time

        return $query;
    }

    public function getItems() {
        return parent::getItems();
    }

}
