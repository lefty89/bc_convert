<?php

namespace BC\BcConvert\Controller;

/**
 *
 * User: Lefty
 * Date: 31.01.2015
 * Time: 13:21
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use BC\BcConvert\Domain\Model\Queue;
use BC\BcConvert\Utility\StandaloneUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * Class InfoController
 * @package BC\BcMediastream\Controller
 */
class InfoController extends ActionController {

	/**
	 * fileRepository
	 *
	 * @var \BC\BcConvert\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * queueRepository
	 *
	 * @var \BC\BcConvert\Domain\Repository\QueueRepository
	 * @inject
	 */
	protected $queueRepository;

	/**
	 * show action
	 */
	public function showAction() {

		$this->addResources();

		$this->view->assign('files', $this->fileRepository->findByComplete(true));
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\File $file
	 */
	public function prepareAction($file) {

		// renders the html for convert window
		$html = StandaloneUtility::renderStandaloneView('EXT:bc_convert/Resources/Private/Standalone/Prepare.html', array(
			'queue' => new Queue(),
			'file' => $file,
			'audioBitrates' => array(
				100 => 'low',
				200 => 'medium'
			),
			'videoBitrates' => array(
				100 => 'low',
				200 => 'medium'
			),
		));

		$this->view->assign('html', $html);
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 */
	public function convertAction($queue) {
		// set convert time to now
		$queue->setTime(date_create());
		$this->queueRepository->add($queue);
	}

	/**
	 * adds required resources (js/css)
	 */
	private function addResources()
	{
		/** @var string $extPath */
		$extPath = ExtensionManagementUtility::siteRelPath("bc_convert").'Resources/Public/';

		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pr */
		$pr = $GLOBALS['TSFE']->getPageRenderer();

		// required css files
		$pr->addCssFile($extPath.'css/style.css');

		// required javascript files
		$pr->addJsFooterFile($extPath.'js/ui.js');
		$pr->addJsFooterFile($extPath.'js/main.js');
		$pr->addJsFooterFile($extPath.'js/circle-progress.js');
	}
}