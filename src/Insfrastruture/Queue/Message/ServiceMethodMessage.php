<?php

namespace App\Infrastructure\Queue\Message;

class ServiceMethodMessage
{
    public function __construct(
        private readonly string $serviceName,
        private readonly string $method,
        private readonly array $params
    )
    {
    }
    
    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
    
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    
    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }
}
