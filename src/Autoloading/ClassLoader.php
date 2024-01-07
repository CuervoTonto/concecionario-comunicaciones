<?php

final class ClassLoader
{
    /**
     * autoloader base direction
     */
    private readonly string $dir;

    /**
     * build a instance of ClassLoader
     * 
     * @param string $dir the directory to autoloader
     */
    public function __construct(string $dir)
    {
        $this->setDirectory($dir);
    }

    /**
     * set the directory to the autoloader
     * 
     * @param string $dir the directory to autoloader
     */
    private function setDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            throw new RuntimeException('invalid directory to ClassLoader');
        }

        $this->dir = $dir;
    }

    /**
     * checks if the given file exists and is valid for autoload
     * 
     * @param string|SplFileInfo $file path of the file to validate
     */
    private function validateFile(string|SplFileInfo $file): void
    {
        if (! file_exists($file)) {
            throw new RuntimeException("the file {$file} doesn't exists");
        }
    }

    /**
     * get the file path from class
     * 
     * @param string $class the class to get the file path
     * 
     * @param string get file path of class
     */
    private function fileFromClass(string $class): string
    {
        return sprintf('%s/%s.php', $this->dir, $class);
    }

    /**
     * load the file of the given class
     * 
     * @param string $class the class to load
     * 
     * @return bool state of the file loaded
     */
    private function load(string $class): bool
    {
        $this->validateFile(
            $file = new SplFileInfo($this->fileFromClass($class))
        );

        return require_once $file->getRealPath();
    }

    /**
     * register on spl_autoload
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * register from spl_autoload
     */
    public function unregister(): void
    {
        spl_autoload_unregister([$this, 'load']);
    }
}