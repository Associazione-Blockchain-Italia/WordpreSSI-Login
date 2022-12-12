<?php

namespace Inc\Responses;

/**
 * This class represent a response for the ConnectionControllers.
 *
 */
class ConnectionResponse extends BaseResponse
{

    /**
     * The list of active connections
     *
     * @var array
     */
    protected array $allConnections = [];

    /**
     * The connectionId of the current connection
     *
     * @var string
     */
    protected string $connectionId;

    /**
     * A connection invitation url to print the shortcode
     *
     * @var string
     */
    protected string $connectionInvitationUrl;

    /**
     * @return array
     */
    public function getAllConnections(): array
    {
        return $this->allConnections;
    }

    /**
     * @param array $allConnections
     *
     * @return ConnectionResponse
     */
    public function setAllConnections(array $allConnections): ConnectionResponse
    {
        foreach ($allConnections as $connection) {
            $this->allConnections[] = $connection['connectionId'];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getConnectionId(): string
    {
        return $this->connectionId;
    }

    /**
     * @param string $connectionId
     *
     * @return ConnectionResponse
     */
    public function setConnectionId(string $connectionId): ConnectionResponse
    {
        $this->connectionId = $connectionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getConnectionInvitationUrl(): string
    {
        return $this->connectionInvitationUrl;
    }

    /**
     * @param string $connectionInvitationUrl
     *
     * @return ConnectionResponse
     */
    public function setConnectionInvitationUrl(string $connectionInvitationUrl): ConnectionResponse
    {
        $this->connectionInvitationUrl = $connectionInvitationUrl;

        return $this;
    }

}
