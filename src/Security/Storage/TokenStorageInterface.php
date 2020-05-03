<?php

namespace RM\Component\Client\Security\Storage;

/**
 * Interface TokenStorageInterface
 *
 * @package RM\Component\Client\Security
 * @author  h1karo <h1karo@outlook.com>
 */
interface TokenStorageInterface
{
    /**
     * Sets the token into storage by type.
     *
     * @param string $type
     * @param string $token
     */
    public function set(string $type, string $token): void;

    /**
     * Get the token by type.
     *
     * @param string $type
     *
     * @return string|null
     */
    public function get(string $type): ?string;
}
