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

use App\Custom\FileHelper;
use App\Custom\Paginator;
use App\Entity\Tender;
use App\Form\AttachFileToTenderFormType;
use App\Form\EditTenderFormType;
use App\Form\TenderFormType;
use App\Repository\ContractorRepository;
use App\Repository\ContractorTypeRepository;
use App\Repository\TenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserTenderController
 */
class UserTenderController extends AbstractController
{
    /**
     * @Route("/user/list/tenders", name="list_tenders")
     */
    public function listTenders(TenderRepository $tenderRepository, PaginatorInterface $paginator, Request $request)
    {
        $queryBuilder = $tenderRepository->findAllTendersWithQB();
        $pagination = Paginator::paginate($paginator, $queryBuilder, $request);

        return $this->render('user/list_tenders.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/user/add/tender", name="add_tender")
     */
    public function addTender(Request $request, EntityManagerInterface $em, ContractorRepository $contractorRepository, ContractorTypeRepository $contractorTypeRepository, $attachTenderDir)
    {
        $form = $this->createForm(TenderFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ('' == !$request->request->get('tender_form')['contractor']) {
                /**@var Tender $tender*/
                $tender = $form->getData();

                $tender->setContractor($contractorRepository->find($request->request->get('tender_form')['contractor']));

                if (count($form['attach']) > 0) {
                    $uploadedFilesArray = $request->files->get('tender_form');
                    $tender->setAttach(FileHelper::save($uploadedFilesArray, $attachTenderDir));
                } else {
                    $tender->setAttach([]);
                }

                $em->persist($tender);
                $em->flush();

                $this->addFlash('success', 'Тендер успешно добавлен в систему!');

                return $this->redirectToRoute('show_tender', [
                    'id' => $tender->getId(),
                ]);
            }
        }

        $customers = $contractorRepository->findAllContractorsByType($contractorTypeRepository->getCustomerType());

        return $this->render('user/add_tender.html.twig', [
            'tenderForm' => $form->createView(),
            'contractors' => $customers,
        ]);
    }

    /**
     * @Route("/user/show/tender/{id}", name="show_tender")
     */
    public function showTender(Tender $tender)
    {
        return $this->render('user/show_tender.html.twig', [
            'tender' => $tender,
        ]);
    }

    /**
     * @Route("/user/edit/tender/{id}", name="edit_tender")
     */
    public function editTender(Tender $tender, Request $request, EntityManagerInterface $em, $attachTenderDir)
    {
        $tender->setAttachTenderDir($attachTenderDir);

        $form = $this->createForm(EditTenderFormType::class, $tender);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Tender $tender*/
            $tender = $form->getData();

            $em->persist($tender);
            $em->flush();

            $this->addFlash('success', 'Тендер предприятия "'.$tender->getContractor()->getName().'" успешно обновлен!');
            return $this->redirectToRoute('show_tender', [
                'id' => $tender->getId(),
            ]);
        }

        return $this->render('user/edit_tender.html.twig', [
            'editTenderForm' => $form->createView(),
            'tenderId' => $tender->getId(),
        ]);
    }

    /**
     * @Route("/user/attach/file/to/tender/{id}/", name="attach_file_to_tender")
     */
    public function attachFileToOffer(Tender $tender, Request $request, EntityManagerInterface $em, $attachTenderDir)
    {
        $form = $this->createForm(AttachFileToTenderFormType::class);

        $tender->setAttachTenderDir($attachTenderDir);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (count($form['attach']) > 0) {
                $uploadedFilesArray = $request->files->get('attach_file_to_tender_form');
                $tender->setAttach(array_merge($tender->getAttach(), FileHelper::save($uploadedFilesArray, $attachTenderDir)));
            }

            $em->persist($tender);
            $em->flush();

            $this->addFlash('success', 'Тендер предприятия "'.$tender->getContractor()->getName().'" успешно обновлен!');

            return $this->redirectToRoute('edit_tender', [
                'id' => $tender->getId(),
            ]);
        }

        return $this->render('user/add_file_to_tender.html.twig', [
            'attachFileToTenderForm' => $form->createView(),
            'tenderId' => $tender->getId(),
        ]);
    }

    /**
     * @Route("/user/delete/tenders", name="delete_tenders")
     */
    public function deleteSelectedOffers(Request $request, TenderRepository $tenderRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('tender');

        $message = '';

        if (!is_null($toBeDeleted) && count($toBeDeleted) > 0) {
            foreach ($toBeDeleted as $key => $value) {
                $tender = $tenderRepository->findOneBy(['id' => $value]);

                $message .= $value.' ';
                $em->remove($tender);
            }
            $em->flush();
            $this->addFlash('success', 'Тендер №'.$message.' БЫЛ УСПЕШНО УДАЛЕН! ');
        }

        return $this->redirectToRoute('list_tenders');
    }
}
