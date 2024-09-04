<?php

namespace App\Repository;

use DateTime;
use Exception;
use App\Entity\Stages;
use App\Entity\Categories;
use App\Entity\Constructions;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Throwable;

/**
 * @extends ServiceEntityRepository<Constructions>
 */
class ConstructionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Constructions::class);
    }

    public function getList(?string $filter): array
    {
        $query = $this->createQueryBuilder('c');
        if (!empty($filter)) {
            $filter = strtolower($filter);
            $query->andWhere("LOWER(c.name) LIKE :filter OR LOWER(c.location) LIKE :filter OR LOWER(c.stage) LIKE :filter OR LOWER(c.category) LIKE :filter");
            $query->setParameter("filter", "%".$filter."%");
        }
        return $query->getQuery()->getResult();
    }

    public function store(Constructions $construction): Constructions
    {
        $entityManager = $this->getEntityManager();
        $db = $entityManager->getConnection();
        $db->beginTransaction();
        try {
            $entityManager->persist($construction);
            $entityManager->flush();
            $db->commit();
            return $construction;
        } catch (\Throwable $th) {
            $db->rollBack();
            throw new \Exception('Failed to store construction in the database.', $th->getCode());
        }
    }

    public function detail($id): ?Constructions
    {
        return $this->find($id);
    }

    public function update($id, array $data): Constructions
    {
        $entityManager = $this->getEntityManager();
        $db = $entityManager->getConnection();

        $construction = $entityManager->find(Constructions::class, $id);
        if (!$construction) {
            throw new \Exception('Construction not found.');
        }

        $db->beginTransaction();
        try {
            if (isset($data['name'])) {
                $construction->setName($data['name']);
            }
            if (isset($data['location'])) {
                $construction->setLocation($data['location']);
            }
            if (isset($data['stage'])) {
                $stage = Stages::from($data['stage']);
                $construction->setStage($stage);
            }
            if (isset($data['category'])) {
                $category = Categories::from($data['category']);
                $construction->setCategory($category);
                if ($data['category'] != Categories::OTHERS->value) {
                    $construction->setOtherCategory(null);
                }
            }
            if (isset($data['otherCategory'])) {
                $construction->setOtherCategory($data['otherCategory']);
            }
            if (isset($data['startDate'])) {
                $construction->setStartDate(new DateTime($data['startDate']));
            }
            if (isset($data['description'])) {
                $construction->setDescription($data['description']);
            }
            if (isset($data['creatorId'])) {
                $construction->setCreatorId($data['creatorId']);
            }
            $entityManager->persist($construction);
            $entityManager->flush();
            $db->commit();

            return $construction;
        } catch (Throwable $th) {
            $db->rollBack();
            throw new \Exception('Failed to update construction in the database. ' . $th->getMessage(), $th->getCode());
        }
    }

    public function delete($id): bool
    {
        $entityManager = $this->getEntityManager();
        $construction = $this->find($id);
        if (!$construction) {
            throw new Exception('Construction not found.');
        }
        try {
            $entityManager->remove($construction);
            $entityManager->flush();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Failed to delete construction in the database.', $th->getCode());
        }
    }
}
