<?php

namespace App\Exception;


use Throwable;

class TranslatedException extends \Exception
{
    protected $arguments;

    /**
     * TranslatedException constructor.<br>
     *
     * @param string $message
     * @param array $arguments
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", array $arguments = [], int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->arguments = $arguments;
    }

    /**
     * @return array The arguments used to translate the message
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
