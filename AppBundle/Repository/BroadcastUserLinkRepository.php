<?php

namespace AppBundle\Repository;
 use AppBundle\Entity\User;
 use Doctrine\ORM\NonUniqueResultException;
 use Doctrine\ORM\NoResultException;
 use Doctrine\ORM\ORMException;

 /**
 * BroadcastUserLinkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BroadcastUserLinkRepository extends \Doctrine\ORM\EntityRepository
{
    public function getBroadcastsForUser(User $user) {
        $query = $this->createQueryBuilder('broadcasts')
            ->where('broadcasts.user = :user')
            ->andWhere('broadcasts.archived = 0')
            ->setParameter('user', $user)
            ->orderBy('broadcasts.dateCreated', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    public function findBroadcastUserLinkByBroadcast($broadcast) {
        try {
            $currentID = $this->createQueryBuilder('link')
                ->select('link.id')
                ->where('link.broadcast = :broadcast')
                ->andWhere('link.archived = 0')
                ->setParameter('broadcast', $broadcast)
                ->getQuery()
                ->getSingleScalarResult();
            if (!$currentID)
                return false;
            return $this->_em->getReference('AppBundle:BroadcastUserLink', $currentID);
        }
        catch (NoResultException $e) {
            return false;
        } catch (NonUniqueResultException $e) {
            return false;
        } catch (ORMException $e) {
            return false;
        }
    }
}