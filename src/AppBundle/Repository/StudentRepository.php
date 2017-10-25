<?php

namespace AppBundle\Repository;

/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentRepository extends \Doctrine\ORM\EntityRepository
{
    public function countStudents($user)
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getFirstStudent($user, $class)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.user = :user')
            ->andWhere('s.classs = :class')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('class', $class)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastStudent($user, $class)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.user = :user')
            ->andWhere('s.classs = :class')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('class', $class)
            ->getQuery()
            ->getOneOrNullResult();
    }

	public function previous($student, $class)
	{
	    return $this->getEntityManager()
	        ->createQuery(
	            'SELECT s
	            FROM AppBundle:Student s
	            WHERE s.id < :id
	            AND s.classs = :class
	            ORDER BY s.id DESC
	            '
	        )
	        ->setParameter(':id', $student)
	        ->setParameter(':class', $class)
            ->setMaxResults(1)
	        ->getResult();
	}

	public function next($student, $class)
	{
	    return $this->getEntityManager()
	        ->createQuery(
	            'SELECT s
	            FROM AppBundle:Student s
	            WHERE s.id > :id
	            AND s.classs = :class
	            ORDER BY s.id ASC
	            '
	        )
	        ->setParameter(':id', $student)
	        ->setParameter(':class', $class)
            ->setMaxResults(1)
	        ->getResult();
	}
}