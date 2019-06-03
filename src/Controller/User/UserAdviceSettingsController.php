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

use App\Entity\AdviceSettings;
use App\Form\EditAdviceSettingsFormType;
use App\Repository\AdviceSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserAdviceSettingsController
 */
class UserAdviceSettingsController extends AbstractController
{
    /**
     * @Route("/user/edit/advice/settings", name="edit_advice_settings")
     */
    public function editAdviceSettings(Request $request, AdviceSettingsRepository $repository, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditAdviceSettingsFormType::class, $repository->find(1));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var AdviceSettings $adviceSettings*/
            $adviceSettings = $form->getData();

            $em->persist($adviceSettings);
            $em->flush();

            $this->addFlash('success', 'Настройки поиска успешно обновлены!');

            return $this->redirectToRoute('edit_advice_settings');

        }

        return $this->render('user/edit_advice_settings.html.twig', [
            'adviceSettingsForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/find/advice", name="find_advice")
     */
    public function findAdvice()
    {
        return $this->render('user_advice/index.html.twig', [
            'controller_name' => 'UserAdviceController',
        ]);
    }
}
