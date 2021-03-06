<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 15/02/16
 * Time: 21:00.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\MongoDB;

use MongoDB\Client;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Mapping;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Page;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\PageRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;

/**
 * Class MongoDBRepository.
 */
class MongoDBRepository implements ReadRepository, WriteRepository, PageRepository
{
    /**
     * @var string
     */
    protected $databaseName;
    /**
     * @var string
     */
    protected $collectionName;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var \MongoDB\Collection
     */
    protected $collection;

    /**
     * If not using ObjectID, field being used as primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /** @var MongoDBReadRepository */
    protected $readRepository;

    /** @var MongoDBWriteRepository */
    protected $writeRepository;

    /** @var MongoDBPageRepository */
    protected $pageRepository;

    /** @var Mapping */
    protected $mapping;

    /**
     * MongoDBRepository constructor.
     *
     * @param Mapping $mapping
     * @param Client  $client
     * @param $databaseName
     * @param $collectionName
     * @param array $options
     */
    public function __construct(Mapping $mapping, Client $client, $databaseName, $collectionName, array $options = [])
    {
        $this->mapping = $mapping;
        $this->client = $client;
        $this->databaseName = (string) $databaseName;
        $this->collectionName = (string) $collectionName;
        $this->options = $options;

        $this->writeRepository = MongoDBWriteRepository::create(
            $this->mapping,
            $this->client,
            $this->databaseName,
            $this->collectionName,
            $this->options
        );

        $this->readRepository = MongoDBReadRepository::create(
            $this->mapping,
            $this->client,
            $this->databaseName,
            $this->collectionName,
            $this->options
        );

        $this->pageRepository = MongoDBPageRepository::create(
            $this->mapping,
            $this->client,
            $this->databaseName,
            $this->collectionName,
            $this->options
        );
    }

    /**
     * @return \MongoDB\Collection
     */
    public function getDriver()
    {
        return $this->readRepository->getDriver();
    }

    /**
     * Returns the total amount of elements in the repository given the restrictions provided by the Filter object.
     *
     * @param Filter|null $filter
     *
     * @return int
     */
    public function count(Filter $filter = null) : int
    {
        return $this->writeRepository->count($filter);
    }

    /**
     * Retrieves an entity by its id.
     *
     * @param Identity    $id
     * @param Fields|null $fields
     *
     * @return array
     */
    public function find(Identity $id, Fields $fields = null)
    {
        return $this->readRepository->find($id, $fields);
    }

    /**
     * Adds a new entity to the storage.
     *
     * @param Identity $value
     *
     * @return mixed
     */
    public function add(Identity $value)
    {
        return $this->writeRepository->add($value);
    }

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param $id
     *
     * @return bool
     */
    public function exists(Identity $id) : bool
    {
        return $this->writeRepository->exists($id);
    }

    /**
     * Adds a collections of entities to the storage.
     *
     * @param array $values
     *
     * @return mixed
     */
    public function addAll(array $values)
    {
        return $this->writeRepository->addAll($values);
    }

    /**
     * Returns all instances of the type.
     *
     * @param Filter|null $filter
     * @param Sort|null   $sort
     * @param Fields|null $fields
     *
     * @return array
     */
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null) : array
    {
        return $this->readRepository->findBy($filter, $sort, $fields);
    }

    /**
     * Removes the entity with the given id.
     *
     * @param $id
     */
    public function remove(Identity $id)
    {
        $this->writeRepository->remove($id);
    }

    /**
     * Removes all elements in the repository given the restrictions provided by the Filter object.
     * If $filter is null, all the repository data will be deleted.
     *
     * @param Filter $filter
     */
    public function removeAll(Filter $filter = null)
    {
        $this->writeRepository->removeAll($filter);
    }

    /**
     * Returns a Page of entities meeting the paging restriction provided in the Pageable object.
     *
     * @param Pageable $pageable
     *
     * @return Page
     */
    public function findAll(Pageable $pageable = null) : Page
    {
        return $this->pageRepository->findAll($pageable);
    }

    /**
     * Returns all instances of the type meeting $distinctFields values.
     *
     * @param Fields      $distinctFields
     * @param Filter|null $filter
     * @param Sort|null   $sort
     *
     * @return array
     *
     * @throws \Exception
     */
    public function findByDistinct(Fields $distinctFields, Filter $filter = null, Sort $sort = null) : array
    {
        return $this->readRepository->findByDistinct($distinctFields, $filter, $sort);
    }

    /**
     * Repository data is added or removed as a whole block.
     * Must work or fail and rollback any persisted/erased data.
     *
     * @param callable $transaction
     *
     * @throws \Exception
     */
    public function transactional(callable $transaction)
    {
        $this->writeRepository->transactional($transaction);
    }
}
