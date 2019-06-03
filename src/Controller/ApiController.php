<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller;

use App\Custom\FileHelper;
use App\Entity\Country;
use App\Repository\ContractorRepository;
use App\Repository\ContractorTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 *
 * @IsGranted("ROLE_USER")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/api/search/contractors", name="api_search_contractors")
     */
    public function apiSearchCustomers(ContractorRepository $contractorRepository, Request $request, ContractorTypeRepository $contractorTypeRepository)
    {
        switch ($request->query->get('contractorType')) {
            case 'customer':
                $contractorDescription = 'Клиенты';
                break;
            case 'supplier':
                $contractorDescription = 'Поставщики';
                break;
            default:
                $contractorDescription = 'Клиенты';
        }
        $contractorType = $contractorTypeRepository->findOneBy(['description' => $contractorDescription]);
        $contractors = $contractorRepository->findAllMatching(
            $request->query->get('query'),
            $contractorType
        );

        return $this->json([
            'contractors' =>  $contractors,
        ], 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/api/return/{offerOrTender}/file/{fileName}", name="return_file")
     */
    public function apiReturnFile(String $fileName, $attachOfferDir, $attachTenderDir, $offerOrTender)
    {
        switch ($offerOrTender) {
            case 'offer':
                return FileHelper::getAndRenameFile($fileName, $attachOfferDir);
            case 'tender':
                return FileHelper::getAndRenameFile($fileName, $attachTenderDir);
            default:
                return FileHelper::getAndRenameFile($fileName, $attachOfferDir);
        }
    }

    /**
     * @Route("/api/get/customers/by/country/{id}", name="get_customers_by_country")
     */
    public function apiGetCustomers(Country $country, ContractorRepository $contractorRepository)
    {
        $contractors = $contractorRepository->findAllCustomersByCountry($country);

        return $this->json([
            'contractors' =>  $contractors,
        ], 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/api/get/suppliers/by/country/{id}", name="get_suppliers_by_country")
     */
    public function apiGetSuppliers(Country $country, ContractorRepository $contractorRepository)
    {
        $contractors = $contractorRepository->findAllSuppliersByCountry($country);

        return $this->json([
            'contractors' =>  $contractors,
        ], 200, [], ['groups' => ['main']]);
    }
}
