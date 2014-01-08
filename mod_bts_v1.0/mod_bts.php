<?php
/**
 * @version     1.0.1
 * @package     mod_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
 */

defined('_JEXEC') or die;

// load language file from administrator
$language = JFactory::getLanguage();
$language->load('com_bts', JPATH_ADMINISTRATOR, null, true);

require JModuleHelper::getLayoutPath('mod_bts', $params->get('layout', 'default'));