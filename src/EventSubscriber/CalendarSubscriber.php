<?php

namespace App\EventSubscriber;

use App\Entity\ControleDouanier;
use App\Repository\ControleDouanierRepository;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CalendarBundle\CalendarEvents;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ControleDouanierRepository $repository,
        private UrlGeneratorInterface $router
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $controles = $this->repository->createQueryBuilder('c')
            ->where('c.dateControle BETWEEN :start AND :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($controles as $controle) {
            if (!$controle->getDateControle()) {
                continue;
            }

            $eventDate = clone $controle->getDateControle();

            $event = new Event(
                sprintf('Contrôle #%d - %s', $controle->getIdControle(), $controle->getPaysDouane()),
                $eventDate,
                $eventDate->modify('+1 day')
            );

            $event->setOptions([
                'backgroundColor' => $this->getStatusColor($controle->getStatut()),
                'borderColor' => '#000000',
                'textColor' => '#ffffff',
                'allDay' => true,
                'url' => $this->router->generate('app_controle_douanier_show', [
                    'id_controle' => $controle->getIdControle()
                ]),
                'extendedProps' => [
                    'status' => $controle->getStatut(),
                    'country' => $controle->getPaysDouane(),
                    'comments' => $controle->getCommentaires() ?? 'Aucun commentaire'
                ]
            ]);

            $calendar->addEvent($event);
        }
    }

    private function getStatusColor(string $status): string
    {
        return match($status) {
            'En attente' => '#FFC107',
            'En cours' => '#17A2B8',
            'Validé' => '#28A745',
            'Rejeté' => '#DC3545',
            default => '#6C757D'
        };
    }
}