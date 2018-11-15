<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 14/11/2018
 * Time: 18:13
 */

namespace BusinessBundle\Service;

use BusinessBundle\Entity\BusinessCase;
use CustomerBundle\Entity\CorporationGroup;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;

class InternalReferenceGeneratorSubscriber implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
        );
    }

    public function postPersist(LifecycleEventArgs $eventArgs){
        $em = $eventArgs->getObjectManager();
        $entity = $eventArgs->getObject();
        if($entity instanceof BusinessCase){
            //No ref exists
            if(!$entity->getInternalReference()) {
                if ($entity->getCustomer()->getObjectName() == CorporationGroup::ObjectName) {
                    $lastSixDigitsOfExternalReference = preg_match("/\d{6}$/", $entity->getExternalReference(), $m);
                    $projectManagerName = $entity->getCustomerProjectMaanger();
                    $projectManagerNameCleaned = substr(strtoupper(preg_replace("/[^a-zA-Z]/", "", $projectManagerName)), 0, 3);

                    $firstThreeLetters = str_pad($projectManagerNameCleaned, 3, "X");
                    if (isset($m[0])) {
                        $internalReference = "EC" . date("y") . $lastSixDigitsOfExternalReference . $firstThreeLetters;
                        $entity->setInternalReference($internalReference);
                    }
                } else {
                    $internalReference = "E" . date("ym") . str_pad($entity->getId(), 6, 0, STR_PAD_LEFT);
                    $entity->setInternalReference($internalReference);
                }
            }
        }
    }
}