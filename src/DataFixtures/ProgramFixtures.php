<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const TITLES = [
        'Star Wars VII',
        'Walking Dead',
        'Fear the Walking Dead',
        'Brooklyn 99',
        'Scrubs',
    ];
    const SUMMARIES = [
        'Il y longtemps dans une galaxie très très lointaine ...',
        'Des zombies et des gens ...',
        'Des zombies et des gens qui ont peur ...',
        'Des flics complètement barrés dans les quartiers malfamés de Brooklyn',
        'Un jeune inter à la recherche du parfait mentor tout en huralant "je suis un aiiiigle"',
    ];
    const POSTERS = [
        'https://www.photosmurales.fr/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/v/d/vd-046-star-wars-official-poster-ep7.jpg',
        'https://m.media-amazon.com/images/M/MV5BZmFlMTA0MmUtNWVmOC00ZmE1LWFmMDYtZTJhYjJhNGVjYTU5XkEyXkFqcGdeQXVyMTAzMDM4MjM0._V1_.jpg',
        'https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg',
        'https://fr.web.img6.acsta.net/pictures/20/01/10/10/23/0734068.jpg',
        'https://disney-planet.fr/wp-content/uploads/2021/02/affiche-scrubs-01.jpg',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::TITLES as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName);
            $program->setSummary(self::SUMMARIES[$key]);
            $program->setPoster(self::POSTERS[$key]);
            $program->setCategory($this->getReference('category_0'));
            for ($i = 0; $i < count(ActorFixtures::ACTORS); $i++) {
                $program->addActor($this->getReference('actor_' . $i));
            }
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
