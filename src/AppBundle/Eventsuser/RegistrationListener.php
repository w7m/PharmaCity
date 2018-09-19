<?php

namespace AppBundle\Eventsuser;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Form;

class RegistrationListener implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(FOSUserEvents::REGISTRATION_SUCCESS=>'onRegistrationSuccess');
    }
    public function onRegistrationSuccess(FormEvent $event)
    {
        $role = 'ROLE_PATIENT';
        $user = $event->getForm()->getData();
        $user->addRole($role);
    }

}