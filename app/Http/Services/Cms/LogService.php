<?php

namespace App\Http\Services\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class LogService
{
    private ?Request $request = null;
    private ?Throwable $exception = null;
    private array $context = [];

    /**
     * Set the request object for logging
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set a generic exception for logging
     */
    public function setException(Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Set a validation exception specifically
     */
    public function setValidationException(ValidationException $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Add custom context data to the log
     */
    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    /**
     * Log a debug message
     */
    public function debug(string $message): void
    {
        Log::debug($message, $this->buildContext());
    }

    /**
     * Log an info message
     */
    public function info(string $message): void
    {
        Log::info($message, $this->buildContext());
    }

    /**
     * Log an error message
     */
    public function error(string $message): void
    {
        Log::error($message, $this->buildContext());
    }

    /**
     * Log a warning message
     */
    public function warning(string $message): void
    {
        Log::warning($message, $this->buildContext());
    }

    /**
     * Build the context array for logging
     */
    private function buildContext(): array
    {
        $context = $this->context;

        if ($this->request) {
            $buildContext['who'] = [
                'id' => $this->request->user()?->id ?? null,
                'name' => $this->request->user()?->name ?? null
            ];
            $buildContext['input'] = $this->request->except(['password', 'passwordConfirmation', 'currentPassword']);
            $buildContext['ip'] = $this->request->ip();
            $buildContext['method'] = $this->request->method();
            $buildContext['url'] = $this->request->fullUrl();
        }

        if ($this->exception) {
            if ($this->exception instanceof ValidationException) {
                $buildContext['validation_errors'] = $this->exception->errors();
            } else {
                $buildContext['error'] = $this->exception->getMessage();
                $buildContext['trace'] = $this->exception->getTraceAsString();
            }
        }

        $buildContext['results'] = $context;

        return $buildContext;
    }

    /**
     * Reset the service state
     */
    public function reset(): self
    {
        $this->request = null;
        $this->exception = null;
        $this->context = [];
        return $this;
    }
}