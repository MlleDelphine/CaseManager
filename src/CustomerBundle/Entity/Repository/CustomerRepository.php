<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 14/11/2018
 * Time: 19:53
 */

namespace CustomerBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository {

    public function getOnlyLegalPersons() {

        $query = $this->createQueryBuilder("c")
            ->from("CustomerBundle:AbstractClass\Customer", "a")
            ->where("u.id  :id");
            //->setParameter("id", $user->getId());

        return $query;
    }
}