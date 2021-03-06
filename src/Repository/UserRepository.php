<?php
/*
 * This file is a part of Relations Messenger Client.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client
 * @link      https://dev.relmsg.ru/packages/client
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   https://legal.relmsg.ru/licenses/client
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Component\Client\Repository;

use RM\Component\Client\Entity\User;
use RM\Standard\Message\Action;

/**
 * Class UserRepository.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @method User   get(string $id)
 * @method User[] getAll(string[] $ids)
 */
class UserRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    final protected function generateGetAction(array $ids): Action
    {
        return new Action('users.get', ['id' => $ids]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return User::class;
    }
}
