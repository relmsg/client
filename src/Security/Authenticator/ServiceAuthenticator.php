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

namespace RM\Component\Client\Security\Authenticator;

use BadMethodCallException;
use RM\Component\Client\Entity\Application;
use RM\Component\Client\Entity\Identifiable;
use RM\Component\Client\Security\Credentials\AuthorizationInterface;
use RM\Component\Client\Security\Credentials\Token;
use RM\Standard\Message\Action;
use RM\Standard\Message\MessageInterface;

/**
 * Class ServiceAuthenticator provides ability to authenticate the application.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see    https://dev.relmsg.ru/security/service
 */
class ServiceAuthenticator extends DirectAuthenticator
{
    private string $id;
    private string $secret;

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createMessage(): MessageInterface
    {
        return new Action(
            'auth.authorize',
            [
                'application' => $this->id,
                'secret' => $this->secret,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createAuthorization(string $credentials, object $entity): AuthorizationInterface
    {
        if ($entity instanceof Identifiable) {
            return new Token($credentials, $entity->getId());
        }

        throw new BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return Application::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function getTokenType(): string
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    protected function getObjectKey(): string
    {
        return 'application';
    }
}
