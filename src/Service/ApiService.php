<?php


namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ApiService
{
    private $productRepository;
    private $em;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function findProduct(int $id)
    {
        $post = $this->productRepository->findOneBy(['id' => $id]);
        if (empty($post)) {
            return false;
        }
        return $post;
    }

    public function getProducts(int $page, int $limit, string $order)
    {
        $orderBy = substr($order,0, 1) ==='-' ? 'DESC' : 'ASC';
        $field = substr($order,0, 1) ==='-' ? substr($order,1, (strlen($order) - 1)) : $order;

        $products = $this->productRepository->findByFields($page, $limit, $field, $orderBy);
        $response = [];
        foreach ($products as $product){
            $response[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()
            ];
        }

        return $response;
    }

    public function setProduct(string $name, string $description, float $price)
    {
        $product = new Product();
        $product
            ->setName($name)
            ->setDescription($description)
            ->setPrice($price);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function updateProduct(int $id, ?string $name, ?string $description, ?float $price)
    {
        $product = $this->findProduct($id);

        $product->setName($name ?? $product->getName());
        $product->setDescription($description ?? $product->getDescription());
        $product->setPrice($price ?? $product->getPrice());

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function deleteProduct(int $id)
    {
        if ($product = $this->findProduct($id)) {
            $this->em->remove($product);
            $this->em->flush();
            return true;
        }
        return false;
    }
}
