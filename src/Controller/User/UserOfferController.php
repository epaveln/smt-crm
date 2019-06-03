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
use App\Entity\Contractor;
use App\Entity\Offer;
use App\Form\AttachFileToOfferFormType;
use App\Form\EditOfferFormType;
use App\Form\OfferFormType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserOfferController
 */
class UserOfferController extends AbstractController
{
    /**
     * @Route("/user/create/offer/for/contractor/{id}/", name="create_offer")
     */
    public function createOffer(Contractor $contractor, Request $request, EntityManagerInterface $em, $attachOfferDir)
    {
        $form = $this->createForm(OfferFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Offer $offer*/
            $offer = $form->getData();

            if (count($form['attach']) > 0) {
                $uploadedFilesArray = $request->files->get('offer_form');
                $offer->setAttach(FileHelper::save($uploadedFilesArray, $attachOfferDir));
            } else {
                $offer->setAttach([]);
            }
            $offer->setUser($this->getUser());

            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Предложение №'.$offer->getNumber().' успешно создано!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $contractor->getId(),
            ]);
        }

        return $this->render('user/add_offer.html.twig', [
            'offerForm' => $form->createView(),
            'id' => $contractor->getId(),
        ]);
    }

    /**
     * @Route("/user/show/offer/{id}", name="show_offer")
     */
    public function showOffer(Offer $offer)
    {
        return $this->render('user/show_offer.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/user/edit/offer/{id}/", name="edit_offer")
     */
    public function editOffer(Offer $offer, Request $request, EntityManagerInterface $em, $attachOfferDir)
    {
        $offer->setAttachOfferDir($attachOfferDir);

        $form = $this->createForm(EditOfferFormType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Offer $offer*/
            $offer = $form->getData();

            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Предложение №'.$offer->getNumber().' успешно обновлено!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $offer->getContractor()->getId(),
            ]);
        }

        return $this->render('user/edit_offer.html.twig', [
            'editOfferForm' => $form->createView(),
            'offerId' => $offer->getId(),
        ]);
    }

    /**
     * @Route("/user/attach/file/to/offer/{id}/", name="attach_file_to_offer")
     */
    public function attachFileToOffer(Offer $offer, Request $request, EntityManagerInterface $em, $attachOfferDir)
    {
        $form = $this->createForm(AttachFileToOfferFormType::class);

        $offer->setAttachOfferDir($attachOfferDir);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (count($form['attach']) > 0) {
                $uploadedFilesArray = $request->files->get('attach_file_to_offer_form');
                $offer->setAttach(array_merge($offer->getAttach(), FileHelper::save($uploadedFilesArray, $attachOfferDir)));
            }
            $offer->setUser($this->getUser());

            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Предложение №'.$offer->getNumber().' успешно обновлено!');
            return $this->redirectToRoute('edit_offer', [
                'id' => $offer->getId(),
            ]);
        }

        return $this->render('user/add_file_to_offer.html.twig', [
            'attachFileToOfferForm' => $form->createView(),
            'offerNumber' => $offer->getNumber(),
        ]);
    }

    /**
     * @Route("/user/delete/offers", name="delete_offers")
     */
    public function deleteSelectedOffers(Request $request, OfferRepository $offerRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('offer');
        $contractorId = $request->query->get('contractorId');

        $message = '';

        if (!is_null($toBeDeleted) && count($toBeDeleted) > 0) {
            foreach ($toBeDeleted as $key => $value) {
                $offer = $offerRepository->findOneBy(['id' => $value]);

                $message .= $value.' ';
                $em->remove($offer);
            }
            $em->flush();
            $this->addFlash('success', 'Предложение №'.$message.' БЫЛО УСПЕШНО УДАЛЕНО! ');
        }

        return $this->redirectToRoute('show_contractor', [
            'id' => $contractorId,
        ]);
    }
}
