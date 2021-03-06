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

namespace RM\Component\Client\Hydrator;

use RM\Component\Client\Entity\EntityInterface;

/**
 * Class EntityHydrator.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class EntityHydrator implements HydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate(array $data, string $class): EntityInterface
    {
        if (is_subclass_of($class, EntityInterface::class, true)) {
            return $class::createFromArray($data);
        }

        return new $class(...$data);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $data, string $class): bool
    {
        return class_exists($class);
    }
}
