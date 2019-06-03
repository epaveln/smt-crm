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

use App\Entity\Person;
use App\Form\PersonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserPersonController
 */
class UserPersonController extends AbstractController
{
    /**
     * @Route("/user/create/person/for/contractor/{id}/", name="create_person")
     */
    public function createPerson($id, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PersonFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Person $person*/
            $person = $form->getData();

            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'Сотрудник '.$person->getName().' успешно создан!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $id,
            ]);
        }

        return $this->render('user/add_person.html.twig', [
            'personForm' => $form->createView(),
            'id' => $id,
        ]);
    }

    /**
     * @Route("/user/edit/person/{id}/", name="edit_person")
     */
    public function editperson(Person $person, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PersonFormType::class, $person);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Person $person*/
            $person = $form->getData();

            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'Сотрудник '.$person->getName().' успешно обновлен!');
            return $this->redirectToRoute('show_contractor', [
                'id' => $person->getContractor()->getId(),
            ]);
        }

        return $this->render('user/edit_person.html.twig', [
            'editPersonForm' => $form->createView(),
            'personId' => $person->getId(),
        ]);
    }

    /**
     * @Route("/user/delete/person/{id}", name="delete_person")
     */
    public function deletePerson(Person $person, EntityManagerInterface $em)
    {
        $em->remove($person);
        $em->flush();

        return $this->redirectToRoute('show_contractor', [
            'id' => $person->getContractor()->getId(),
        ]);
    }
}
