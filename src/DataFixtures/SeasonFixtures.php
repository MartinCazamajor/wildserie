<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<6; $i++){
            for ($rand = 1; $rand < 4; $rand++) {
                $faker = Faker\Factory::create('fr_FR');
                $season = new Season();
                $season->setNumber($rand);
                $season->setDescription($faker->sentence);
                $season->setYear($faker->year);
                $manager->persist($season);
                $season->setProgram($this->getReference('program_' . $i));
                $this->addReference('season_' .$i .'_'.$rand, $season);
            }
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
