<?php

namespace RM\Component\Client;

use RM\Component\Client\Exception\FactoryException;
use RM\Component\Client\Hydrator\EntityHydrator;
use RM\Component\Client\Hydrator\HydratorInterface;
use RM\Component\Client\Repository\RepositoryFactory;
use RM\Component\Client\Repository\RepositoryFactoryInterface;
use RM\Component\Client\Security\Authenticator\AuthenticatorFactory;
use RM\Component\Client\Security\Authenticator\AuthenticatorFactoryInterface;
use RM\Component\Client\Security\Storage\TokenStorage;
use RM\Component\Client\Transport\ThrowableTransport;
use RM\Component\Client\Transport\TransportInterface;

/**
 * Class ClientFactory
 *
 * @package RM\Component\Client
 * @author  h1karo <h1karo@outlook.com>
 */
class ClientFactory
{
    private TransportInterface $transport;
    private bool $throwable = true;
    private ?RepositoryFactoryInterface $repositoryFactory = null;
    private ?HydratorInterface $hydrator = null;
    private ?RepositoryRegistryInterface $repositoryRegistry = null;
    private ?AuthenticatorFactoryInterface $authenticatorFactory = null;
    private ?TokenStorage $tokenStorage = null;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public static function create(TransportInterface $transport): self
    {
        return new self($transport);
    }

    public function setTransport(TransportInterface $transport): self
    {
        $this->transport = $transport;
        return $this;
    }

    public function setThrowable(bool $throwable): ClientFactory
    {
        $this->throwable = $throwable;
        return $this;
    }

    public function setHydrator(HydratorInterface $hydrator): ClientFactory
    {
        $this->hydrator = $hydrator;
        $this->repositoryFactory = null;
        return $this;
    }

    public function setRepositoryFactory(RepositoryFactoryInterface $repositoryFactory): self
    {
        $this->repositoryFactory = $repositoryFactory;
        $this->repositoryRegistry = null;
        return $this;
    }

    public function setRepositoryRegistry(RepositoryRegistryInterface $repositoryRegistry): self
    {
        $this->repositoryRegistry = $repositoryRegistry;
        $this->hydrator = null;
        $this->repositoryFactory = null;
        return $this;
    }

    public function setTokenStorage(TokenStorage $tokenStorage): ClientFactory
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticatorFactory = null;
        return $this;
    }

    public function setAuthenticatorFactory(AuthenticatorFactoryInterface $authenticatorFactory): ClientFactory
    {
        $this->authenticatorFactory = $authenticatorFactory;
        $this->tokenStorage = null;
        return $this;
    }

    public function build(): ClientInterface
    {
        $transport = $this->transport;
        if ($this->throwable && !$transport instanceof ThrowableTransport) {
            $transport = new ThrowableTransport($transport);
        }

        $tokenStorage = $this->tokenStorage;

        $hydrator = $this->hydrator;
        if ($hydrator === null) {
            $hydrator = new EntityHydrator();
        }

        $repositoryFactory = $this->repositoryFactory;
        if ($repositoryFactory === null) {
            $repositoryFactory = new RepositoryFactory($transport, $hydrator);
        }

        $repositoryRegistry = $this->repositoryRegistry;
        if ($repositoryRegistry === null) {
            $repositoryRegistry = new RepositoryRegistry($repositoryFactory);
        }

        $authenticatorFactory = $this->authenticatorFactory;
        if ($authenticatorFactory === null) {
            if ($tokenStorage === null) {
                throw new FactoryException('You must set up a token storage or authenticator factory.');
            }

            $authenticatorFactory = new AuthenticatorFactory($transport, $tokenStorage);
        }

        return new Client($transport, $repositoryRegistry, $authenticatorFactory);
    }
}
