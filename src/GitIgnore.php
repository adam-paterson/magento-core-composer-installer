<?php

namespace AydinHassan\MagentoCoreComposerInstaller;

/**
 * Class GitIgnore
 * @package AydinHassan\MagentoCoreComposerInstaller
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class GitIgnore
{

    /**
     * @var array
     */
    protected $lines = array();

    /**
     * @var array
     */
    protected $directoriesToIgnoreEntirely = array();

    /**
     * @var string|null
     */
    protected $gitIgnoreLocation;

    /**
     * @param string $fileLocation
     * @param array $directoriesToIgnoreEntirely
     * @param bool $gitIgnoreAppend
     */
    public function __construct($fileLocation, array $directoriesToIgnoreEntirely, $gitIgnoreAppend = false)
    {
        $this->gitIgnoreLocation = $fileLocation;

        if (!file_exists($fileLocation)) {
            touch($fileLocation);
        } else {

            if ($gitIgnoreAppend) {
                $this->lines = array_flip(file($fileLocation, FILE_IGNORE_NEW_LINES));
            }
        }

        $this->directoriesToIgnoreEntirely = $directoriesToIgnoreEntirely;

        foreach ($this->directoriesToIgnoreEntirely as $directory) {
            $this->lines[$directory] = $directory;
        }
    }

    /**
     * @param string $file
     */
    public function addEntry($file)
    {
        $addToGitIgnore = true;
        foreach ($this->directoriesToIgnoreEntirely as $directory) {
            if (substr($file, 0, strlen($directory)) === $directory) {
                $addToGitIgnore = false;
            }
        }

        if ($addToGitIgnore && !isset($this->lines[$file])) {
            $this->lines[$file] = $file;
        }
    }

    /**
     * @return array
     */
    public function getEntries()
    {
        return array_values(array_flip($this->lines));
    }

    /**
     * Wipe out the gitginore
     */
    public function wipeOut()
    {
        $this->lines = array();
    }

    /**
     * Write the file
     */
    public function __destruct()
    {
        file_put_contents($this->gitIgnoreLocation, implode("\n", array_flip($this->lines)));
    }
}
