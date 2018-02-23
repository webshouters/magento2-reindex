<?php
/**
 * @category   WBS
 * @package    Wbs_Reindexer
 * @author     WebShouters Team
 * @copyright  Copyright (c) 2017-2018 WebShouters. ( https://www.webshouters.com )
 * @license    https://www.webshouters.com//Wbs-Commerce-License.txt
 */
namespace Wbs\Reindexer\Block\Backend\Grid;

class ItemsUpdater extends \Magento\Indexer\Block\Backend\Grid\ItemsUpdater
{
    public function update($argument)
    {
        if (false === $this->authorization->isAllowed('Magento_Indexer::changeMode')) {
            unset($argument['change_mode_onthefly']);
            unset($argument['change_mode_changelog']);
        }
        if (false === $this->authorization->isAllowed('Wbs_Reindexer::reindexdata')) {
            unset($argument['change_mode_reindex']);
        }
        return $argument;
    }
}
