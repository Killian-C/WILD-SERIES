<?php

namespace App\DataFixtures;


use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const DESCRIPTION = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi luctus sodales nulla quis porta. Nam tempus augue ipsum, eget lacinia turpis rhoncus nec. Mauris eu ligula vitae urna luctus vestibulum. Pellentesque gravida nulla ac turpis iaculis, in efficitur ligula feugiat. Curabitur vitae convallis nunc. Fusce a accumsan ex, non lobortis purus. In eu mi nisl. Sed mauris sapien, rhoncus non urna eleifend, varius rhoncus mauris. Praesent in ipsum velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.';

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $season = new Season();
            $season->setProgram($this->getReference('program_0'));
            $season->setNumber($i);
            $season->setYear(2015 + $i);
            $season->setDescription(self::DESCRIPTION);
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}


