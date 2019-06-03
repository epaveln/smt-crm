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

use App\Entity\Country;
use App\Form\CountryFormType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserCountryController
 */
class UserCountryController extends AbstractController
{
    /**
     * @Route("/user/list/countries", name="list_countries")
     */
    public function listCountries(CountryRepository $repository)
    {
        return $this->render('user/list_countries.html.twig', [
            'countries' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/user/add/country", name="add_country")
     */
    public function addCountry(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CountryFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Country $country*/
            $country = $form->getData();

            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('list_countries');
        }

        return $this->render('user/add_country.html.twig', [
            'countryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit/country/{id}", name="edit_country")
     */
    public function editCountry(Country $country, Request $request, CountryRepository $repository, EntityManagerInterface $em)
    {
        $form = $this->createForm(CountryFormType::class, $country);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Country $country*/
            $country = $form->getData();

            $em->persist($country);
            $em->flush();

            return $this->render('user/list_countries.html.twig', [
                'countries' => $repository->findAll(),
            ]);
        }

        return $this->render('user/edit_country.html.twig', [
            'editCountryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/countries", name="delete_countries")
     */
    public function deleteSelectedCountries(Request $request, CountryRepository $countryRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('country');
        $message = '';

        foreach ($toBeDeleted as $key => $value) {
            $country = $countryRepository->findOneBy(['id' => $value]);

            //проверяем
            if (!is_null($country)) {
                $message .= $value.' ';
                $em->remove($country);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Страны №'.$message.' БЫЛИ УСПЕШНО УДАЛЕНЫ! ');

        return $this->redirectToRoute('list_countries');
    }
}
