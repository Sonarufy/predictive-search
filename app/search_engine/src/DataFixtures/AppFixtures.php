<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const SQL_FILE_PATH = 'data/BDD.sql';

    const SQL_FILES = [
	    'data/pricing_zone.sql',
	    'data/postal_code.sql',
	    'data/insee_town.sql',
	    'data/insees_postals.sql',
	    'data/reference_price.sql',
    ];

    public function load(ObjectManager $manager)
    {
    	foreach (self::SQL_FILES as $fileName) {
		    // Read file contents
		    $sql = file_get_contents($fileName);

		    // Execute native SQL
		    $manager->getConnection()->exec($sql);

		    $manager->flush();
	    }

    }
}
