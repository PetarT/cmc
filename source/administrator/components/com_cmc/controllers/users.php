<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class CmcControllerUsers extends CmcController {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
        $this->registerTask('unpublish', 'publish');
        // Register Extra tasks
        $this->registerTask('addUser', 'editUser');
        $this->registerTask('apply', 'save');
    }

    /**
     * @param bool $cachable
     * @param bool $urlparams
     */
    public function display($cachable = false, $urlparams = false) {
        //die("adsf");
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'users');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Users', 'CmcModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    /**
     *
     */
    public function remove() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $db = JFactory::getDBO();
        if (count($cid)) {
            $cids = implode(',', $cid);
            // Removing it from mailchimp


            // REmoving it from DB
            $query = "DELETE FROM #__cmc_users where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->query()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
        }

        $this->setRedirect('index.php?option=com_cmc&view=users');
    }

    // Edit Gallery

    function editUser() {
        $document = JFactory::getDocument();
        $viewName = 'editUser'; // hardcoede
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);

        $model = $this->getModel('editUser');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    function save() {
        $row = JTable::getInstance('users', 'Table');
        $postgal = JRequest::get('post');

        $id = JRequest::GetVar('id', 0);

        if (!$row->bind($postgal)) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        // Updating it to mailchimp

        if (!isset($row->published)) {
            $row->published = 1;
        }

        if (!$row->store()) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        switch ($this->task) {
            case 'apply':
                $msg = JText::_('COM_CMC_LIST_APPLY');
                $link = 'index.php?option=com_cmc&controller=users&task=editUser&id=' . $row->id;
                break;

            case 'save':
            default:
                $msg = JText::_('COM_CMC_LIST_SAVE');
                $link = 'index.php?option=com_cmc&view=users';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    function cancel() {
        $link = 'index.php?option=com_cmc&view=users';
        $this->setRedirect($link);
    }

}