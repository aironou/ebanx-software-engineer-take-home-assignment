<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
    /**
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     *
     * @Route(
     *     "/reset",
     *     name="reset-reset",
     *     methods={"POST"}
     * )
     */
    public function reset(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->run(new StringInput('doctrine:database:drop --force --quiet'));
        $application->run(new StringInput('doctrine:migrations:migrate --no-interaction --quiet'));
        return new Response('OK', Response::HTTP_OK);
    }
}