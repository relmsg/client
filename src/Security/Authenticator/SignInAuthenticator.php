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
use RM\Component\Client\Entity\Identifiable;
use RM\Component\Client\Entity\User;
use RM\Component\Client\Security\Credentials\AuthorizationInterface;
use RM\Component\Client\Security\Credentials\Request;
use RM\Component\Client\Security\Credentials\Token;
use RM\Standard\Message\Action;
use RM\Standard\Message\MessageInterface;

/**
 * Class SignInAuthenticator provides ability to complete the authentication started by {@see CodeAuthenticator}.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see    https://dev.relmsg.ru/security/user
 *
 * @method User authenticate()
 */
class SignInAuthenticator extends DirectAuthenticator implements StatefulAuthenticatorInterface
{
    private ?string $phone;
    private ?string $request;
    private ?string $code;

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Returns the identifier of the auth request.
     *
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * Sets the identifier of the auth request.
     *
     * @param string $request
     *
     * @return self
     */
    public function setRequest(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Sets the auth code in authenticator.
     *
     * @param string $code
     *
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    protected function createMessage(): MessageInterface
    {
        return new Action(
            'auth.signIn',
            [
                'phone' => $this->phone,
                'request' => $this->request,
                'code' => $this->code,
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
    protected function getObjectKey(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function getTokenType(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return User::class;
    }

    /**
     * {@inheritdoc}
     */
    public function store(): self
    {
        if (null !== $this->request && null !== $this->phone) {
            $authorization = new Request($this->request, $this->phone);
            $this->storage->set(static::getTokenType(), $authorization);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function restore(): self
    {
        $this->clear();

        $authorization = $this->storage->get(static::getTokenType());
        if ($authorization instanceof Request) {
            $this->request = $authorization->getId();
            $this->phone = $authorization->getPhone();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): self
    {
        $this->phone = null;
        $this->request = null;
        $this->code = null;

        return $this;
    }
}
