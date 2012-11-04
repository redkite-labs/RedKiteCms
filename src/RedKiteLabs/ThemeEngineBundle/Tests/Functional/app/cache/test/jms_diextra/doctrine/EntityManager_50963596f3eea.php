<?php

namespace EntityManager50963596f3eea_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class EntityManager extends \Doctrine\ORM\EntityManager
{
    private $delegate;
    private $container;

    /**
     * Executes a function in a transaction.
     *
     * The function gets passed this EntityManager instance as an (optional) parameter.
     *
     * {@link flush} is invoked prior to transaction commit.
     *
     * If an exception occurs during execution of the function or flushing or transaction commit,
     * the transaction is rolled back, the EntityManager closed and the exception re-thrown.
     *
     * @param callable $func The function to execute transactionally.
     * @return mixed Returns the non-empty value returned from the closure or true instead
     */
    public function transactional($func)
    {
        return $this->delegate->transactional($func);
    }

    /**
     * Performs a rollback on the underlying database connection.
     */
    public function rollback()
    {
        return $this->delegate->rollback();
    }

    /**
     * Removes an entity instance.
     *
     * A removed entity will be removed from the database at or before transaction commit
     * or as a result of the flush operation.
     *
     * @param object $entity The entity instance to remove.
     */
    public function remove($entity)
    {
        return $this->delegate->remove($entity);
    }

    /**
     * Refreshes the persistent state of an entity from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $entity The entity to refresh.
     */
    public function refresh($entity)
    {
        return $this->delegate->refresh($entity);
    }

    /**
     * Tells the EntityManager to make an instance managed and persistent.
     *
     * The entity will be entered into the database at or before transaction
     * commit or as a result of the flush operation.
     *
     * NOTE: The persist operation always considers entities that are not yet known to
     * this EntityManager as NEW. Do not pass detached entities to the persist operation.
     *
     * @param object $object The instance to make managed and persistent.
     */
    public function persist($entity)
    {
        return $this->delegate->persist($entity);
    }

    /**
     * Create a new instance for the given hydration mode.
     *
     * @param  int $hydrationMode
     * @return \Doctrine\ORM\Internal\Hydration\AbstractHydrator
     */
    public function newHydrator($hydrationMode)
    {
        return $this->delegate->newHydrator($hydrationMode);
    }

    /**
     * Merges the state of a detached entity into the persistence context
     * of this EntityManager and returns the managed copy of the entity.
     * The entity passed to merge will not become associated/managed with this EntityManager.
     *
     * @param object $entity The detached entity to merge into the persistence context.
     * @return object The managed copy of the entity.
     */
    public function merge($entity)
    {
        return $this->delegate->merge($entity);
    }

    /**
     * Acquire a lock on the given entity.
     *
     * @param object $entity
     * @param int $lockMode
     * @param int $lockVersion
     * @throws OptimisticLockException
     * @throws PessimisticLockException
     */
    public function lock($entity, $lockMode, $lockVersion = NULL)
    {
        return $this->delegate->lock($entity, $lockMode, $lockVersion);
    }

    /**
     * Check if the Entity manager is open or closed.
     *
     * @return bool
     */
    public function isOpen()
    {
        return $this->delegate->isOpen();
    }

    /**
     * Checks whether the state of the filter collection is clean.
     *
     * @return boolean True, if the filter collection is clean.
     */
    public function isFiltersStateClean()
    {
        return $this->delegate->isFiltersStateClean();
    }

    /**
     * Helper method to initialize a lazy loading proxy or persistent collection.
     *
     * This method is a no-op for other objects
     *
     * @param object $obj
     */
    public function initializeObject($obj)
    {
        return $this->delegate->initializeObject($obj);
    }

    /**
     * Checks whether the Entity Manager has filters.
     *
     * @return True, if the EM has a filter collection.
     */
    public function hasFilters()
    {
        return $this->delegate->hasFilters();
    }

