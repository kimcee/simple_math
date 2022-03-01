<?php

class User {

    const _COOKIE_NAME = 'jonas';
    const _COOKIE_VAL  = '12345';

    public function __construct()
    {
        // check for login request
        if (!empty($_POST['password'])) {
            $this->logIn($_POST['password']);
            $this->goHome();
        }

        // check for logout
        if (!empty($_GET['logout'])) {
            $this->logOut();
        }
        
        // check for cookie
        if (!$this->isLoggedIn() && isset($_COOKIE[self::_COOKIE_NAME])) {
            $this->setUserLoggedIn();
        }
    }

    public function isLoggedIn(): bool
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return true;
        }

        return false;
    }

    public function logIn($password): bool
    {
        if ($password === _USER_PASS) {
            $this->setUserLoggedIn();
            $this->setUserCookie();
            return true;
        }

        return false;
    }

    private function setUserLoggedIn(): void
    {
        $_SESSION['logged_in'] = true;
    }

    private function setUserCookie(): void
    {
        setcookie(
            self::_COOKIE_NAME, 
            self::_COOKIE_VAL, 
            time() + (86400 * 30), 
            "/"
        );
    }

    public function logOut(): void
    {
        // clear session
        $_SESSION['logged_in'] = false;
        unset($_SESSION['logged_in']);

        // set cookie in the past
        setcookie(
            self::_COOKIE_NAME, 
            self::_COOKIE_VAL, 
            time() - (86400 * 60), 
            "/"
        );

        $this->goHome();
    }

    private function goHome()
    {
        header("Location: index.php");
        exit;
    }
}
