<?php

/**
 * @version     1.0.0
 * @package     com_vnpbtsnodeb
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class BtsViewStation extends JViewLegacy {

    protected $item;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= JFactory::getApplication();
        $user		= JFactory::getUser();
        
        $this->item = $this->get('Data');
        $confFields = BtsHelper::getStationConfigFields();
		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        // get only fields from component configuration
        $item  = array();
        foreach (get_object_vars($this->item) as $property => $value) {
            if (in_array($property, $confFields)) 
                $item[] = array(
                    'text'  => JText::_('COM_BTS_FORM_LBL_STATION_'.strtoupper($property)),
                    'value' => $value
                );
        }
        
        header('Content-type: application/json');
		echo json_encode($item);
		JFactory::getApplication()->close();
       
    }
    
}
