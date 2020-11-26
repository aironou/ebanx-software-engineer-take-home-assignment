<?php
namespace App\Listener;

use App\Exceptions\TransactionTypeNotAllowedException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $event->setResponse($this->handleThrowable($event->getThrowable()));
    }

    /**
     * @param \Throwable $throwable
     * @return JsonResponse
     */
    private function handleThrowable(\Throwable $throwable): JsonResponse
    {
        try {
            throw $throwable;
        } catch (NoResultException $exception) {
            return new JsonResponse(0, Response::HTTP_NOT_FOUND);
        } catch (TransactionTypeNotAllowedException|\TypeError $exception) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        } catch (HttpException $exception) {
            return new JsonResponse(null, $exception->getStatusCode());
        } catch (\Throwable $throwable) {
            return new JsonResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}