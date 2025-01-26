<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class ShutdownHandler
{
    public function __construct(
        private Request $request,
        private HttpErrorHandler $errorHandler,
        private bool $displayErrorDetails
    ) {
    }

    public function __invoke()
    {
        $error = error_get_last();
        if (!$error) {
            return;
        }

        $message = $this->getErrorMessage($error);
        $exception = new HttpInternalServerErrorException($this->request, $message);
        $response = $this->errorHandler->__invoke(
            $this->request,
            $exception,
            $this->displayErrorDetails,
            false,
            false,
        );

        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }

    /**
     * @param array{type: int, message: string, file: string, line: int}|null$error
     */
    private function getErrorMessage(?array $error = null): string
    {
        if (!$this->displayErrorDetails) {
            return 'An error while processing your request. Please try again later.';
        }

        $errorFile = $error['file'] ?? null;
        $errorLine = $error['line'] ?? null;
        $errorMessage = $error['message'] ?? null;
        $errorType = $error['type'] ?? null;

        return match ($errorType) {
            E_USER_WARNING => "WARNING: {$errorMessage}",
            E_USER_NOTICE => "NOTICE: {$errorMessage}",
            default => "FATAL ERROR: {$errorMessage}. on line {$errorLine} in file {$errorFile}."
        };
    }
}
