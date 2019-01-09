<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const SQL_FILE_PATH = 'data/BDD.sql';

    public function load(ObjectManager $manager)
    {
        // Read file contents
        $sql = file_get_contents(self::SQL_FILE_PATH);

        // Execute native SQL
        $manager->getConnection()->exec($sql);

        $manager->flush();
    }
}
