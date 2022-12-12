<?php

namespace Inc\Helpers;

/**
 * Utility class to manipulate and get file paths for the plugin.
 */
class PluginPathHelper
{

    /**
     * Return the directory of the plugin
     *
     * @return string
     */
    public static function getPluginPath(): string
    {
        return plugin_dir_path(dirname(__FILE__, 2));
    }

    /**
     * Get the path of a file given the list of directories
     *
     * eg. ["path", "to", "file" ] produces "path/to/file"
     *
     * @param array $pieces
     *
     * @return string
     */
    public static function pathFromPieces(array $pieces): string
    {
        return join(DIRECTORY_SEPARATOR, $pieces);
    }

    /**
     * Get the path where all the template and views folders are saved.
     *
     * @return string
     */
    public static function getPluginViewsFolderPath(): string
    {
        return PluginPathHelper::getPluginPath() . PluginPathHelper::pathFromPieces(['inc', 'Templates', 'Views']);
    }

    /**
     * Given a file name return its path from the plugin root.
     *
     * @param array $pieces
     *
     * @return string
     */
    public static function getPathForFile(array $pieces): string
    {
        return PluginPathHelper::getPluginPath() . PluginPathHelper::pathFromPieces($pieces);
    }

    /**
     * Check whether a file exists or not
     *
     * @param string $filePath
     *
     * @return bool
     */
    public static function fileExists(string $filePath): bool
    {
        return !empty($filePath) && is_readable($filePath);
    }

    /**
     * Get the url of a file
     * @param $filepath
     *
     * @return string
     */
    public static function getFileURL($filepath) : string
    {
        return plugins_url('SSIPlugin/'.$filepath);
    }

}
