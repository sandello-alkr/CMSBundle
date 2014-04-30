<?
namespace alkr\CMSBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;

class RedirectExceptionListener
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $router;

    function __construct(EntityManager $em, $router)
    {
        $this->router = $router;
        $this->em = $em;
    }


    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function checkRedirect(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            preg_match('/(.+[\wа-я]+)(\/?)$/u', $event->getRequest()->get('url'), $url);
            $redirect = $this->em->getRepository('CMSBundle:Redirect')->findOneByOldUrl($url[1]);
            if (!is_null($redirect)) {
                $event->setResponse(new RedirectResponse($this->router->generate('page_show',array('url'=>$redirect->getNewUrl()),true)));
            }
        }
    }
}