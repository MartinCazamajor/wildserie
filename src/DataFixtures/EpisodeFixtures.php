<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        for ($program = 0; $program<6; $program++) {
            for ($season = 1; $season < 4; $season ++) {
                for ($rand = 1; $rand < 10; $rand++) {
                    $faker = Faker\Factory::create('fr_FR');
                    $episode = new Episode();
                    $episode->setNumber($rand);
                    $episode->setTitle($faker->word);
                    $episode->setSynopsis($faker->sentence);
                    $episode->setSlug($slugify->generate($episode->getTitle()));
                    $manager->persist($episode);
                    $episode->setSeason($this->getReference('season_' .$program .'_'.$season ));
                    $this->addReference('episode_' . $program . '_' . $season . '_' . $rand , $episode);
                }
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
        return [SeasonFixtures::class];
    }
}
