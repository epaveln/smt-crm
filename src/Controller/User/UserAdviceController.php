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

use App\Custom\AdviceWatcher;
use \App\Entity\Advice;
use App\Entity\AdviceSettings;
use App\Form\EditAdviceSettingsFormType;
use App\Repository\AdviceRepository;
use App\Repository\AdviceSettingsRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserAdviceController
 */
class UserAdviceController extends AbstractController
{
    /**
     * @Route("/user/search/advices", name="search_advices")
     */
    public function searchAdvices(AdviceSettingsRepository $adviceSettingsRepository, TagRepository $tagRepository, AdviceRepository $adviceRepository, EntityManagerInterface $em)
    {
        $watcher = new AdviceWatcher($adviceSettingsRepository->find(1), $tagRepository->findAll());

        $adviceData = $watcher->getAdviceTenders();
        $counter = 0;
        if (!empty($adviceData)) {
            foreach ($adviceData as $key => $value) {
                if (is_null($adviceRepository->find($key))) {
                    $advice = new Advice();
                    $advice->setId($key);
                    $advice->setTitle($value);
                    $advice->setChecked(false);
                    $counter++;
                    $em->persist($advice);
                    $em->flush();
                }
            }
        }

        if ($counter > 0) {
            $this->addFlash('success', 'Найдено новых тендеров: '.$counter);
        } else {
            $this->addFlash('warning', 'Рекомендуемые тендеры не найдены.');
        }

        return $this->redirectToRoute('list_advices');
    }

    /**
     * @Route("/user/list/advices", name="list_advices")
     */
    public function listAdvices(AdviceSettingsRepository $adviceSettingsRepository, AdviceRepository $adviceRepository)
    {
        return $this->render('user/list_advices.html.twig', [
            'advices' => $adviceRepository->findBy(['checked' => false]),
            'itemUrl' => $adviceSettingsRepository->find(1)->getItemUrl(),
        ]);
    }

    /**
     * @Route("/user/process/advices", name="process_advices")
     */
    public function processAdvices(Request $request, EntityManagerInterface $em, AdviceRepository $adviceRepository)
    {
        $toBeChecked =  $request->query->get('advice');
        $message = '';

        foreach ($toBeChecked as $key => $value) {
            $advice = $adviceRepository->find($value);

            //проверяем
            if (!is_null($advice)) {
                $message .= $value.' ';

                $advice->setChecked(true);
                $em->persist($advice);
                $em->flush();
            }
        }
        $this->addFlash('success', 'Тендеры №'.$message.' были помечены, как не актуальные!');

        return $this->redirectToRoute('list_advices');
    }
}