    /**
     * Gets the UnitOfWork used by the EntityManager to coordinate operations.
     *
     * @return \Doctrine\ORM\UnitOfWork
     */
    public function getUnitOfWork()
    {
        return $this->delegate->getUnitOfWork();
    }

    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     * @return EntityRepository The repository class.
     */
    public function getRepository($className)
    {
        $repository = $this->delegate->getRepository($className);

        if ($repository instanceof \Symfony\Component\DependencyInjection\ContainerAwareInterface) {
            $repository->setContainer($this->container);

            return $repository;
        }

        if (null !== $metadata = $this->container->get("jms_di_extra.metadata.metadata_factory")->getMetadataForClass(get_class($repository))) {
            foreach ($metadata->classMetadata as $classMetadata) {
                foreach ($classMetadata->methodCalls as $call) {
                    list($method, $arguments) = $call;
                    call_user_func_array(array($repository, $method), $this->prepareArguments($arguments));
                }
            }
        }

        return $repository;
    }

    /**
     * Gets a reference to the entity identified by the given type and identifier
     * without actually loading it, if the entity is not yet loaded.
     *
     * @param string $entityName The name of the entity type.
     * @param mixed $id The entity identifier.
     * @return object The entity reference.
     */
    public function getReference($entityName, $id)
    {
        return $this->delegate->getReference($entityName, $id);
    }

    /**
     * Gets the proxy factory used by the EntityManager to create entity proxies.
     *
     * @return ProxyFactory
     */
    public function getProxyFactory()
    {
        return $this->delegate->getProxyFactory();
    }

    /**
     * Gets a partial reference to the entity identified by the given type and identifier
     * without actually loading it, if the entity is not yet loaded.
     *
     * The returned reference may be a partial object if the entity is not yet loaded/managed.
     * If it is a partial object it will not initialize the rest of the entity state on access.
     * Thus you can only ever safely access the identifier of an entity obtained through
     * this method.
     *
     * The use-cases for partial references involve maintaining bidirectional associations
     * without loading one side of the association or to update an entity without loading it.
     * Note, however, that in the latter case the original (persistent) entity data will
     * never be visible to the application (especially not event listeners) as it will
     * never be loaded in the first place.
     *
     * @param string $entityName The name of the entity type.
     * @param mixed $identifier The entity identifier.
     * @return object The (partial) entity reference.
     */
    public function getPartialReference($entityName, $identifier)
    {
        return $this->delegate->getPartialReference($entityName, $identifier);
    }

    /**
     * Gets the metadata factory used to gather the metadata of classes.
     *
     * @return \Doctrine\ORM\Mapping\ClassMetadataFactory
     */
    public function getMetadataFactory()
    {
        return $this->delegate->getMetadataFactory();
    }

    /**
     * Gets a hydrator for the given hydration mode.
     *
     * This method caches the hydrator instances which is used for all queries that don't
     * selectively iterate over the result.
     *
     * @param int $hydrationMode
     * @return \Doctrine\ORM\Internal\Hydration\AbstractHydrator
     */
    public function getHydrator($hydrationMode)
    {
        return $this->delegate->getHydrator($hydrationMode);
    }

    /**
     * Gets the enabled filters.
     *
     * @return FilterCollection The active filter collection.
     */
    public function getFilters()
    {
        return $this->delegate->getFilters();
    }

    /**
     * Gets an ExpressionBuilder used for object-oriented construction of query expressions.
     *
     * Example:
     *
     * <code>
     *     $qb = $em->createQueryBuilder();
     *     $expr = $em->getExpressionBuilder();
     *     $qb->select('u')->from('User', 'u')
     *         ->where($expr->orX($expr->eq('u.id', 1), $expr->eq('u.id', 2)));
     * </code>
     *
     * @return \Doctrine\ORM\Query\Expr
     */
    public function getExpressionBuilder()
    {
        return $this->delegate->getExpressionBuilder();
    }

    /**
     * Gets the EventManager used by the EntityManager.
     *
     * @return \Doctrine\Common\EventManager
     */
    public function getEventManager()
    {
        return $this->delegate->getEventManager();
    }

    /**
     * Gets the database connection object used by the EntityManager.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->delegate->getConnection();
    }

    /**
     * Gets the Configuration used by the EntityManager.
     *
     * @return \Doctrine\ORM\Configuration
     */
    public function getConfiguration()
    {
        return $this->delegate->getConfiguration();
    }

