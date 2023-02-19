<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientModifyType;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * This controller display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    /**
     * This controller show form to create new Ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau',name: 'ingredient.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $ingredient = new Ingredient();
        //On crée le form
        $form = $this->createForm(IngredientType::class, $ingredient)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Creer mon ingrédient'
            ]);
        //On récupére les données du form
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            //On relie l'ingrédient à l'utilisateur
            $ingredient->setUser($this->getUser());
            //On met le nouvel ingrédient dans la bd
            $manager->persist($ingredient);//commit
            $manager->flush();//push dans la bd

            $this->addFlash(
                'success',
                'Votre ingrédient à été créé avec succes !'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * This function show a form to modify a Ingredient
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/edition/{id}', name : 'ingredient.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager) :Response
    {
        //cherche l'ingredient avce l'id correspondant
        //On crée le form
        $form = $this->createForm(IngredientType::class, $ingredient)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Modifier mon ingrédient'
            ]);
        //On récupére les données du form
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            //On met le nouvel ingrédient dans la bd
            $manager->persist($ingredient);//commit
            $manager->flush();//push dans la bd

            $this->addFlash(
                'success',
                'Votre ingrédient à été modifié avec succes !'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller delete an Ingredient
     *
     * @param EntityManagerInterface $manager
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/supression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient) : Response
    {
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient à été supprimé avec succes !'
        );
        return $this->redirectToRoute('ingredient.index');
    }
}
