<?php

namespace App\Service;

use RuntimeException;
use App\Entity\User\User;
use App\Entity\User\Notification;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class NotificationBuilder
{
    const INFORMATION = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    const TYPE_ICONS = [
        self::INFORMATION => 'info_outline',
        self::SUCCESS => 'done',
        self::WARNING => 'warning',
        self::ERROR => 'report'
    ];

    const DURATION_FLASH = 0;
    const DURATION_PERSIST = 1;

    private $user;
    private $translator;
    private $router;
    private $session;
    private $em;

    private $content;
    private $type;
    private $icon;
    private $link;
    private $duration;
    private $receiver;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router,
        SessionInterface $session,
        EntityManagerInterface $em
    ) {
        $token = $tokenStorage->getToken();
        $this->user = $token ? $token->getUser() : null;
        $this->translator = $translator;
        $this->router = $router;
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * Initialize the builder's properties.
     *
     * @param  int $duration The duration of the notification
     * @return self
     * @throws RuntimeException When the duration isn't valid
     */
    public function newNotification(int $duration = self::DURATION_FLASH): self
    {
        if ($duration < 0 || $duration >= 2) {
            throw new RuntimeException('Duration "' . $duration . '" does not exist');
        }

        $this->content = $this->type = $this->icon = $this->link = $this->receiver = null;
        $this->duration = $duration;

        return $this;
    }

    /**
     * Defines the content of the notification.
     * If $parameters is not set to 'false', $content is translated.
     *
     * @param  string $content    The content
     * @param  array  $parameters The parameters of the translation
     * @return self
     */
    public function setContent(string $content, $parameters = []): self
    {
        if ($parameters !== false) {
            $content = $this->translator->trans($content, $parameters);
        }

        $this->content = $content;

        return $this;
    }

    /**
     * Set the type of the notification.
     * Also defines a default icon based on $type.
     *
     * @param  string $type The type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        if ($this->icon === null) {
            $this->icon = self::TYPE_ICONS[$type];
        }

        return $this;
    }

    /**
     * Set the custom icon to be used.
     * See all available icons here : https://material.io/tools/icons/
     *
     * @param  string $icon The icon
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Defines the path the notification will link to.
     * If $parameters is not set to 'false', the link will be generated
     * from $path and $parameters.
     *
     * @param  string $path       The path
     * @param  array  $parameters The routing parameters
     * @return self
     */
    public function setPath(string $path, Array $parameters = []): self
    {
        $this->link = false !== $parameters ?
            $this->router->generate($path, $parameters)
            : $path;

        return $this;
    }

    /**
     * Set the receiver of the notification.
     * He can only be different from the current user when
     * the duration is set to 'DURATION_PERSIST'.
     *
     * @param  User $receiver The user receiving the notification
     * @return self
     */
    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @throws RuntimeException
     */
    public function save()
    {
        // Pre-save
        if ($this->receiver === null) {
            $this->receiver = $this->user;
        }

        // Validation of the input
        $this->validate();

        // Create a notification
        $newNotification = (new Notification())
            ->setContent($this->content)
            ->setType($this->type)
            ->setIcon($this->icon)
            ->setLink($this->link)
            ->setUser($this->receiver)
        ;

        switch ($this->duration) {
            case self::DURATION_FLASH:
                $this->session->getFlashBag()->add('notifications', $newNotification);
                break;
            case self::DURATION_PERSIST:
                $this->em->persist($newNotification);
                $this->em->flush();
                break;
        }
    }

    private function validate()
    {
        if ($this->content === null) {
            throw new RuntimeException('Notification content is not set');
        }

        if ($this->type === null) {
            throw new RuntimeException('Notification type is not set');
        }

        if ($this->icon === null) {
            throw new RuntimeException('Notification icon is not set');
        }

        if ($this->duration === null) {
            throw new RuntimeException('Notification duration is not set');
        }

        if ($this->duration !== self::DURATION_PERSIST && (
            $this->receiver !== null && $this->receiver !== $this->user
        )) {
            throw new RuntimeException('Notifications for other users must be persisted');
        }
    }
}
