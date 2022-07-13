<?php

namespace Post\Controller;

use Doctrine\ORM\EntityManager;
use Post\Model\Entities\Post;
use Post\Model\Entities\User;
use Post\View\PageView;

class MainController
{
    private EntityManager $manager;
    private PageView $pageView;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
        $this->pageView = new PageView();
    }

    public function showMainPage()
    {
        $posts = $this->manager
            ->getRepository(Post::class)
            ->getAll();
        $this->pageView->indexPage($posts);
    }

    public function showProfilePage()
    {
        $user = $this->manager
            ->getRepository(User::class)
            ->findByLogin($_COOKIE['login']);
        $this->pageView->profilePage($user);
    }

    public function showRegistrationPage()
    {
        $this->pageView->registrationPage();
    }

    public function showLoginPage()
    {
        $this->pageView->loginPage();
    }

    public function showErrorPage()
    {
        $this->pageView->errorPage();
    }

    function login($login, $password)
    {
        $user = new User();
        $user->setLogin($login);
        $user->setPassword($password);
        $currentUser = $this->manager
            ->getRepository(User::class)
            ->findByLoginAndPassword($user->getLogin(), $user->getPassword());
        if ($currentUser != NULL) {
            setcookie('login', $user->getLogin());
            setcookie('passwordHash', password_hash($user->getPassword(), PASSWORD_DEFAULT));
            header('Location: ' . '/profilePage');
        } else {
            header('Location: ' . '/loginPage');
        }
    }

    public function registration($login, $password)
    {
        $newUser = new User();
        $newUser->setLogin($login);
        $newUser->setPassword($password);
        $foundUser = $this->manager
            ->getRepository(User::class)
            ->findByLogin($newUser->getLogin());
        if ($foundUser) {
            header('Location: ' . '/registrationPage');
        } else {
            $this->manager->persist($newUser);
            $this->manager->flush();
            header('Location: ' . '/loginPage');
        }
    }

    public function logout()
    {
        setcookie('login', '');
        setcookie('password', '');
        header('Location: ' . '/loginPage');
    }

    public function addPost($title, $content, $login)
    {
        $user = $this->manager
            ->getRepository(User::class)
            ->findByLogin($login);
        if (isset($user)) {
            $newPost = new Post();
            $newPost->setUser($user);
            $newPost->setTitle($title);
            $newPost->setContent($content);
            $this->manager->persist($newPost);
            $this->manager->flush();
            header('Location: ' . '/');
        } else {
            header('Location: ' . '/profilePage');
        }
    }
}