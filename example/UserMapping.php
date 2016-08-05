<?php

namespace NilPortugues\Example;

use DateTime;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Mapping;

class UserMapping implements Mapping
{
    /**
     * Name of the identity field in storage.
     *
     * @return string
     */
    public function identity() : string
    {
        return 'user_id';
    }

    /**
     * Returns the table name.
     *
     * @return string
     */
    public function name() : string
    {
        return 'users';
    }

    /**
     * Keys are object properties without property defined in identity(). Values its SQL column equivalents.
     *
     * @return array
     */
    public function map() : array
    {
        return [
            'userId' => 'user_id',
            'username' => 'username',
            'alias' => 'public_username',
            'email' => 'email',
            'registeredOn.date' => 'created_at',
        ];
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public function fromArray(array $data)
    {
        return new User(
            $data['user_id'],
            $data['username'],
            $data['public_username'],
            $data['email'],
            new DateTime($data['created_at'])
        );
    }

    /**
     * The automatic generated strategy used will be the data-store's if set to true.
     *
     * @return bool
     */
    public function autoGenerateId() : bool
    {
        return true;
    }
}
