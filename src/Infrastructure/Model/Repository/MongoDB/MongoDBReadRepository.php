<?php

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\MongoDB;

use MongoDB\Model\BSONDocument;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Fields as FieldsInstance;

class MongoDBReadRepository extends BaseMongoDBRepository implements ReadRepository
{
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
        $options = $this->options;
        $fields = ($fields) ? new FieldsInstance($this->getColumns($fields)) : null;

        $this->fetchSpecificFields($fields, $options);

        /** @var BSONDocument $result */
        $result = $this->getCollection()->findOne($this->applyIdFiltering($id), $options);

        return (!empty($result)) ? $this->recursiveArrayCopy($result) : [];
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
        $collection = $this->getCollection();
        $options = $this->options;
        $filterArray = [];

        $this->applyFiltering($filter, $filterArray);
        $this->applySorting($sort, $options);
        $this->fetchSpecificFields(new FieldsInstance($this->getColumns($fields)), $options);

        $result = $collection->find($filterArray, $options)->toArray();

        foreach ($result as &$r) {
            $r = $this->recursiveArrayCopy($r);
        }

        return $result;
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
        $collection = $this->getCollection();
        $options = $this->options;
        $filterArray = [];

        $this->applyFiltering($filter, $filterArray);
        $this->applySorting($sort, $options);

        $fields = $this->getColumns(new FieldsInstance($distinctFields->get()));

        if (count($fields) > 1) {
            throw new \Exception('Mongo cannot select more than one field when calling distinct.');
        }

        $results = (array) $collection->distinct(array_shift($fields), $filterArray, $this->options);

        foreach ($results as &$r) {
            $r = $this->recursiveArrayCopy($r);
        }

        return $results;
    }
}
