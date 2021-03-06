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

JHTML::_('behavior.tooltip');
jimport('joomla.filter.output');
JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
// Joomla.submitbutton('lists.synchronize')

$document = & JFactory::getDocument();
$overwrite = 'Joomla.submitbutton = function(pressbutton){
        Check = confirm("' . JText::_("COM_CMC_SYNCRONIZE_CONFIRM") . '");

        if(Check == false){
            return;
        }

        document.adminForm.task.value=pressbutton;
        submitform(pressbutton);
    }';
$document->addScriptDeclaration($overwrite);

CmcHelperBasic::bootstrap();
JHTML::_('stylesheet', 'media/com_cmc/css/strapper.css');

?>
<div class="compojoom-bootstrap">
    <form action="<?php echo JRoute::_('index.php?option=com_cmc&view=lists'); ?>" method="post" name="adminForm">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search fltlft btn-group pull-left">
                <label class="filter-search-lbl element-invisible"
                       for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                       title="<?php echo JText::_('COM_CMC_SEARCH_IN_TITLE'); ?>"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
            </div>
            <div class="btn-group pull-left hidden-phone">
                <?php if (JVERSION > 2.5) : ?>
                <button class="btn" type="submit"><i class="icon-search"></i></button>
                <button class="btn" type="button"
                        onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i>
                </button>
                <?php else : ?>
                <button class="btn" type="submit"
                        style="margin:0"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button class="btn" type="button" style="margin:0"
                        onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

                <?php endif; ?>
            </div>

        </div>
        <div class="clr"></div>

        <table class="adminlist table">
            <thead>
            <tr>
                <th width="5"><?php echo JText::_('JGRID_HEADING_ROW_NUMBER'); ?></th>
                <!--            <th width="5">-->
                <!--                <input type="checkbox" name="checkall-toggle" value=""-->
                <!--                       title="-->
                <?php //echo JText::_('JGLOBAL_CHECK_ALL'); ?><!--" onclick="Joomla.checkAll(this)"/>-->
                <!--            </th>-->
                <th class="title">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'cc.list_name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
                <th width="10%"><?php echo JText::_('COM_CMC_MC_ID'); ?></th>
                <th width="20%"><?php echo JText::_('COM_CMC_DEFAULT_FROM_NAME'); ?></th>
                <th width="20%"><?php echo JText::_('COM_CMC_DEFAULT_FROM_MAIL'); ?></th>
                <th width="10%"><?php echo JText::_('COM_CMC_DEFAULT_LANGUAGE'); ?></th>
                <th width="5%" nowrap="nowrap"><?php echo JText::_('COM_CMC_VISIBILITY'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
            </tfoot>
            <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
            <tr class="<?php echo "row" . $i % 2; ?>">
                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                <!--            <td>-->
                <!--                --><?php //echo JHTML::_('grid.id', $i, $item->id);; ?>
                <!--            </td>-->
                <td>
                    <!--                <a href="-->
                        <?php //echo JRoute::_('index.php?option=com_cmc&task=list.edit&id=' . $item->id);; ?><!--">-->
                        <?php //echo $item->list_name; ?><!--</a>-->
                    <?php echo $item->list_name; ?>
                </td>
                <td align="center">
                    <?php echo $item->id; ?>
                </td>
                <td align="center">
                    <?php echo $item->mc_id; ?>
                </td>
                <td align="center">
                    <?php echo $item->default_from_name; ?>
                </td>
                <td align="center">
                    <?php echo $item->default_from_email; ?>
                </td>
                <td>
                    <?php echo $item->default_language; ?>
                </td>
                <td align="center">
                    <?php echo $item->visibility; ?>
                </td>
            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>

        <?php echo JHTML::_('form.token'); ?>
    </form>

    <div class="clear"></div>
    <?php echo CmcHelperBasic::footer(); ?>
</div>