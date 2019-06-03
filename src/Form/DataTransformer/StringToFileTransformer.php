<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class StringToFileTransformer
 */
class StringToFileTransformer implements DataTransformerInterface
{
    private $projectDir;

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function transform($offer)
    {
        if (null === $offer) {
            return '';
        }
        //if (!$value instanceof Fil) {
        //    throw new \LogicException('The UserSelectTextType can only be used with User objects');
        //}

        //dd($offer->getAttach());
        $files = [];
        foreach ($offer->getAttach() as $value) {
            //dd(new File($this->projectDir.$value));
            $files[] = new File($this->projectDir.$value);
        }
        //dd($offer);
        $offer->setAttach($files);

        return $offer;
    }

    public function reverseTransform($offer)
    {
        $files = $offer->getAttach();
        dd($files);
        //dd('reverse transform', $value);
    }
}