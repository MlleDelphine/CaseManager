<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 14/11/2018
 * Time: 18:13
 */

namespace BusinessBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class InternalReferenceGenerator extends AbstractIdGenerator
{
    /**
     * The name of the sequence to pass to lastInsertId(), if any.
     *
     * @var string
     */
    private $sequenceName;

    /**
     * Constructor.
     *
     * @param string|null $sequenceName The name of the sequence to pass to lastInsertId()
     *                                  to obtain the last generated identifier within the current
     *                                  database session/connection, if any.
     */
    public function __construct($sequenceName = null)
    {
        $this->sequenceName = $sequenceName;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(EntityManager $em, $entity)
    {
        return (int)$em->getConnection()->lastInsertId($this->sequenceName);
    }

    /**
     * {@inheritdoc}
     */
    public function isPostInsertGenerator()
    {
        return true;
    }

}