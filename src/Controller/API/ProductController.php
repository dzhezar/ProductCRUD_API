<?php


namespace App\Controller\API;

use App\Repository\ProductRepository;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $service;

    public function __construct(ApiService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/api/products", methods={"GET"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function getProducts(Request $request)
    {
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 25;
        $order = $request->get('order') ?? 'id';

        $products = $this->service->getProducts($page, $limit, $order);

        return new JsonResponse([
            'data' => $products
        ]);
    }

    /**
     * @Route("/api/products", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function setProduct(Request $request)
    {
        $name =$request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');

        $product = $this->service->setProduct($name, $description, $price);

        return new JsonResponse([
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()
            ]
        );
    }

    /**
     * @Route("/api/products/{id}", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getProduct(int $id)
    {
        $product = $this->service->findProduct($id);

        return new JsonResponse([
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()
            ]
        );
    }

    /**
     * @Route("/api/products/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function updateProduct(int $id, Request $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');

        $product = $this->service->updateProduct($id, $name, $description, $price);

        return new JsonResponse([
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()
            ]
        );
    }

    /**
     * @Route("/api/products/{id}", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function deleteProduct(int $id)
    {
        if ($this->service->deleteProduct($id)) {
            return new JsonResponse([],Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }
}
