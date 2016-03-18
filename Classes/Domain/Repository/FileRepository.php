<?php

namespace BC\BcConvert\Domain\Repository;

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

use BC\BcConvert\Domain\Model\File;
use BC\BcConvert\Utility\ConvertUtility;
use BC\BcConvert\Utility\FileUtility;
use BC\BcConvert\Utility\ValidationUtility;
use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class FileRepository extends Repository
{
    /**
     * @param \BC\BcConvert\Domain\Model\Queue $queue
     * @return boolean
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function createFromQueue($queue)
    {
        /** @var string $completePath */
        $completePath = GeneralUtility::getFileAbsFileName($queue->getPath());

        if (is_file($completePath)) {

            // move and rename file
            $hash = sha1_file($completePath);

            $file = new File();
            $file->setHash($hash);

            /** @var string $filename */
            $filename = pathinfo($queue->getFile()->getName(), PATHINFO_FILENAME) . "." . $queue->getFormat();

            $file->setPid($queue->getFile()->getPid());
            $file->setName($filename);
            $file->setSize(filesize($completePath));
            $file->setComplete(true);

            // get file mime
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $completePath);
            finfo_close($finfo);

            $file->setMime($mime);
            $file->setPath($queue->getPath());

            $mirror = $queue->getFile()->getMirror() ?: $queue->getFile()->getUid();
            $file->setMirror($mirror);
            $this->add($file);

            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @param \BC\BcConvert\Domain\Model\File $file
     */
    private function fillMessage(&$data, $file)
    {
        $data = array_merge($data, array(
            'link' => $file->getPath(),
            'cable' => ConvertUtility::isConvertable($file),
            'list' => array()
        ));

    }

    /**
     * @param string $path
     * @param string $hash
     * @param Object $manifest
     * @return \BC\BcConvert\Domain\Model\File
     */
    private function createFileDbEntry($path, $hash, $manifest)
    {
        $file = new File();
        $file->setHash($hash);
        $file->setName($manifest->name);
        $file->setSize($manifest->size);
        $file->setMime($manifest->mime);
        $file->setPath($path);
        $this->add($file);

        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->persistAll();

        return $file;
    }

    /**
     * @param array $data
     * @return boolean
     * @throws \Exception
     */
    public function createManifest(&$data)
    {
        try {
            /** @var Object $manifest */
            $manifest = FileUtility::getManifest();

            /** @var string $hash */
            $hash = ValidationUtility::getFileHash();

            /** @var \BC\BcConvert\Domain\Model\File $file */
            $file = $this->findOneByHash($hash);

            if ($file === null) {
                // create a new file on the file system
                $path = FileUtility::createPersistentFile($manifest);
                // create a new file in the typo3 db
                $this->createFileDbEntry($path, $hash, $manifest);
                $data['chunks'] = $manifest->chunks;
            } else {
                if (!$file->isComplete()) {
                    /** @var Object $fileManifest */
                    $fileManifest = FileUtility::readFileManifest($file);
                    $data['chunks'] = array_merge(array_diff($fileManifest->chunks, array(FileUtility::COMPLETE_HASH)));
                } else {
                    $this->fillMessage($data, $file);
                }
            }
        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), true));

            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @return boolean
     */
    public function addChunkToFile(&$data)
    {
        try {
            /** @var string $hash */
            $hash = ValidationUtility::getFileHash();

            /** @var \BC\BcConvert\Domain\Model\File $file */
            $file = $this->findOneByHash($hash);

            if ($file !== null) {
                /** @var Object $fileManifest */
                $fileManifest = FileUtility::addChunkToExistingFile($file);
                /** @var array $chunks */
                $chunks = $data['chunks'] = array_merge(array_diff($fileManifest->chunks,
                    array(FileUtility::COMPLETE_HASH)));

                if (!count($chunks)) {
                    // complete file upload and update path
                    if (FileUtility::completeFile($hash, $file)) {
                        $this->fillMessage($data, $file);
                        $file->setMirror($file->getUid());
                        $this->update($file);
                    }
                }
            } else {
                throw new Exception('File was not found');
            }
        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), true));

            return false;
        }

        return true;
    }

    /**
     * @param \BC\BcConvert\Domain\Model\File $file
     * @return array
     */
    public function getMirrorList($file)
    {
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            "name, path",
            "tx_bcconvert_domain_model_file",
            sprintf("mirror = %d AND uid != %d",
                $file->getMirror(),
                $file->getUid()),
            "",
            "",
            ""
        );

        return $result;
    }
}