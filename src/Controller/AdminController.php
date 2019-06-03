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

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\EditUserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/add/user", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['passwordRepeat']->getData() === $form['password']->getData()) {
                /**@var User $user*/
                $user = $form->getData();
                $user->setRoles($user->getRoles());
                $user->setPassword($userPasswordEncoder->encodePassword(
                    $user,
                    $form['password']->getData()
                ));

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Пользователь успешно зарегистрирован!');
            } else {
                $this->addFlash('warning', 'Пароли не совпадают!');
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/user/{id}", name="edit_user")
     */
    public function editUser(User $user, Request $request, EntityManagerInterface $em, RoleRepository $roleRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var User $user*/
            $user = $form->getData();
            $user->setRoles([$request->request->get('edit_user_form')['role']]);

            if ('' == !$request->request->get('edit_user_form')['plainPassword']) {
                $user->setPassword($userPasswordEncoder->encodePassword(
                    $user,
                    $request->request->get('edit_user_form')['plainPassword']
                ));
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Данные пользователя успешно изменены!');
            return $this->render('admin/edit_user.html.twig', [
                'editUserForm' => $form->createView(),
                'userRoles' => $user->getRoles(),
                'roleTypes' => $roleRepository->findAll(),
            ]);
        }

        return $this->render('admin/edit_user.html.twig', [
            'editUserForm' => $form->createView(),
            'userRoles' => $user->getRoles(),
            'roleTypes' => $roleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/delete/users", name="delete_users")
     */
    public function deleteSelectedUsers(Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $toBeDeleted =  $request->query->get('user');
        $message = '';

        foreach ($toBeDeleted as $key => $value){
            $user = $userRepository->findOneBy(['id' => $value]);

            //проверяем
            if (!is_null($user)) {
                $message .= $value.' ';

                $em->remove($user);
                $em->flush();
            }
        }
        $this->addFlash('success', 'Пользователи №'.$message.' БЫЛИ УСПЕШНО УДАЛЕНЫ! ');

        return $this->redirectToRoute('list_users');
    }

    /**
     * @Route("/admin/list/users", name="list_users")
     */
    public function listUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }
}
