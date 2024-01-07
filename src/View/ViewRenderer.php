<?php

namespace Src\View;

use Closure;
use RuntimeException;
use Throwable;

class ViewRenderer
{
    /**
     * view instance
     */
    private View $view;

    /**
     * sections content
     */
    private array $sections = [];

    /**
     * content of stacks
     */
    private array $stacks = [];

    /**
     * accumulated of sections on row
     */
    private array $sectionAcc = [];

    /**
     * accumulated stacks on row
     */
    private array $stackAcc = [];

    /**
     * view layout
     */
    private ?string $layout = null;

    /**
     * build a instanc of ViewRenderer
     * 
     * @param View $view instance of View for render
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * start rendering for view
     * 
     * @return string the rendered view
     * 
     * @throws Throwable
     */
    public function run(): string
    {
        try {
            return $this->renderContent();
        } catch (Throwable $thr) {
            while (ob_get_level() > 1) ob_end_clean();
            throw $thr;
        }
    }

    /**
     * render the content of the view
     */
    private function renderContent(): string
    {
        $render = Closure::bind(
            /**
             * get the contents of the view file
             * 
             * @param string $_file view file
             * @param array $_data the data to use
             * 
             * @return string
             */
            function (string $_file, array $_data): string {
                ob_start();
                extract($_data);
                require $_file;

                return ob_get_clean();
            },
            $this,
            null
        );

        $res = $render($this->view->getFile(), $this->view->getData());

        if (! is_null($this->layout)) {
            $res = $render($this->layout, $this->view->getData());
        }

        return $res;
    }

    /**
     * set layout file
     */
    public function layout(string $file): void
    {
        if (! file_exists($file)) {
            throw new RuntimeException("the file [{$file}] doesn't exists (layout)");
        }

        $this->layout = $file;
    }

    /**
     * start buffer for section
     */
    public function section(string $name, ?string $content = null): void
    {
        if (! is_null($content)) {
            $this->sections[$name] = $content;
        } else {
            $this->sectionAcc[] = $name;
            ob_start();
        }
    }

    /**
     * end the current started section
     */
    public function endSection(): void
    {
        if (is_null($name = array_pop($this->sectionAcc))) {
            throw new RuntimeException('attempt of end a not started section');
        }

        $this->sections[$name] = ob_get_clean();
    }

    /**
     * start buffer for stack
     */
    public function stack(string $name, ?string $content = null): void
    {
        if (! is_null($content)) {
            $this->stacks[$name][] = $content;
        } else {
            $this->stackAcc[] = $name;
            ob_start();
        }
    }

    /**
     * end the current started section
     */
    public function endStack(): void
    {
        if (is_null($name = array_pop($this->stackAcc))) {
            throw new RuntimeException('attempt of end a not started section');
        }

        $this->stacks[$name][] = ob_get_clean();
    }

    /**
     * obtains the content from a stack
     */
    public function useStack(string $name): string
    {
        return implode(PHP_EOL, $this->stacks[$name] ?? []);
    }

    /**
     * use section
     */
    public function useSection(string $name): ?string
    {
        return $this->sections[$name] ?? null;
    }
}