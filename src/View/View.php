<?php

namespace Src\View;

use RuntimeException;
use Src\Contracts\Various\Renderable;

class View implements Renderable
{
    /**
     * file path of the view
     */
    private string $file;

    /**
     * data for use on view
     */
    private array $data;

    /**
     * indicate if the view was rendered
     */
    private bool $rendered = false;

    /**
     * the last render result
     */
    private ?string $lastRender = null;

    /**
     * build a instance of View
     * 
     * @param string $file view file
     * @param array<string, mixed> $data data for view
     */
    public function __construct(string $file, array $data = [])
    {
        $this->setFile($file);
        $this->setData($data);
    }

    /**
     * set the view file
     * 
     * @param string $file the new file (path)
     * 
     * @return $this
     */
    public function setFile(string $file): static
    {
        if (! file_exists($file)) {
            throw new RuntimeException("the file [{$file}] doesn't exists");
        }

        $this->file = $file;
        $this->rendered = false;

        return $this;
    }

    /**
     * set the data of the view
     * 
     * @param array<string, mixed> $data the new data
     * 
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = [];
        
        foreach ($data as $key => $value) {
            $this->addToData($key, $value);
        }

        return $this;
    }

    /**
     * add element to view data
     * 
     * @param string $key   identifier of the element
     * @param mixed  $value the element
     * 
     * @return void
     */
    public function addToData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
        $this->rendered = false;
    }

    /**
     * get a element form data
     * 
     * @param string $key identifier of element
     * 
     * @return mixed the element
     */
    public function getFromData(string $key): mixed
    {
        return $this->data[$key];
    }

    /**
     * remove a element form view data
     * 
     * @param string $key identifier of element
     * 
     * @return void
     */
    public function removeFromData(string $key): void
    {
        unset($this->data[$key]);
        $this->rendered = false;
    }

    /**
     * render the view
     */
    public function render(): string
    {
        if ($this->rendered) {
            return $this->lastRender;
        }

        $content = (new \Src\View\ViewRenderer($this))->run();
        $this->lastRender = $content;
        $this->rendered = true;

        return $content;
    }

    /**
     * get the view data
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * get the view file (path)
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * obtains the rendered status
     */
    public function rendered(): bool
    {
        return $this->rendered;
    }

    /**
     * obtains the last result of render
     */
    public function getLastRender(): ?string
    {
        return $this->lastRender;
    }
}