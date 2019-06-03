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

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserTagController
 */
class UserTagController extends AbstractController
{
    /**
     * @Route("/user/list/tags", name="list_tags")
     */
    public function listTags(TagRepository $repository)
    {
        return $this->render('user/list_tags.html.twig', [
            'tags' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/user/add/tag", name="add_tag")
     */
    public function addTag(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TagFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Tag $tag*/
            $tag = $form->getData();

            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Ключевое слово успешно добавлено! ');

            return $this->redirectToRoute('add_tag');
        }

        return $this->render('user/add_tag.html.twig', [
            'tagForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/edit/tag/{id}", name="edit_tag")
     */
    public function edittag(Tag $tag, Request $request, TagRepository $repository, EntityManagerInterface $em)
    {
        $form = $this->createForm(TagFormType::class, $tag);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Tag $tag*/
            $tag = $form->getData();

            $em->persist($tag);
            $em->flush();

            return $this->render('user/list_tags.html.twig', [
                'tags' => $repository->findAll(),
            ]);
        }

        return $this->render('user/edit_tag.html.twig', [
            'editTagForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/tags", name="delete_tags")
     */
    public function deleteSelectedCountries(Request $request, TagRepository $tagRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('tag');
        $message = '';

        foreach ($toBeDeleted as $key => $value) {
            $tag = $tagRepository->findOneBy(['id' => $value]);

            //проверяем
            if (!is_null($tag)) {
                $message .= $value.' ';
                $em->remove($tag);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Ключевые слова №'.$message.' БЫЛИ УСПЕШНО УДАЛЕНЫ! ');

        return $this->redirectToRoute('list_tags');
    }
}
