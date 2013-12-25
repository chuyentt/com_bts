<?php
/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Station controller class.
 */
class BtsControllerStation extends BtsController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function edit()
	{
		$app			= JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_bts.edit.station.id');
		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_bts.edit.station.id', $editId);

		// Get the model.
		$model = $this->getModel('Station', 'BtsModel');

		// Check out the item
		if ($editId) {
            $model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId) {
            $model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_bts&view=stationform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Station', 'BtsModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		$ajax = $data['ajax'];
		$ajaxMsg = '';
		
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			if (!$ajax) return false; else $ajaxMsg = $model->getError();
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();
			$errorMsg = array();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'station');
					$errorMsg[] = $errors[$i]->getMessage();
				} else {
					$app->enqueueMessage($errors[$i], 'station');
					$errorMsg[] = $errors[$i];
				}
			}

			// Save the data in the session.
			$app->setUserState('com_bts.edit.station.data', JRequest::getVar('jform'),array());

			if (!$ajax) {
				// Redirect back to the edit screen.
				$id = (int) $app->getUserState('com_bts.edit.station.id');
				$this->setRedirect(JRoute::_('index.php?option=com_bts&view=station&layout=edit&id='.$id, false));
				return false;
			} else {
				$ajaxMsg = implode(';',$errorMsg);
			}
		}

		// Attempt to save the data.
		$return	= $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_bts.edit.station.data', $data);

			if (!$ajax) {
				// Redirect back to the edit screen.
				$id = (int)$app->getUserState('com_bts.edit.station.id');
				$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'station');
				$this->setRedirect(JRoute::_('index.php?option=com_bts&view=station&layout=edit&id='.$id, false));
				return false;
			} else {
				$ajaxMsg = $model->getError();
			}
		}
            
        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }
        
        // Clear the profile id from the session.
        $app->setUserState('com_bts.edit.station.id', null);

        if (!$ajax) {
	        // Redirect to the list screen.
	        $this->setMessage(JText::_('COM_BTS_ITEM_SAVED_SUCCESSFULLY'));
	        $menu = & JSite::getMenu();
	        $item = $menu->getActive();
	        $this->setRedirect(JRoute::_($item->link, false));
	
			// Flush the data from the session.
			$app->setUserState('com_bts.edit.station.data', null);
		} else {
		
			// Flush the data from the session.
			$app->setUserState('com_bts.edit.station.data', null);
			
			$status = (!$ajaxMsg) ? true : false;
			echo json_encode(
				array(
					'status'	=> $status,
					'message'	=> $ajaxMsg
				)
			);
			jexit();
		}
	}
      
    function cancel() {
		$menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));
    }
    
	public function remove()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Station', 'BtsModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_bts.edit.station.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_bts.edit.station.id');
			$this->setRedirect(JRoute::_('index.php?option=com_bts&view=station&layout=edit&id='.$id, false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->delete($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_bts.edit.station.data', $data);

			// Redirect back to the edit screen.
			$id = (int)$app->getUserState('com_bts.edit.station.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_bts&view=station&layout=edit&id='.$id, false));
			return false;
		}

            
        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }
        
        // Clear the profile id from the session.
        $app->setUserState('com_bts.edit.station.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_BTS_ITEM_DELETED_SUCCESSFULLY'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));

		// Flush the data from the session.
		$app->setUserState('com_bts.edit.station.data', null);
	}
    
    
}