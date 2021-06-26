<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductController extends AbstractController
{
    public function new(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($product);
             $entityManager->flush();

            return $this->redirectToRoute('app_lucky_number');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     public function index(): Response
     {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        return $this->render('index.html.twig', [
            'products' => $repository->findAll(),
        ]);
     }

  

    /**
     * @Route("/product/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
      }

     /**
     * @Route("/product/edit/{id}", name="edit_product")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $product = new Product();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
  
        $form = $this->createFormBuilder($product)
        ->add('code', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
        ->add('name', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
          ->add('description', TextareaType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
          ->add('brand', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
          ->add('price', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
          ->add('name', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
          ->add('category', EntityType::class, array(
            'class' => Category::class,
            'required' => true,
            'attr' => array('class' => 'form-control')
          ))
        ->add('save', SubmitType::class, array(
            'label' => 'Update',
            'attr' => array('class' => 'btn btn-primary mt-3')
          ))
          ->getForm();
  
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
  
          return $this->redirectToRoute('app_products_show');
        }
  
        return $this->render('product/edit.html.twig', array(
          'form' => $form->createView()
        ));
      }

        /**
     * @Route("/product/show/{id}", name="product_show")
     */
    public function show($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
  
        return $this->render('product/show.html.twig', array('product' => $product));
      }
  
}