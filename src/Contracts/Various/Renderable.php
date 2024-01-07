<?php 

namespace Src\Contracts\Various;

interface Renderable
{
    /**
     * transform the class data on a visual representation
     */
    public function render(): string;
}