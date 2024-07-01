<?php

namespace Dotdigital\Exception;

use Psr\Http\Message\ResponseInterface;

class ResponseValidationException extends \ErrorException implements ExceptionInterface
{
    /**
     * @var array
     */
    private $details;
    private $headers;

    /**
     * @param ResponseInterface $errorResponse
     * @return ResponseValidationException
     */
    public static function fromErrorResponse(
        ResponseInterface $errorResponse
    ): ResponseValidationException {
        $headers = $errorResponse->getHeaders();
        $content = $errorResponse->getBody()->getContents();
        $status = $errorResponse->getStatusCode();
        $exception = new self($content, $status);
        $exception->setDetails($content);
        $exception->setMessage($content);
        $exception->setHeaders($headers);
        return $exception;
    }

    /**
     * @return array
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param $errorResponse
     * @return void
     */
    public function setDetails($errorResponse)
    {
        $errorData = $this->decodeResponse($errorResponse);
        $this->details = $errorData['details'] ?? [];
    }

    /**
     * @param string $responseBody
     * @return void
     */
    public function setMessage(string $responseBody): void
    {
        $decoded = $this->decodeResponse($responseBody);
        $this->message = sprintf(
            '%s - %s',
            $decoded['errorCode'],
            $decoded['description']
        );
    }

    public function setHeaders(string|array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param $responseBody
     * @return mixed|string[]
     */
    private function decodeResponse($responseBody)
    {
        $decoded = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decoded = [
                'description' => sprintf('Error decoding response - %s', json_last_error_msg()),
                'errorCode' => 'Error Unknown',
                'details' => [],
           ];
        }
        return $decoded;
    }
}
