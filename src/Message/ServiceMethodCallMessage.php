<?php

namespace App\Message;

class ServiceMethodCallMessage
{

    private string $serviceName;
    private string $methodName;
    private array $params;

    public function __construct ( string $serviceName, string $methodName, array $params )
    {

        $this->serviceName = $serviceName;
        $this->methodName = $methodName;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getServiceName (): string
    {
        return $this->serviceName;
    }

    /**
     * @return string
     */
    public function getMethodName (): string
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getParams (): array
    {
        return $this->params;
    }
}
