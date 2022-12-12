<?php

namespace Inc\Responses;

use Inc\Services\CredentialStatusService;

/**
 * This class represent a response to the Credentials (get and create) Controller
 */
class CredentialResponse extends BaseResponse
{

    /**
     * The url that display the shortcode
     *
     * @var
     */
    protected $offerUrl;

    /**
     * The credentialId
     *
     * @var
     */
    protected $credentialId;

    /**
     * The state of the response
     * @see CredentialStatusService
     *
     * @var
     */
    protected $state;

    /**
     * @return mixed
     */
    public function getOfferUrl()
    {
        return $this->offerUrl;
    }

    /**
     * @param mixed $offeredURL
     *
     * @return CredentialResponse
     */
    public function setOfferURL($offeredURL): CredentialResponse
    {
        $this->offerUrl = $offeredURL;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredentialId()
    {
        return $this->credentialId;
    }

    /**
     * @param mixed $credentialId
     *
     * @return CredentialResponse
     */
    public function setCredentialId($credentialId): CredentialResponse
    {
        $this->credentialId = $credentialId;

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
     * @return CredentialResponse
     */
    public function setState(?string $state): CredentialResponse
    {
        $this->state = $state;

        return $this;
    }

}
