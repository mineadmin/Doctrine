<?php

declare(strict_types=1);

/**
 * Example: Service Usage with Doctrine EntityManager
 * 
 * This example shows how to use the EntityManager in a Hyperf service.
 */

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Hyperf\Di\Annotation\Inject;

class ProductService
{
    #[Inject]
    private EntityManagerInterface $entityManager;

    public function createProduct(string $name, string $price, ?string $description = null): Product
    {
        $product = new Product($name, $price);
        
        if ($description !== null) {
            $product->setDescription($description);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function findProductById(int $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id);
    }

    public function findActiveProducts(): array
    {
        return $this->entityManager->getRepository(Product::class)
            ->findBy(['active' => true]);
    }

    public function updateProduct(Product $product): Product
    {
        $this->entityManager->flush();
        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function searchProductsByName(string $name): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        $qb->select('p')
           ->from(Product::class, 'p')
           ->where('p.name LIKE :name')
           ->setParameter('name', '%' . $name . '%')
           ->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getProductStats(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        $qb->select('COUNT(p.id) as total_products, AVG(p.price) as average_price')
           ->from(Product::class, 'p')
           ->where('p.active = :active')
           ->setParameter('active', true);

        return $qb->getQuery()->getSingleResult();
    }
}