<?php

namespace Post\View;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class PageView
{
    private FilesystemLoader $loader;
    private Environment $view;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(dirname(__DIR__) . '/View/templates');
        $this->view = new Environment($this->loader);
    }

    public function indexPage($data)
    {
        try {
            echo $this->view->render('index.twig', ['posts' => $data ?? array(), 'logged' => isset($_COOKIE['login'])]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e;
        }
    }

    public function profilePage($data)
    {
        try {
            echo $this->view->render('profile.twig', ['user' => $data, 'logged' => isset($_COOKIE['login'])]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e;
        }
    }

    public function errorPage()
    {
        try {
            echo $this->view->render('error.twig', ['logged' => isset($_COOKIE['login'])]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e;
        }
    }

    public function registrationPage()
    {
        try {
            echo $this->view->render('registration.twig' , ['logged' => isset($_COOKIE['login'])]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e;
        }
    }

    public function loginPage()
    {
        try {
            echo $this->view->render('login.twig', ['logged' => isset($_COOKIE['login'])]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e;
        }
    }
}