    /**
     * Returns the ORM metadata descriptor for a class.
     *
     * The class name must be the fully-qualified class name without a leading backslash
     * (as it is returned by get_class($obj)) or an aliased class name.
     *
     * Examples:
     * MyProject\Domain\User
     * sales:PriceRequest
     *
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     * @internal Performance-sensitive method.
     */
    public function getClassMetadata($className)
    {
        return $this->delegate->getClassMetadata($className);
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     *
     * If an entity is explicitly passed to this method only this entity and
     * the cascade-persist semantics + scheduled inserts/removals are synchronized.
     *
     * @param object $entity
     * @throws \Doctrine\ORM\OptimisticLockException If a version check on an entity that
     *         makes use of optimistic locking fails.
     */
    public function flush($entity = NULL)
    {
        return $this->delegate->flush($entity);
    }

    /**
     * Finds an Entity by its identifier.
     *
     * @param string $entityName
     * @param mixed $id
     * @param integer $lockMode
     * @param integer $lockVersion
     *
     * @return object
     */
    public function find($entityName, $id, $lockMode = 0, $lockVersion = NULL)
    {
        return $this->delegate->find($entityName, $id, $lockMode, $lockVersion);
    }

    /**
     * Detaches an entity from the EntityManager, causing a managed entity to
     * become detached.  Unflushed changes made to the entity if any
     * (including removal of the entity), will not be synchronized to the database.
     * Entities which previously referenced the detached entity will continue to
     * reference it.
     *
     * @param object $entity The entity to detach.
     */
    public function detach($entity)
    {
        return $this->delegate->detach($entity);
    }

    /**
     * Create a QueryBuilder instance
     *
     * @return QueryBuilder $qb
     */
    public function createQueryBuilder()
    {
        return $this->delegate->createQueryBuilder();
    }

    /**
     * Creates a new Query object.
     *
     * @param string $dql The DQL string.
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($dql = '')
    {
        return $this->delegate->createQuery($dql);
    }

    /**
     * Creates a native SQL query.
     *
     * @param string $sql
     * @param ResultSetMapping $rsm The ResultSetMapping to use.
     * @return NativeQuery
     */
    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        return $this->delegate->createNativeQuery($sql, $rsm);
    }

    /**
     * Creates a Query from a named query.
     *
     * @param string $name
     * @return \Doctrine\ORM\Query
     */
    public function createNamedQuery($name)
    {
        return $this->delegate->createNamedQuery($name);
    }

    /**
     * Creates a NativeQuery from a named native query.
     *
     * @param string $name
     * @return \Doctrine\ORM\NativeQuery
     */
    public function createNamedNativeQuery($name)
    {
        return $this->delegate->createNamedNativeQuery($name);
    }

    /**
     * Creates a copy of the given entity. Can create a shallow or a deep copy.
     *
     * @param object $entity  The entity to copy.
     * @return object  The new entity.
     * @todo Implementation need. This is necessary since $e2 = clone $e1; throws an E_FATAL when access anything on $e:
     * Fatal error: Maximum function nesting level of '100' reached, aborting!
     */
    public function copy($entity, $deep = false)
    {
        return $this->delegate->copy($entity, $deep);
    }

    /**
     * Determines whether an entity instance is managed in this EntityManager.
     *
     * @param object $entity
     * @return boolean TRUE if this EntityManager currently manages the given entity, FALSE otherwise.
     */
    public function contains($entity)
    {
        return $this->delegate->contains($entity);
    }

    /**
     * Commits a transaction on the underlying database connection.
     */
    public function commit()
    {
        return $this->delegate->commit();
    }

    /**
     * Closes the EntityManager. All entities that are currently managed
     * by this EntityManager become detached. The EntityManager may no longer
     * be used after it is closed.
     */
    public function close()
    {
        return $this->delegate->close();
    }

    /**
     * Clears the EntityManager. All entities that are currently managed
     * by this EntityManager become detached.
     *
     * @param string $entityName if given, only entities of this type will get detached
     */
    public function clear($entityName = NULL)
    {
        return $this->delegate->clear($entityName);
    }

    /**
     * Starts a transaction on the underlying database connection.
     */
    public function beginTransaction()
    {
        return $this->delegate->beginTransaction();
    }

    public function __construct($objectManager, \Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->delegate = $objectManager;
        $this->container = $container;
    }

    private function prepareArguments(array $arguments)
    {
        $processed = array();
        foreach ($arguments as $arg) {
            if ($arg instanceof \Symfony\Component\DependencyInjection\Reference) {
                $processed[] = $this->container->get((string) $arg, $arg->getInvalidBehavior());
            } else if ($arg instanceof \Symfony\Component\DependencyInjection\Parameter) {
                $processed[] = $this->container->getParameter((string) $arg);
            } else {
                $processed[] = $arg;
            }
        }

        return $processed;
    }
}