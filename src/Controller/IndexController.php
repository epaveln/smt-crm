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

use App\Custom\LastVisitedContractorCache;
use App\Form\SelectContractorFormType;
use App\Repository\TenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('base.html.twig', [
            'info' => 'Стартовая страница',
        ]);
    }

    /**
     * @Route("/user/index/customers", name="index_customers")
     */
    public function indexCustomers()
    {
        $form = $this->createForm(SelectContractorFormType::class);

        return $this->render('user/index_customers.html.twig', [
            'customers' => true,
            'lastCustomers' => LastVisitedContractorCache::get('customers', $this->getUser()->getId()),
            'selectCustomerForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/index/suppliers", name="index_suppliers")
     */
    public function indexSuppliers()
    {
        $form = $this->createForm(SelectContractorFormType::class);

        return $this->render('user/index_suppliers.html.twig', [
            'suppliers' => true,
            'lastSuppliers' => LastVisitedContractorCache::get('suppliers', $this->getUser()->getId()),
            'selectSupplierForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/index/tenders", name="index_tenders")
     */
    public function indexTenders(TenderRepository $tenderRepository)
    {
        $tenders = $tenderRepository->findBy([], ['id' => 'DESC'], 7);

        //dump($tenders);
        //$form = $this->createForm(SelectContractorFormType::class);

        return $this->render('user/index_tenders.html.twig', [
            'tenders' => $tenders,
        ]);
    }
}
