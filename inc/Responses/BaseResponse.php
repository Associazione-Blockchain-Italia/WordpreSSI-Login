<?php

namespace Inc\Responses;

/**
 *
 */
class BaseResponse
{

    /**
     * The provider id
     *
     * @var string
     */
    protected string $provider;

    /**
     * The nextAction that must be fired in the script.js
     *
     * @var string
     */
    protected string $nextAction;

    /**
     * A redirectUrl if the nextAction === 'externalPage'
     *
     * @var string
     */
    protected string $redirectUrl;

    /**
     * A list of additional arguments that are sent-back in the next request
     * under the request.data.args property of the request.
     *
     * @var
     */
    protected $args;

    /**
     * @param $provider
     */
    function __construct($provider) {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getNextAction(): string
    {
        return $this->nextAction;
    }

    /**
     * @param string $nextAction
     *
     * @return BaseResponse
     */
    public function setNextAction(string $nextAction): BaseResponse
    {
        $this->nextAction = $nextAction;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return BaseResponse
     */
    public function setProvider(string $provider): BaseResponse
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return BaseResponse
     */
    public function setRedirectUrl(string $redirectUrl): BaseResponse
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param mixed $args
     *
     * @return BaseResponse
     */
    public function setArgs($args): BaseResponse
    {
        $this->args = $args;

        return $this;
    }

    /**
     * Transform the Object in a dictionary ready to be printed.
     *
     * @return array
     */
    public function asArray(): array
    {
        return get_object_vars($this);
    }

}
