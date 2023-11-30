<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class UserController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("user/new", name="add_user")
     */

    public function adduser(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
                     ->add('firstname', TextType::class)
                     ->add('lastname', TextType::class)
                     ->add('email', EmailType::class)
                     ->add('phone', TextType::class)
                     ->add('address', TextType::class)
                     ->add('sexe', ChoiceType::class, [
                         "choices" => [
                             "Man" => "man",
                             "Woman" => "woman"
                         ]
                     ])->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('home');
            dump($user);

        }
        return $this->render('user/form.html.twig', [
            "userForm" => $form->createView()
        ]);
    }
}
