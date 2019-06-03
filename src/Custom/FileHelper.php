<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Custom;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class FileHelper
 */
class FileHelper
{
    /**
     * @param array  $uploadedFilesArray
     * @param string $attachDir
     *
     * @return array
     */
    public static function save(array $uploadedFilesArray, string $attachDir)
    {
        $attach = [];
        foreach ($uploadedFilesArray as $key => $valueAsArray) {
            foreach ($valueAsArray as $value) {
                if ($value instanceof UploadedFile) {
                    /**@Var UploadedFile $uploadedFile*/
                    $uploadedFile = $value;

                    if ($uploadedFile->getSize() < $uploadedFile::getMaxFilesize()) {
                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
						$ext = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);
                        $newFilename = $originalFilename.'_'.uniqid().'.'.$ext;
                        $uploadedFile->move($attachDir, $newFilename);

                        $attach[] = $newFilename;
                    }
                }
            }
        }

        return $attach;
    }

    /**
     * @param String $fileName
     * @param String $attachDir
     *
     * @return BinaryFileResponse
     */
    public static function getAndRenameFile(String $fileName, String $attachDir)
    {
        $response = new BinaryFileResponse($attachDir.'/'.$fileName);

        return $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            preg_replace("/_.*\./", '.', $fileName)
        );
    }
}