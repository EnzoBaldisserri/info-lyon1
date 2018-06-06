<?php

namespace App\Controller\Api;

use App\Entity\User\Notification;
use App\Repository\User\NotificationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/notification", name="notification_")
 */
class NotificationApiController extends BaseController
{
    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     */
    public function delete(Notification $notification, TranslatorInterface $translator)
    {
        if ($notification->getUser() !== $this->getUser()) {
            return $this->createJsonResponse([
                'error' => $translator->trans('error.notification.not_owner'),
            ], 403);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($notification);
        $em->flush();

        return $this->createJsonResponse();
    }

    /**
     * @Route("/clear", name="clear")
     * @Method({"DELETE"})
     */
    public function clear(NotificationRepository $notificationRepository, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === null) {
            return $this->createJsonResponse([
                'error' => $translator->trans('error.notification.not_user'),
            ]);
        }

        $notificationRepository->clearForUser($user);

        return $this->createJsonResponse();
    }
}
