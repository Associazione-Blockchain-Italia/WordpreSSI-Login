<?php

namespace Inc\Providers\Eassi\Helpers;

use Inc\Helpers\PluginPathHelper;

class EassiAPIHelper {

    private static function formatURL($pieces): string
    {
        return join("/", $pieces);
    }

    public static function getTokenAPIEndpoint($providerSettings): string
    {
        return self::formatURL(
            [
                $providerSettings['endpointURL'],
                "api",
                "utils",
                "jwt",
                $providerSettings['applicationId']
            ]
        );
    }

    public static function getIssueEndpoint($providerSettings, $token): string
    {
        return self::formatURL([$providerSettings['endpointURL'], "issue", $token]);
    }

    public static function getVerifyEndpoint($providerSettings, $token): string
    {
        return self::formatURL([$providerSettings['endpointURL'], "verify", $token]);
    }

    public static function getOrganizationsEndpoint($providerSettings): string
    {
        return self::formatURL([$providerSettings['endpointURL'], "api", "organizations"]);
    }

    public static function getIssueCallbackURL(): string
    {
        return PluginPathHelper::getFileURL('inc/Providers/Eassi/Controllers/Impl/IssueCallbackController.php?token=');
    }

    public static function getVerifyCallbackURL(): string
    {
        return PluginPathHelper::getFileURL('inc/Providers/Eassi/Controllers/Impl/VerifyCallbackController.php?token=');
    }

}
