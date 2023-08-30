<?php

namespace App\Http;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class ApiResponse
{
    private ?string $message = null;

    private ?array $data = null;

    private ?array $errors = null;

    private int $status;

    private ?Exception $exception = null;

    public function __construct(
        private readonly JsonResponse $jsonResponse,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getResponse(): JsonResponse
    {
        $data = $this->getData();

        if ($this->getMessage()) {
            $data['meta']['message'] = $this->getMessage();
            $data['meta']['code'] = $this->getStatus();
        }

        if ($this->getErrors()) {
            $data['meta']['errors'] = $this->getErrors();
        }

        if ($this->exception && config('app.debug')) {
            $data['meta']['trace'] = $this->exception->getTraceAsString();
        }

        try {
            $this->jsonResponse->setData($data);
            $this->setStatus($this->getStatus());
        } catch (Exception $exception) {
            $this->logger->critical('Unable to create JSON response', [
                'code' => $exception->getCode(),
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            $this->jsonResponse->setData([
                'meta' => [
                    'message' => 'Something went wrong',
                    'errors' => ['Something went wrong'],
                ],
            ]);
            $this->setStatus(Response::HTTP_BAD_REQUEST);
        }

        preg_match('~^[1-5]\d\d$~', (string) $this->getStatus()) ?
            $this->jsonResponse->setStatusCode($this->getStatus()) :
            $this->jsonResponse->setStatusCode(Response::HTTP_BAD_REQUEST);

        return $this->jsonResponse;
    }

    public function setException(Exception $exception): ApiResponse
    {
        $this->exception = $exception;

        is_int($exception->getCode()) && $exception->getCode() > 0 ?
            $this->setStatus($exception->getCode()) :
            $this->setStatus(Response::HTTP_BAD_REQUEST);

        $this->setMessage($exception->getMessage());

        if (isset($exception->validator)) {
            $this->setErrors(
                $exception->validator->errors()->all()
            );
        }

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): ApiResponse
    {
        $this->data = $data;

        return $this;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status ?? Response::HTTP_OK;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
