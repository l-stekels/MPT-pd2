<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @template T
 */
abstract class AbstractRepository extends ServiceEntityRepository
{

    /**
     * @param string     $id
     * @param mixed|null $lockMode
     * @param mixed|null $lockVersion
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @return T|null
     *
     * @final
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @return T|null
     *
     * @final
     */
    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @return T[]
     *
     * @final
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param mixed|null $limit
     * @param mixed|null $offset
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @return T[]
     *
     * @final
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @final
     */
    public function persist(object $entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @final
     */
    public function remove(object $entity): void
    {
        $this->_em->remove($entity);
    }

    /**
     * @final
     */
    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * @final
     */
    public function refresh(object $entity): void
    {
        $this->_em->refresh($entity);
    }
}
