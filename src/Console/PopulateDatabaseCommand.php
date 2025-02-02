<?php


namespace App\Console;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\Schema;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Populate database...');

        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->app->getContainer()->get('db');

        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("TRUNCATE `employees`");
        $db->getConnection()->statement("TRUNCATE `offices`");
        $db->getConnection()->statement("TRUNCATE `companies`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");


        // $db->getConnection()->statement("INSERT INTO `companies` VALUES
        //     (1,'Stack Exchange','0601010101','stack@exchange.com','https://stackexchange.com/','https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/Verisure_information_technology_department_at_Ch%C3%A2tenay-Malabry_-_2019-01-10.jpg/1920px-Verisure_information_technology_department_at_Ch%C3%A2tenay-Malabry_-_2019-01-10.jpg', now(), now(), null),
        //     (2,'Google','0602020202','contact@google.com','https://www.google.com','https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Google_office_%284135991953%29.jpg/800px-Google_office_%284135991953%29.jpg?20190722090506',now(), now(), null)
        // ");

        // $db->getConnection()->statement("INSERT INTO `offices` VALUES
        //     (1,'Bureau de Nancy','1 rue Stanistlas','Nancy','54000','France','nancy@stackexchange.com',NULL,1, now(), now()),
        //     (2,'Burea de Vandoeuvre','46 avenue Jeanne d\'Arc','Vandoeuvre','54500','France',NULL,NULL,1, now(), now()),
        //     (3,'Siege sociale','2 rue de la primatiale','Paris','75000','France',NULL,NULL,2, now(), now()),
        //     (4,'Bureau Berlinois','192 avenue central','Berlin','12277','Allemagne',NULL,NULL,2, now(), now())
        // ");

        $this->randomData($db);

        // $db->getConnection()->statement("INSERT INTO `employees` VALUES
        //     (1,'Camille','La Chenille',1,'camille.la@chenille.com',NULL,'Ingénieur', now(), now()),
        //     (2,'Albert','Mudhat',2,'albert.mudhat@aqume.net',NULL,'Superviseur', now(), now()),
        //     (3,'Sylvie','Tesse',3,'sylive.tesse@factice.local',NULL,'PDG', now(), now()),
        //     (4,'John','Doe',4,'john.doe@generique.org',NULL,'Testeur', now(), now()),
        //     (5,'Jean','Bon',1,'jean@test.com',NULL,'Developpeur', now(), now()),
        //     (6,'Anais','Dufour',2,'anais@aqume.net',NULL,'DBA', now(), now()),
        //     (7,'Sylvain','Poirson',3,'sylvain@factice.local',NULL,'Administrateur réseau', now(), now()),
        //     (8,'Telma','Thiriet',4,'telma@generique.org',NULL,'Juriste', now(), now())
        // ");

        // $db->getConnection()->statement("update companies set head_office_id = 1 where id = 1;");
        // $db->getConnection()->statement("update companies set head_office_id = 3 where id = 2;");

        $output->writeln('Database created successfully!');
        return 0;
    }

    /**
     * 
     */
    function randomCompanies(int $qty): string
    {
        $faker = \Faker\Factory::create('fr_FR');

        $rq = 'INSERT INTO `companies` VALUES';

        for ($i = 1; $i <= $qty; $i++) {
            $rq .= "($i,
                    '{$faker->company()}',
                    '{$faker->phoneNumber()}',
                    '{$faker->email()}',
                    '{$faker->url()}',
                    '{$faker->imageUrl()}',"
                . "now(),now(),NULL)";

            if ($i < $qty)
                $rq .= ',';
        }

        return $rq;
    }

    /**
     * 
     */
    function randomOffices(int $qty): string
    {
        $faker = \Faker\Factory::create();
        $rq = 'INSERT INTO `offices` VALUES';

        for ($i = 1; $i <= $qty; $i++) {

            $rq .= "({$i},
            '{$faker->companySuffix()}',
            '{$faker->address()}',
            '{$faker->city()}',
            '{$faker->postcode()}',
            '{$faker->country()}',
            '{$faker->email()}',
            NULL,
            {$faker->numberBetween(1, 3)},
            now(),
            now())";

            if ($i < $qty)
                $rq .= ',';
        }

        print_r($rq);

        return $rq;
    }


    /**
     * 
     */
    function randomEmployees(int $qty): string
    {
        $faker = \Faker\Factory::create();
        $rq = 'INSERT INTO `employees` VALUES';

        for ($i = 1; $i <= $qty; $i++) {
            $rq .= `({$i},{$faker->companySuffix},{$faker->address()},{$faker->city()},{$faker->postcode()},{$faker->country()},{$faker->email()},` . null . `{$faker->numberBetween(1, 4)},{$faker->date()},{$faker->date()})`;

            if ($i < $qty)
                $rq .= ',';
        }
        return $rq;
    }

    /**
     * 
     */
    function randomData($db)
    {
        $db->getConnection()->statement($this->randomCompanies(4));
        $db->getConnection()->statement($this->randomOffices(3));
    }
}
