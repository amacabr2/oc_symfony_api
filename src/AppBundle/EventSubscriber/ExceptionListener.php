<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Normalizer\NormalizerInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface {

    /**
     * @var Serializer
     */
    private $serializer;

    private $normalizers;

    /**
     * ExceptionListener constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer) {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents() {
        return [
          KernelEvents::EXCEPTION =>['processException', 255]
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function processException(GetResponseForExceptionEvent $event) {

        $result = null;

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($event)) {
                $result = $normalizer->normalize($event->getException());
                break;
            }
        }

        if ($result == null) {
            $result['code'] = Response::HTTP_BAD_REQUEST;
            $result['body'] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $event->getException()->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');
        $event->setResponse(new Response($body, $result['code']));

    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function addNormalizer(NormalizerInterface $normalizer) {
        $this->normalizers[] = $normalizer;
    }

}