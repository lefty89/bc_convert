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
	 * show action
	 */
	public function showAction() {

		$this->addResources();

		$this->view->assign('files', $this->fileRepository->findByComplete(true));
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

		// add inline blobby config
		$pr->addJsInlineCode("TYPO3_BCCONVERT_VARS", $this->getInlineConfig());

		// required javascript files
		$pr->addJsFooterFile($extPath.'js/ui.js');
		$pr->addJsFooterFile($extPath.'js/main.js');
		$pr->addJsFooterFile($extPath.'js/circle-progress.js');
	}

	/**
	 * generate inline config
	 * @return string
	 */
	private function getInlineConfig()
	{
		/** @var string $resPath */
		$resPath = '/'.ExtensionManagementUtility::siteRelPath("bc_convert").'Resources/Public/';

		$config = array(
			"CHUNK_URL" => "index.php?&type=165237&tx_bcconvert_file[action]=uploadChunk&tx_bcconvert_file[controller]=File",
			"MANIFEST_URL" => "index.php?&type=165237&tx_bcconvert_file[action]=uploadManifest&tx_bcconvert_file[controller]=File",
			"WORKER_JS" => $resPath.'js/blob_worker.js',
		);

		return "TYPO3_BCCONVERT = " . json_encode($config);
	}
}