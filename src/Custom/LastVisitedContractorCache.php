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

use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Class LastVisitedContractorCache
 */
class LastVisitedContractorCache
{
    /**
     * @param int    $userId
     * @param string $contractorDescription
     * @param int    $contractorId
     * @param string $contractorName
     */
    public static function set(int $userId, string $contractorDescription, int $contractorId, string $contractorName, string $contractorCountry)
    {
        $cache = new FilesystemCache();

        switch ($contractorDescription) {
            case 'Клиенты':
                $contractorDescription = 'customers';
                break;
            case 'Поставщики':
                $contractorDescription = 'suppliers';
                break;
        }

        $newLastCustomers[0]['id'] = $contractorId;
        $newLastCustomers[0]['name'] = $contractorName;
        $newLastCustomers[0]['country'] = $contractorCountry;

        //$cache->set('last.'.$contractorDescription.'.'.$userId, $newLastCustomers);
        //если данного кэша не существует
        if (!$cache->has('last.'.$contractorDescription.'.'.$userId)) {
            $cache->set('last.'.$contractorDescription.'.'.$userId, $newLastCustomers);
        } else {
            $lastCustomers = $cache->get('last.'.$contractorDescription.'.'.$userId);
            $counter = 0;
            foreach ($lastCustomers as $key => $value) {
                if ($counter < 5 && $value['id'] !== $newLastCustomers[0]['id']) {
                    $newLastCustomers[$counter+1]['id'] = $value['id'];
                    $newLastCustomers[$counter+1]['name'] = $value['name'];
                    $newLastCustomers[$counter+1]['country'] = $value['country'];
                }
                $counter++;
            }
            $cache->set('last.'.$contractorDescription.'.'.$userId, $newLastCustomers);
        }
    }

    public static function get(string $contractorDescription, int $userId)
    {
        $cache = new FilesystemCache();

        return $cache->get('last.'.$contractorDescription.'.'.$userId);
    }
}
