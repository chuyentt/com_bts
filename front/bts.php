<?php
/**
 * @version     1.0.1
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// load language file from administrator
$language = JFactory::getLanguage();
$language->load('com_bts', JPATH_ADMINISTRATOR, null, true);

// Include helper class
require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'bts.php';

//Remote login
$username = JRequest::getVar('username', 1);
$password = JRequest::getVar('password', 1);
$checking = JRequest::getVar('checking', 2);
if ($username != 1 && $password != 1 && $checking == 1) {
    JFactory::getApplication()->login(array('username'=>$username,'password'=>$password),array('remember'=>true));
    $canEdit = JFactory::getUser()->authorise('core.edit', 'com_bts');
    if($canEdit):
        $json = json_encode(JFactory::getUser());
        $jsonData = json_decode($json,true);
        $jsonData['token'] = JSession::getFormToken();
        header('Content-type: application/json');
		echo json_encode($jsonData);
		JFactory::getApplication()->close();
    endif;
}

// Execute the task.
$controller	= JControllerLegacy::getInstance('Bts');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
