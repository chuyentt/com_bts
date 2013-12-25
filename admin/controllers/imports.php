<?php
/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Imports list controller class.
 */
class BtsControllerImports extends JControllerLegacy
{
	public function import()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		if (!$_POST['file']) $this->setRedirect(JRoute::_('index.php?option=com_bts&view=imports', 'There is no file'));

		$model = $this->getModel('imports');
		if ($model->import())
		{
			// TODO: ...
		}
		
		$view = 'stations';
		if ($_POST['type'] == 'warning') $view = 'warnings';
		$redirect_url = JRoute::_('index.php?option=com_bts&view='.$view, false);
		$this->setRedirect($redirect_url);
	}
    
}