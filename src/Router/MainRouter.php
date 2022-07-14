<?php

namespace Post\Router;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use Post\Controller\MainController;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

class MainRouter
{
    public function __construct()
    {
        Dotenv::createImmutable(dirname(__DIR__, 2))->load();
        $uri = rtrim($_SERVER['REQUEST_URI'], '/');
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

        $mainController = new MainController($entityManager);
        $rootPath = '/mvc';

        if (!isset($_COOKIE['login'])) {
            switch ($uri) {
                case $rootPath:
                {
                    $mainController->showMainPage();
                    break;
                }
                case $rootPath . '/registrationPage': {
                    $mainController->showRegistrationPage();
                    break;
                }
                case $rootPath . '/registration': {
                    $mainController->registration($_POST["login"], $_POST["password"]);
                    break;
                }
                case $rootPath . '/loginPage': {
                    $mainController->showLoginPage();
                    break;
                }
                case $rootPath . '/login': {
                    $mainController->login($_POST["login"], $_POST["password"]);
                    break;
                }
                default:
                {
                    $mainController->showErrorPage();
                    break;
                }
            }
        } else {
            switch ($uri) {
                case $rootPath:
                {
                    $mainController->showMainPage();
                    break;
                }
                case $rootPath . '/profilePage':
                {
                    $mainController->showProfilePage();
                    break;
                }
                case $rootPath . '/logout': {
                    $mainController->logout();
                    break;
                }
                case $rootPath . '/addPost': {
                    $mainController->addPost($_POST["title"], $_POST["content"], $_COOKIE["login"]);
                    break;
                }
                default:
                {
                    $mainController->showErrorPage();
                    break;
                }
            }
        }
    }
}