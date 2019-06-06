<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Subscriber;
use AppBundle\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;

/**
 * SubscriberUserLinkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubscriberUserLinkRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSubscriberUserLinkBySubscriberEmail($email)
    {
        try {
            $currentID = $this->createQueryBuilder('subscriberUserLink')
                ->select('subscriberUserLink.id')
                ->where("LOWER(subscriberUserLink.subscriber.email) LIKE ? LOWER(:email)")
                ->setParameter('email', $email)
                ->getQuery()
                ->getSingleScalarResult();

            if (!$currentID) {
                return false;
            }
            return $this->_em->getReference('AppBundle\Entity\SubscriberUserLink', $currentID);

        } catch (NoResultException $e) {
            return false;
        } catch (NonUniqueResultException $e) {
            return false;
        } catch (ORMException $e) {
            return false;
        }

    }

    /**
     * @param User $user
     * @param Subscriber $subscriber
     * @return null|object
     */
    public function findSubscriberUserLink(User $user, Subscriber $subscriber)
    {
        return $this->findOneBy(array(
            'user' => $user,
            'subscriber' => $subscriber
        ));
    }

    public function getAllSubscriberLinksForUser(User $user) {
        $query = $this->createQueryBuilder('subscriberUserLink')
            ->where("subscriberUserLink.user = :user")
            ->setParameter('user', $user)
            ->orderBy('subscriberUserLink.dateCreated', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}