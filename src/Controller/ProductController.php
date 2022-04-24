<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/api/products", methods={"POST"})
     */
    public function add(ValidatorInterface $validator, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data["product_name"]);
        $product->setPrice($data["product_price"]);
        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;
    
            return new Response($errorsString);
        }
    
        return new Response('The author is valid! Yes!');
    }

    /**
     * @Route("/api/products/{id}", methods={"GET","HEAD"})
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());
        // ... return a JSON response with the products
    }

    /**
     * @Route("/api/products/{id}", methods={"PUT"})
     */
    public function edit(int $id): Response
    {
        // ... edit a products
    }
}
