<?php

namespace RM\Component\Client\Transport;

use RM\Standard\Message\MessageInterface;

/**
 * Interface TransportInterface
 *
 * @package RM\Component\Client\Transport
 * @author  h1karo <h1karo@outlook.com>
 */
interface TransportInterface
{
    public const DOMAIN = 'apis.relmsg.ru';
    public const VERSION = '1.0';

    /**
     * Sends the message into server and receive a response message.
     *
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    public function send(MessageInterface $message): MessageInterface;
}
