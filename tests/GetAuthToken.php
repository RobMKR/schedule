<?php

namespace Tests;

use App\Services\Auth\LoginService;

trait GetAuthToken
{
    use RegisterSuccessUser;

    /**
     * @var string|null
     */
    protected static $token;

    /**
     * @var integer|null
     */
    protected static $id;

    /**
     * Get token and store to static variable $token
     *
     * @throws \App\Exceptions\V1\WrongCredentialsException
     */
    protected function init()
    {
        /** @var LoginService $loginService */
        $loginService = app(LoginService::class);

        $this->registerSuccessUser();

        $payload = $loginService->login([
            'email' => $this->adminEmail,
            'password' => $this->adminPassword
        ]);

        $this::$token = $payload['token'] ?? null;
        $this::$id = $payload['id'];
    }

    /**
     * @return string|null
     * @throws \App\Exceptions\V1\WrongCredentialsException
     */
    protected function getToken()
    {
        if (!$this::$token) {
            $this->init();
        }

        return $this::$token;
    }

    protected function getId() {
        if (!$this::$id) {
            $this->init();
        }

        return $this::$id;
    }
}
