<?php

namespace App\DataFixtures;

use App\Entity\Contract;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Faker;

use function random_int;
use function sprintf;

require_once 'vendor/autoload.php';

class AppFixtures extends Fixture
{
    public const NB_CONTRACTS = 3;
    public const NB_STATUS = 10;
    public const NB_TAGS = 10;
    public const NB_ROLES = 2;
    public const NB_USERS = 20;
    public const NB_PROJECTS = 4;
    public const MAX_PROJECT_DAYS = 730;
    public const NB_TASKS = 100;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        $this->loadContracts($manager);
        $this->loadStatuses($manager, $faker);
        $this->loadTags($manager, $faker);
        $this->loadUsers($manager, $faker);
        $this->loadProjects($manager, $faker);
        $this->loadTasks($manager, $faker);

        $manager->flush();
    }

    public function allowForcingIdValue(objectManager $manager, string $entity_class): void
    {
        $metadata = $manager->getClassMetadata($entity_class);
        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    }

    public function loadContracts(ObjectManager $manager): void
    {
        $this->allowForcingIdValue($manager, Contract::class);

        $contract = new Contract();
        $contract->setId(1)->setName('CDD');
        $manager->persist($contract);

        $contract = new Contract();
        $contract->setId(2)->setName('CDI');
        $manager->persist($contract);

        $contract = new Contract();
        $contract->setId(3)->setName('Freelance');
        $manager->persist($contract);
    }

    public function loadStatuses(ObjectManager $manager, Faker\Generator $faker): void
    {
        $this->allowForcingIdValue($manager, Status::class);

        for ($i = 1; $i <= self::NB_STATUS; $i++) {
            $status = new Status();
            $status
                ->setId($i)
                ->setName($faker->slug . '_status')
                ->setProjectId(random_int(1, self::NB_PROJECTS));
            $manager->persist($status);
        }
    }

    public function loadTags(ObjectManager $manager, Faker\Generator $faker): void
    {
        $this->allowForcingIdValue($manager, Tag::class);

        for ($i = 1; $i <= self::NB_TAGS; $i++) {
            $tag = new Tag();
            $tag
                ->setId($i)
                ->setName($faker->slug . '_tag');
            $manager->persist($tag);
        }
    }

    public function loadUsers(ObjectManager $manager, Faker\Generator $faker): void
    {
        $this->allowForcingIdValue($manager, User::class);

        for ($i = 1; $i <= self::NB_USERS; $i++) {
            $user = new User();
            $user
                ->setId($i)
                ->setContractId(random_int(1, self::NB_CONTRACTS))
                ->setFirstName($faker->firstName())
                ->setName($faker->lastName())
                ->setEmail($faker->email())
                ->setEnabled(true)
                ->setRole(random_int(1, self::NB_ROLES))
                ->setPassword($faker->password())
                ->setEntryDate($faker->dateTimeBetween('-10 year'));
            $manager->persist($user);
        }
    }

    public function loadProjects(ObjectManager $manager, Faker\Generator $faker): void
    {
        $this->allowForcingIdValue($manager, Project::class);

        for ($i = 1; $i <= self::NB_PROJECTS; $i++) {
            $start = $faker->dateTimeBetween('-5 year');
            $format = sprintf('+%d day', random_int(1, self::MAX_PROJECT_DAYS));
            $end = clone $start;
            $end->modify($format);

            $project = new Project();
            $project
                ->setId($i)
                ->setName('Projet ' . $faker->company())
                ->setArchived(false)
                ->setStartDate($start)
                ->setDeadline($end);

            $manager->persist($project);
        }
    }

    public function loadTasks(ObjectManager $manager, Faker\Generator $faker): void
    {
        $this->allowForcingIdValue($manager, Task::class);

        for ($i = 1; $i <= self::NB_TASKS; $i++) {
            $task = new Task();
            $task
                ->setId($i)
                ->setTitle('Tâche ' . $faker->city())
                ->setDescription($faker->text())
                ->setDeadline($faker->dateTimeBetween('-5 year'))
                ->setProjectId(random_int(1, self::NB_PROJECTS))
                ->setStatusId(random_int(1, self::NB_STATUS))
                ->setUserId(random_int(1, self::NB_USERS));
            $manager->persist($task);
        }
    }
}
