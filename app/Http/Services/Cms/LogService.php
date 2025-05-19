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

    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }


    public function setException(Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }


    public function setValidationException(ValidationException $exception): self
    {
        $this->exception = $exception;
        return $this;
    }


    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    public function debug(string $message): void
    {
        Log::debug($message, $this->buildContext());
    }

    public function info(string $message): void
    {
        Log::info($message, $this->buildContext());
    }

    public function error(string $message): void
    {
        Log::error($message, $this->buildContext());
    }

    public function warning(string $message): void
    {
        Log::warning($message, $this->buildContext());
    }

    private function buildContext(): array
    {
        $context = $this->context;
        
        if ($this->request) {
            $buildContext['user'] = [
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
                $error['error'] = $this->exception->errors();
                $error['trace'] = '';
            } else {
                $error['error'] = $this->exception->getMessage();
                $error['trace'] = $this->exception->getTraceAsString();
            }
        }

        $buildContext['error'] = $error ?? [];
        $buildContext['additional'] = $context;

        return $buildContext;
    }

    public function reset(): self
    {
        $this->request = null;
        $this->exception = null;
        $this->context = [];
        return $this;
    }
}