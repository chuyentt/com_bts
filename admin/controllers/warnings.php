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

jimport('joomla.application.component.controlleradmin');

/**
 * Warnings list controller class.
 */
class BtsControllerWarnings extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('unapprove',	'approve');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'warning', $prefix = 'BtsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	
	/**
	 * Method to toggle the Approve state of warnings
	 *
	 * @return  void
	 */
	public function approve()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user   = JFactory::getUser();
		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('approve' => 1, 'unapprove' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');
		
		// Get the model.
		$model = $this->getModel();

		// Publish the items.
		if (!$model->approve($ids, $value))
		{
			JError::raiseWarning(500, $model->getError());
		}

		$this->setRedirect('index.php?option=com_bts&view=warnings');
	}
    
    
    
}