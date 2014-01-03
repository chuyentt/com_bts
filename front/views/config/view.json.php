<?php

/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class BtsViewConfig extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= JFactory::getApplication();
        $user		= JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_bts');
   		

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_bts');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        
		header('Content-type: application/json');
		echo json_encode($this->item);
		JFactory::getApplication()->close();
	}	
}
