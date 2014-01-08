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
class BtsModelStatus extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'warning.id',
                'station_id', 'warning.station_id',
                'ordering', 'warning.ordering',
                'state', 'warning.state',
                'level', 'warning.level',
                'warning_description', 'warning.warning_description',
                'vnp', 'warning.vnp',
                'station', 'warning.station',
                'bsc_name', 'warning.bsc_name',
                'bts_no', 'warning.bts_no',
                'devices', 'warning.devices',
                'opto', 'warning.opto',
                'warning_time', 'warning.warning_time',
                'warning_days', 'warning.warning_days',
                'maintenance_time', 'warning.maintenance_time',
                'maintenance_by', 'warning.maintenance_by',
                'approve_time', 'warning.approve_time',
                'approve_by', 'warning.approve_by',
                'created_by', 'warning.created_by',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering level
		$this->setState('filter.level', $app->getUserStateFromRequest($this->context.'.filter.level', 'filter_level', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_bts');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('warning.station_id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
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
        $query->select('DISTINCT station.id, station.bts_name, warning.station_id, warning.level');
        $query->from('`#__bts_warning` AS warning');

        
		// Join over the users for the checked out user.
		// $query->select('uc.name AS editor');
		// $query->join('LEFT', '#__users AS uc ON uc.id=warning.checked_out');
    
		// Join over the foreign key 'station_id'
		$query->join('LEFT', '#__bts_station AS station ON warning.station_id = station.id');
		
		// Join over the user field 'maintenance_by'
		// $query->select('maintenance_by.name AS maintenance_by');
		// $query->join('LEFT', '#__users AS maintenance_by ON maintenance_by.id = warning.maintenance_by');
		// Join over the user field 'approve_by'
		// $query->select('approve_by.name AS approve_by');
		// $query->join('LEFT', '#__users AS approve_by ON approve_by.id = warning.approve_by');
		// Join over the user field 'created_by'
		// $query->select('created_by.name AS created_by');
		// $query->join('LEFT', '#__users AS created_by ON created_by.id = warning.created_by');
		
		// get max warning code
		$query->where(
			'warning.level = (
				SELECT MAX(w.level)
				FROM #__bts_warning AS w
				WHERE w.station_id = station.id
			)'
		);
		$query->where(
			"warning.warning_description != ''"
		);
		// $query->where(
			// 'warning.id = (
				// SELECT MAX(w.id)
				// FROM vnp1_bts_warning AS w
				// WHERE w.station_id = station.id
			// )'
		// );
        
		
		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('warning.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(warning.state IN (0, 1))');
		}
    

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('station.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( station.bts_name LIKE '.$search.' )');
			}
		}

		//Filtering level
		$filter_level = $this->state->get("filter.level");
		if ($filter_level) {
			$query->where("warning.level = '".$db->escape($filter_level)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }
		
        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->station_id)) {
				$values = explode(',', $oneItem->station_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('id')
							->from('`#__bts_station`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->id;
					}
				}

			$oneItem->station_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->station_id;

			}
		}
        return $items;
    }

}
