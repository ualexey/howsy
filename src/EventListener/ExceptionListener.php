<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {

        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $responseContent = [
            "error" => $message,

        ];

        $response = new Response();
        $response->setCharset('UTF-8');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($responseContent));
        $event->setResponse($response);
    }

}
