<?php
/**
 * @category   WBS
 * @package    Wbs_Reindexer
 * @author     WebShouters Team
 * @copyright  Copyright (c) 2017-2018 WebShouters. ( https://www.webshouters.com )
 * @license    https://www.webshouters.com//Wbs-Commerce-License.txt
 */
namespace Wbs\Reindexer\Controller\Adminhtml\Indexer;

class massReindexData extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        if ($this->_request->getActionName() == 'massReindexData') {
            return $this->_authorization->isAllowed('Wbs_Reindexer::reindexdata');
        }
        return false;
    }
    
	public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
        	$startTime = microtime(true);
            foreach ($indexerIds as $indexerId) {
            	try {
                    $indexer = $this->_objectManager->get('Magento\Framework\Indexer\IndexerRegistry')->get($indexerId);
                    $indexer->reindexAll();
                    $resultTime = microtime(true) - $startTime;
                    $this->messageManager->addSuccess(
	                    '<div class="wbs-reindexer-info">' . $indexer->getTitle() . ' index has been rebuilt successfully in ' . gmdate('H:i:s', $resultTime) . '</div>'
	                );
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
	                $this->messageManager->addError(
                        $indexer->getTitle() . ' indexer process unknown error:',
                        $e
                    );
	            } catch (\Exception $e) {
                    $this->messageManager->addException(
                        $e,
                        __("We couldn't reindex data because of an error.")
                    );
	            }
            }
            $this->messageManager->addSuccess(
                __('%1 indexer(s) have been rebuilt successfully <a href="javascript:void(0)" class="wbs-reindexer-show">Show detail</a>', count($indexerIds))
            );
        }
        $this->_redirect('indexer/indexer/list');
    }
}
