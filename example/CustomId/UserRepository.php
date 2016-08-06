<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 17:59.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Example\CustomId;

use NilPortugues\Foundation\Infrastructure\Model\Repository\MongoDB\MongoDBRepository;
use NilPortugues\Foundation\Infrastructure\Model\Repository\MongoDB\MongoDBRepositoryHydrator;

class UserRepository extends MongoDBRepository
{
    use MongoDBRepositoryHydrator;
}
