<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Create a Test User
        $user = new User();
        $user->setEmail('pacificooyanib@gmail.com');
        $user->setRoles(['ROLE_ADMIN']); 
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $manager->persist($user);

        // 2. Create a Venue
        $venue = new Venue();
        $venue->setName('NORSU CAS BUILDING');
        $venue->setAddress('Dumaguete City');
        $venue->setCapacity(500);
        $manager->persist($venue);

        // 3. Create an Event
        $event = new Event();
        $event->setTitle('Practice');
        $event->setDescription('Acitivty bastta mao nana');
        
        // Handling DateTimeImmutable as required by your Entity
        $start = new \DateTimeImmutable('2026-05-15 18:00:00');
        $event->setStartDate($start);
        $event->setEndDate($start->modify('+4 hours'));
        
        // Price is a string/decimal in your entity
        $event->setPrice('10.50'); 
        $event->setMaxAttendees(200);
        $event->setStatus('published');
        
        // Link the event to the venue
        $event->setVenue($venue);

        $manager->persist($event);

        // Flush everything to the database
        $manager->flush();
    }
}