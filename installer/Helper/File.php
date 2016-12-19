<?php

namespace Installer\Helper;

use Symfony\Component\Filesystem\Filesystem;

class File
{
    const META = 'meta.json';
    const README = 'README.md';
    const README_DIST = 'installer/Resources/README.md';
    const PARAMETERS = 'app/config/parameters.yml';
    const PARAMETERS_DIST = 'app/config/parameters.yml.dist';
    const PARAMETERS_LOCAL = 'app/config/parameters_local.yml';
    const GITIGNORE = '.gitignore';
    const DEV = 'DEV';
    const COMPOSER_JSON = 'composer.json';
    const SONATA_ADMIN_CONFIG = 'app/config/sonata/admin.yml';
    const FRONT_END_LAYOUT = 'src/App/FrontEndBundle/Resources/views/layout.html.twig';

    const INSTALLER = 'installer';

    /**
     * @param $file
     * @param array $ignore
     */
    public static function removeLinesFromFile($file, array $ignore)
    {
        $lines = [];
        foreach(file($file) as $i => $line) {
            if(!in_array($i, $ignore)) {
                $lines[] = $line;
            }
        }
        file_put_contents($file, implode('', $lines));
    }

    /**
     * @param $file
     * @param array $content
     */
    public static function removeLinesFromFileByContent($file, array $content)
    {
        $lines = [];
        foreach(file($file) as $i => $line) {
            if(!in_array($line, $content)) {
                $lines[] = $line;
            }
        }
        file_put_contents($file, implode('', $lines));
    }

    /**
     * @param string $file
     * @return string
     */
    public static function getFullPath($file)
    {
        return self::getPathRoot() . $file;
    }

    /**
     * @return string
     */
    public static function getPathRoot()
    {
        return realpath(__DIR__ . '/' . str_repeat('../', 2)) . '/';
    }

    /**
     * @param $search
     * @param $replace
     * @param $filename
     */
    public static function replaceInFile($search, $replace, $filename)
    {
        file_put_contents(File::getFullPath($filename), str_replace(
            $search,
            $replace,
            file_get_contents(File::getFullPath($filename))
        ));
    }

    /**
     * @param $name
     * @param $value
     * @param $file
     */
    public static function replaceParameterInFile($name, $value, $file)
    {
        $parameters = [];
        foreach (file(self::getFullPath($file)) as $line) {
            if (strpos($line, $name . ':') !== false) {
                $lineData    = explode(':', $line);
                $lineData[1] = sprintf(' %s', $value) . PHP_EOL;
                $line        = implode(':', $lineData);
            }
            $parameters[] = $line;
        }

        file_put_contents(self::getFullPath($file), implode('', $parameters));
    }

    /**
     * @param $key
     * @param $value
     */
    public static function writeMetaData($key, $value)
    {
        $data = self::readMetaData();
        $data[$key] = $value;

        file_put_contents(self::getFullPath(self::META), json_encode($data));
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public static function readMetaData($key = null)
    {
        if(!file_exists(self::META)) {
            return [];
        }

        $data = json_decode(file_get_contents(self::META), true);

        if(empty($key)) {
            return $data;
        }

        if(empty($data[$key])) {
            return null;
        }

        return $data[$key];
    }

    /**
     * @param $key
     */
    public static function removeMetaData($key)
    {
        $data = self::readMetaData();
        unset($data[$key]);

        file_put_contents(self::getFullPath(self::META), json_encode($data));
    }

    /**
     * @param $source
     * @param $destination
     */
    public static function copy($source, $destination)
    {
        self::getFilesystem()->copy($source, $destination, true);
    }

    /**
     * @param $files
     */
    public static function remove($files)
    {
        if(Environment::isSkeletonDevMode()) {
            return;
        }
        self::getFilesystem()->remove($files);
    }

    /**
     * @return Filesystem
     */
    public static function getFilesystem()
    {
        return new Filesystem();
    }
}
