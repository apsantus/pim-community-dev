<?php

namespace Pim\Component\Catalog\Repository;

use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Pim\Component\Catalog\Model\AssociationAwareInterface;
use Pim\Component\Catalog\Model\AssociationTypeInterface;

/**
 * Association repository interface
 *
 * @author    Julien Janvier <jjanvier@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AssociationTypeRepositoryInterface extends IdentifiableObjectRepositoryInterface, ObjectRepository
{
    /**
     * Build all association entities not yet linked to a product
     *
     * @param AssociationAwareInterface $entity
     *
     * @return AssociationTypeInterface[]
     */
    public function findMissingAssociationTypes(AssociationAwareInterface $entity);

    /**
     * Return the number of association types
     *
     * @return int
     */
    public function countAll(): int;
}
