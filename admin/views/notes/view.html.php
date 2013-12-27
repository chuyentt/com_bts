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
 * View class for a list of Bts.
 */
class BtsViewNotes extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		BtsHelper::addSubmenu('notes');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/bts.php';

		$state	= $this->get('State');
		$canDo	= BtsHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_BTS_TITLE_NOTES'), 'notes.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/note';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('note.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('note.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('notes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('notes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'notes.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('notes.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('notes.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'notes.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('notes.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_bts');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_bts&view=notes');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
        //Filter for the field ".station_id;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_bts.note', 'note');

        $field = $form->getField('station_id');

        $query = $form->getFieldAttribute('station_id','query');
        $translate = $form->getFieldAttribute('station_id','translate');
        $key = $form->getFieldAttribute('station_id','key_field');
        $value = $form->getFieldAttribute('station_id','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Tên BTS',
            'filter_station_id',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.station_id')),
            true
        );
		//Filter for the field created_by
		$this->extra_sidebar .= '<small><label for="filter_created_by">Người tạo</label></small>';
		$this->extra_sidebar .= JHtmlList::users('filter_created_by', $this->state->get('filter.created_by'), 1, 'onchange="this.form.submit();"');
		//Filter for the field approved_by
		$this->extra_sidebar .= '<small><label for="filter_approved_by">Người xác nhận</label></small>';
		$this->extra_sidebar .= JHtmlList::users('filter_approved_by', $this->state->get('filter.approved_by'), 1, 'onchange="this.form.submit();"');
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.station_id' => JText::_('COM_BTS_NOTES_STATION_ID'),
		'a.note' => JText::_('COM_BTS_NOTES_NOTE'),
		'a.created_by' => JText::_('COM_BTS_NOTES_CREATED_BY'),
		'a.created_time' => JText::_('COM_BTS_NOTES_CREATED_TIME'),
		'a.approved' => JText::_('COM_BTS_NOTES_APPROVED'),
		'a.approved_by' => JText::_('COM_BTS_NOTES_APPROVED_BY'),
		'a.approved_time' => JText::_('COM_BTS_NOTES_APPROVED_TIME'),
		);
	}

    
}
