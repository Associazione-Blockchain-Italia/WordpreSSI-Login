<?php

namespace Inc\Responses;

/**
 * The class represent a response to the requests made to the Verify (get and create) Controller
 */
class VerificationResponse extends BaseResponse
{

    /**
     * @var
     */
    protected $verificationId;

    /**
     * @var
     */
    protected $verificationUrl;

    /**
     * @var
     */
    protected $state;

    /**
     * @var
     */
    protected $isValid;

    /**
     * @var
     */
    protected $identifier;

    /**
     * @return mixed
     */
    public function getVerificationId()
    {
        return $this->verificationId;
    }

    /**
     * @param mixed $verificationId
     *
     * @return VerificationResponse
     */
    public function setVerificationId($verificationId)
    {
        $this->verificationId = $verificationId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVerificationUrl()
    {
        return $this->verificationUrl;
    }

    /**
     * @param mixed $verificationUrl
     *
     * @return VerificationResponse
     */
    public function setVerificationUrl($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return VerificationResponse
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param mixed $isValid
     *
     * @return VerificationResponse
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     *
     * @return VerificationResponse
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

}
