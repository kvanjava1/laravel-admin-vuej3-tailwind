<?php

namespace App\Http\Libraries;

class Message
{
    protected string $code;
    protected string $messageHead;
    protected ?array $messageDetail = [];
    protected ?array $data = [];
    protected int $httpCode;

    public function setHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setMessageHead(string $messageHead): self
    {
        $this->messageHead = $messageHead;
        return $this;
    }

    public function getMessageHead(): string
    {
        return $this->messageHead;
    }

    public function setMessageDetail(array $messageDetail): self
    {
        $this->messageDetail = $messageDetail;
        return $this;
    }

    public function getMessageDetail(): array
    {
        return $this->messageDetail;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => [
                'head' => $this->messageHead,
                'detail' => $this->messageDetail,
            ],
            'data' => $this->data
        ];
    }
}