<?php

namespace BC\BcConvert\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (C) 2016 Lefty (fb.lefty@web.de)
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this script. If not, see <http://www.gnu.org/licenses/>.
 *
 ***************************************************************/

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * Class InfoController
 * @package BC\BcMediastream\Controller
 */
class InfoController extends ActionController
{
    /**
     * show action
     */
    public function showAction()
    {
        $this->addResources();
    }

    /**
     * adds required resources (js/css)
     */
    private function addResources()
    {
        /** @var string $extPath */
        $extPath = ExtensionManagementUtility::siteRelPath("bc_convert") . 'Resources/Public/';

        /** @var \TYPO3\CMS\Core\Page\PageRenderer $pr */
        $pr = $GLOBALS['TSFE']->getPageRenderer();

        // required css files
        $pr->addCssFile($extPath . 'css/style.css');

        // add inline blobby config
        $pr->addJsInlineCode("TYPO3_BCCONVERT_VARS", $this->getInlineConfig());

        // required javascript files
        $pr->addJsFooterFile($extPath . 'js/ui.js');
        $pr->addJsFooterFile($extPath . 'js/main.js');
        $pr->addJsFooterFile($extPath . 'js/circle-progress.js');
    }

    /**
     * generate inline config
     * @return string
     */
    private function getInlineConfig()
    {
        /** @var string $resPath */
        $resPath = '/' . ExtensionManagementUtility::siteRelPath("bc_convert") . 'Resources/Public/';

        $config = array(
            "CHUNK_URL" => "index.php?&type=165237&tx_bcconvert_file[action]=uploadChunk&tx_bcconvert_file[controller]=File",
            "MANIFEST_URL" => "index.php?&type=165237&tx_bcconvert_file[action]=uploadManifest&tx_bcconvert_file[controller]=File",
            "WORKER_JS" => $resPath . 'js/blob_worker.js',
        );

        return "TYPO3_BCCONVERT = " . json_encode($config);
    }
}