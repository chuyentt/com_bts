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
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'station_id', 'a.station_id',
                'note', 'a.note',
                'created_by', 'a.created_by',
                'created_time', 'a.created_time',
                'approved', 'a.approved',
                'approved_by', 'a.approved_by',
                'approved_time', 'a.approved_time',

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

        
		//Filtering station_id
		$this->setState('filter.station_id', $app->getUserStateFromRequest($this->context.'.filter.station_id', 'filter_station_id', '', 'string'));

		//Filtering created_by
		$this->setState('filter.created_by', $app->getUserStateFromRequest($this->context.'.filter.created_by', 'filter_created_by', '', 'string'));

		//Filtering approved_by
		$this->setState('filter.approved_by', $app->getUserStateFromRequest($this->context.'.filter.approved_by', 'filter_approved_by', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_bts');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.station_id', 'asc');
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
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__bts_note` AS a');

        
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the foreign key 'station_id'
		$query->select('#__bts_station_991636.bts_name AS stations_bts_name_991636');
		$query->join('LEFT', '#__bts_station AS #__bts_station_991636 ON #__bts_station_991636.id = a.station_id');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the user field 'approved_by'
		$query->select('approved_by.name AS approved_by');
		$query->join('LEFT', '#__users AS approved_by ON approved_by.id = a.approved_by');

        
    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
        $query->where('a.state = '.(int) $published);
    } else if ($published === '') {
        $query->where('(a.state IN (0, 1))');
    }
    

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.station_id LIKE '.$search.'  OR  a.note LIKE '.$search.' )');
            }
        }

        

		//Filtering station_id
		$filter_station_id = $this->state->get("filter.station_id");
		if ($filter_station_id) {
			$query->where("a.station_id = '".$db->escape($filter_station_id)."'");
		}

		//Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");
		if ($filter_created_by) {
			$query->where("a.created_by = '".$db->escape($filter_created_by)."'");
		}

		//Filtering approved_by
		$filter_approved_by = $this->state->get("filter.approved_by");
		if ($filter_approved_by) {
			$query->where("a.approved_by = '".$db->escape($filter_approved_by)."'");
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
							->select('bts_name')
							->from('`#__bts_station`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->bts_name;
					}
				}

			$oneItem->station_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->station_id;

			}
		}
        return $items;
    }

}
