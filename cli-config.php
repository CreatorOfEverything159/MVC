<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

Dotenv::createImmutable(dirname(__DIR__))->load();

$config = Setup::createAnnotationMetadataConfiguration(array("src"), false, useSimpleAnnotationReader: false);
$connection = [
    "dbname" => $_ENV["DB_NAME"],
    "user" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASS"],
    "host" => $_ENV["DB_HOST"],
    "driver" => "pdo_pgsql"
];

try {
    $entityManager = EntityManager::create($connection, $config);
} catch (ORMException $e) {
    echo $e;
}

return ConsoleRunner::createHelperSet($entityManager);
