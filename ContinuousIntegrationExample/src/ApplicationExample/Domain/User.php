<?php

namespace ApplicationExample\Domain;


class User {
    private $name;
    private $mail;
    private $pass;
    private $failed;

    /**
     * User constructor.
     *
     * @param $name
     * @param $mail
     * @param $pass
     * @throws \Exception
     */
    public function __construct( $name, $mail, $pass ) {
        if (strlen($pass) < 5)
            throw new \Exception('Длина пароля должна быть не менее 5 символов');

        $this->name = $name;
        $this->mail = $mail;
        $this->pass = $pass;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName( $name ): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getMail() {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail( $mail ): void {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass( $pass ): void {
        $this->pass = $pass;
    }

    public function failed( $time ) {
        $this->failed = $time;
    }
}