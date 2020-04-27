<?php

namespace RM\Component\Client;

use InvalidArgumentException;
use RM\Component\Client\Auth\AuthenticatorInterface;
use RM\Component\Client\Auth\TokenStorageInterface;
use RM\Component\Client\Exception\ErrorException;
use RM\Component\Client\Exception\UnexpectedMessageException;
use RM\Component\Client\Hydrator\EntityHydrator;
use RM\Component\Client\Hydrator\HydratorInterface;
use RM\Component\Client\Repository\RepositoryFactory;
use RM\Component\Client\Repository\RepositoryFactoryInterface;
use RM\Component\Client\Transport\TransportInterface;
use RM\Standard\Message\Error;
use RM\Standard\Message\MessageInterface;
use RM\Standard\Message\Response;

/**
 * Class Client
 *
 * @package RM\Component\Client
 * @author  h1karo <h1karo@outlook.com>
 */
class Client extends RepositoryRegistry implements ClientInterface
{
    private const AUTH_PROVIDERS = [];

    private TransportInterface $transport;

    public function __construct(
        TransportInterface $transport,
        ?HydratorInterface $hydrator = null,
        ?RepositoryFactoryInterface $factory = null
    ) {
        $hydrator = $hydrator ?? new EntityHydrator($this);
        $factory = $factory ?? new RepositoryFactory($this, $hydrator);
        parent::__construct($factory);

        $this->transport = $transport;
    }

    public function createAuthorization(string $type): AuthenticatorInterface
    {
        $provider = self::AUTH_PROVIDERS[$type] ?? null;
        if ($provider === null) {
            throw new InvalidArgumentException(sprintf('Authorization provider with name `%s` does not exist.', $type));
        }

        return $provider($this, $this->getTokenStorage());
    }

    /**
     * @inheritDoc
     * @throws ErrorException
     */
    public function send(MessageInterface $message): Response
    {
        $response = $this->transport->send($message);

        if ($response instanceof Error) {
            throw new ErrorException($response);
        }

        if (!$response instanceof Response) {
            throw new UnexpectedMessageException($response, Response::class);
        }

        return $response;
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->transport->getTokenStorage();
    }
}