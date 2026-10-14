<?php


namespace PixellWeb\Vosfactures\app;


use Throwable;

class VosfacturesException extends \Exception
{
    /**
     * ReferentielApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Log::channel(config('vosfactures.logging_channel'))->alert($message);
    }
}
