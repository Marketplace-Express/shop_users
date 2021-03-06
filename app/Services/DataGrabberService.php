<?php
/**
 * User: Wajdi Jurry
 * Date: 08/08/2020
 * Time: ١٢:٢٦ م
 */

namespace App\Services;


use Jurry\RabbitMQ\Handler\RequestSender;

class DataGrabberService
{
    /** @var RequestSender */
    private $requestSender;

    /**
     * DataGrabberService constructor.
     * @param RequestSender $requestSender
     */
    public function __construct(RequestSender $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    /**
     * @param string $queue
     * @param string $service
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     * @throws \ErrorException
     */
    public function fetch(string $queue, string $service, string $method, ...$args)
    {
        return $this->requestSender
            ->setQueueName($queue)
            ->setService($service)
            ->setMethod($method)
            ->setData($args)
            ->sendSync();
    }
}