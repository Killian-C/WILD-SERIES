<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const SYNOPSIS = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus sodales nulla quis porta. Nam tempus augue ipsum, eget lacinia turpis rhoncus nec. Mauris eu ligula vitae urna luctus vestibulum. Pellentesque gravida nulla ac turpis iaculis, in efficitur ligula feugiat. Curabitur vitae convallis nunc. Fusce a accumsan ex, non lobortis purus. In eu mi nisl. Sed mauris sapien, rhoncus non urna eleifend, varius rhoncus mauris. Praesent in ipsum velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.';

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $episode = new Episode();
            $episode->setSeason($this->getReference('season_1'));
            $episode->setTitle('Episode n°' . $i);
            $episode->setSlug($this->slugify->generate($episode->getTitle()));
            $episode->setNumber($i);
            $episode->setSynopsis(self::SYNOPSIS);
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
