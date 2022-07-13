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
        $uri = $_SERVER['REQUEST_URI'];
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

        if (!isset($_COOKIE['login'])) {
            switch ($uri) {
                case '/':
                {
                    $mainController->showMainPage();
                    break;
                }
                case '/registrationPage': {
                    $mainController->showRegistrationPage();
                    break;
                }
                case '/registration': {
                    $mainController->registration($_POST["login"], $_POST["password"]);
                    break;
                }
                case '/loginPage': {
                    $mainController->showLoginPage();
                    break;
                }
                case '/login': {
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
                case '/':
                {
                    $mainController->showMainPage();
                    break;
                }
                case '/profilePage':
                {
                    $mainController->showProfilePage();
                    break;
                }
                case '/logout': {
                    $mainController->logout();
                    break;
                }
                case '/addPost': {
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

//        switch ($uri) {
//            case '/':
//            {
//                $mainController->showMainPage();
//                break;
//            }
//            case '/profilePage':
//            {
//                $mainController->showProfilePage();
//                break;
//            }
//            case '/registrationPage': {
//                $mainController->showRegistrationPage();
//                break;
//            }
//            case '/registration': {
//                $mainController->registration($_POST["login"], $_POST["password"]);
//                break;
//            }
//            case '/loginPage': {
//                $mainController->showLoginPage();
//                break;
//            }
//            case '/login': {
//                $mainController->login($_POST["login"], $_POST["password"]);
//                break;
//            }
//            case '/logout': {
//                $mainController->logout();
//                break;
//            }
//            default:
//            {
//                $mainController->showErrorPage();
//                break;
//            }
//        }
    }
}