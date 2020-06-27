<?php
/*
 * This file is a part of Relations Messenger Client.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client
 * @link      https://dev.relmsg.ru/packages/client
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Component\Client\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Entity
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author  Oleg Kozlov <h1karo@outlook.com>
 * @internal
 */
class Entity
{
    public ?string $repositoryClass = null;
}
