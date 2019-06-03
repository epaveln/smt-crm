<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\User;

use App\Custom\LastVisitedContractorCache;
use App\Custom\Paginator;
use App\Entity\Contractor;
use App\Form\ContractorFormType;
use App\Repository\ContractorRepository;
use App\Repository\ContractorTypeRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserContractorController
 */
class UserContractorController extends AbstractController
{
    /**
     * @Route("/user/list/contractors/{contractorType}", name="list_contractors")
     */
    public function listContractors($contractorType, ContractorRepository $contractorRepository, PaginatorInterface $paginator, Request $request, ContractorTypeRepository $contractorTypeRepository)
    {
        if ('customers' === $contractorType) {
            $queryBuilder = $contractorRepository->findAllContractorsByTypeWithQB($contractorTypeRepository->getCustomerType());
            $pagination = Paginator::paginate($paginator, $queryBuilder, $request);

            return $this->render('user/list_contractors.html.twig', [
                'contractorType' => 'customer',
                'pagination' => $pagination,
            ]);
        } else {
            $queryBuilder = $contractorRepository->findAllContractorsByTypeWithQB($contractorTypeRepository->getSupplierType());
            $pagination = Paginator::paginate($paginator, $queryBuilder, $request);

            return $this->render('user/list_contractors.html.twig', [
                'contractorType' => 'supplier',
                'pagination' => $pagination,
            ]);


        }
    }

    /**
     * @Route("/user/show/contractor/{id}", name="show_contractor")
     */
    public function showContractor(Contractor $contractor, Request $request, OfferRepository $offerRepository, PaginatorInterface $paginator)
    {
        LastVisitedContractorCache::set(
            $this->getUser()->getId(),
            $contractor->getContractorType()->getDescription(),
            $contractor->getId(),
            $contractor->getName(),
            $contractor->getCountry()->getName()
        );

        $queryBuilder = $offerRepository->findAllOffersByContractorWithQB($contractor);
        $pagination = Paginator::paginate($paginator, $queryBuilder, $request);

        return $this->render('user/show_contractor.html.twig', [
            'contractor' => $contractor,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/user/add/contractor", name="add_contractor")
     */
    public function addContractor(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ContractorFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Contractor $contractor*/
            $contractor = $form->getData();

            $em->persist($contractor);
            $em->flush();

            $this->addFlash('success', 'Контрагент '.$contractor->getName().' успешно создан!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $contractor->getId(),
            ]);
        }

        return $this->render('user/add_contractor.html.twig', [
            'contractorForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit/contractor/{id}", name="edit_contractor")
     */
    public function editContractor(Contractor $contractor, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ContractorFormType::class, $contractor);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Contractor $contractor*/
            $contractor = $form->getData();

            $em->persist($contractor);
            $em->flush();

            $this->addFlash('success', 'Данные контрагента "'.$contractor->getName().'" успешно изменены!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $contractor->getId(),
            ]);
        }

        return $this->render('user/edit_contractor.html.twig', [
            'editContractorForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/contractors", name="delete_contractors")
     */
    public function deleteSelectedContractors(Request $request, ContractorRepository $contractorRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('contractor');
        $message = '';

        foreach ($toBeDeleted as $key => $value) {
            $contractor = $contractorRepository->findOneBy(['id' => $value]);

            if (!is_null($contractor)) {
                $message .= $value.' ';
                $em->remove($contractor);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Контрагенты №'.$message.' БЫЛИ УСПЕШНО УДАЛЕНЫ! ');

        return $this->redirectToRoute('list_contractors', [
            'contractorType' => 'customers',
        ]);
    }
}
