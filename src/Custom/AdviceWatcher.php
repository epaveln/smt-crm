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

use App\Entity\AdviceSettings;

/**
 * Class AdviceWatcher
 */
class AdviceWatcher
{
    private $charactersToBeDeleted;
    private $adviceSettings;
    private $urlPattern;
    private $tags;

    public function __construct(AdviceSettings $adviceSettings, array $tags)
    {
        $urlPattern = str_replace('/', '\/', $adviceSettings->getItemUrl());
        $this->adviceSettings = $adviceSettings;
        $this->urlPattern = '/<a href="'.$urlPattern.'(\d+)">(.*?)<\/a>/s';
        $this->charactersToBeDeleted = ["\r\n", "\t"];
        $this->tags = $tags;
    }

    public function getAdviceTenders()
    {
        $tenders = [];

        for ($i = 1; $i <= $this->adviceSettings->getPages(); $i++) {

            $pageContent = $this->getPageContent($i);
            $tenders = $this->getAllTendersFromPageContent($pageContent, $tenders);
        }

        return $this->compareWithTags($tenders);
    }

    private function getPageContent(int $pageNumber): string
    {
        if (1 === $pageNumber) {
            $url = $this->adviceSettings->getSearchUrl();
        } else {
            $url = $this->adviceSettings->getSearchUrl().'&p='.$pageNumber;
        }

        $ch = curl_init($url);
        $headers = explode("\r\n", $this->adviceSettings->getRequestHeaders());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    private function getAllTendersFromPageContent(string $pageContent, array $tenders): array
    {
        preg_match_all($this->urlPattern, $pageContent, $result);

        $arrayIdTitle = [];

        for ($i = 0; $i < count($result[0]); $i++) {
            $arrayIdTitle[$i]['id'] = $result[1][$i];
            $arrayIdTitle[$i]['title'] = str_replace($this->charactersToBeDeleted, '', $result[2][$i]);
        }

        return array_merge($tenders, $arrayIdTitle);
    }

    private function compareWithTags(array $tenders)
    {
        $matches = [];

        foreach ($tenders as $tenderKey => $tenderValue) {
            foreach ($this->tags as $tagValue) {
                if (preg_match('/(?:^|\s)('.$tagValue.')/m', mb_strtolower($tenderValue['title']))) {
                    $matches[$tenderValue['id']] = $tenderValue['title'];
                    break;
                }
            }
        }

        return $matches;
    }
}
