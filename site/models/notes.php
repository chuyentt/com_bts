<?php

/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Bts records.
 */
class BtsModelNotes extends JModelList {

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
		
		$query->select("DATE_FORMAT(a.created_time, '%d-%m-%Y %H:%i:%s') AS created_time");
		$query->select("DATE_FORMAT(a.approved_time, '%d-%m-%Y %H:%i:%s') AS approved_time");
		 
        $query->from('`#__bts_note` AS a');

        $station_id = JFactory::getApplication()->input->get('station_id');
		if ($station_id!=0) {
			$query->where("a.station_id = '".$station_id."'");
		}
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the foreign key 'station_id'
		$query->select('#__bts_station_991636.bts_name AS bts_name');
		$query->join('LEFT', '#__bts_station AS #__bts_station_991636 ON #__bts_station_991636.id = a.station_id');
		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		$query->select('approved_by.name AS approved_by');
		$query->join('LEFT', '#__users AS approved_by ON approved_by.id = a.approved_by');
        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.note LIKE '.$search.' )');
            }
        }

		//Filtering station_id
		$filter_station_id = $this->state->get("filter.station_id");
		if ($filter_station_id) {
			$query->where("a.station_id = '".$filter_station_id."'");
		}

		//Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");
		if ($filter_created_by) {
			$query->where("a.created_by = '".$filter_created_by."'");
		}

		//Filtering approved_by
		$filter_approved_by = $this->state->get("filter.approved_by");
		if ($filter_approved_by) {
			$query->where("a.approved_by = '".$filter_approved_by."'");
		}

        return $query;
    }

    public function getItems() {
        return parent::getItems();
    }

}