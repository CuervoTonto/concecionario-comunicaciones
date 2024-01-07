<?php 

namespace Src\Container;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

use Contracts\Container\ContainerInterface;
use Src\Support\Collection\Collection;
use Src\Support\Collection\IndexateCollection;

class Container
{
    /**
     * list of aliases
     */
    private array $aliasesAbstract;

    /**
     * list of shared instancess
     */
    private array $instances;

    /**
     * make a instance of class
     */
    public function __construct()
    {
        $this->aliasesAbstract = [];
        $this->instances = [];
    }

    /**
     * {@inheritDoc}
     */
    public function instance(string $abstract, object $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(string $abstract, array $parameters = []): object
    {
        $abstract = $this->getAbstractByAlias($abstract);

        if ($this->hasInstanceTo($abstract)) {
            return $this->instances[$abstract];
        }

        return $this->make($abstract, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function make(string $type, array $parameters = []): object
    {
        $reflector = new ReflectionClass($type);
        $dependencies = $reflector->getConstructor()?->getParameters();

        if (! $dependencies) {
            return $reflector->newInstance(...$parameters);
        }

        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstance(...$instances, ...$parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function call(Closure|array|string $callable, array $parameters = []): mixed
    {
        if (is_string($callable)) {
            $callable = [$callable, '__invoke'];
        }

        if (is_array($callable) && is_string($callable[0])) {
            $callable[0] = $this->resolve($callable[0]);
        }

        $reflector = is_array($callable)
            ? new ReflectionMethod(...$callable)
            : new ReflectionFunction($callable);

        $dependencies = $reflector->getParameters();

        if (! $dependencies) {
            return call_user_func($callable, ...$parameters);
        }

        $instances = $this->resolveDependencies($dependencies);

        return call_user_func($callable, ...$instances, ...$parameters);
    }

    /**
     * resolve the given dependencies using shared instances
     * 
     * @param array<ReflectionParameter> $dependencies
     */
    private function resolveDependencies(array $dependencies): array
    {
        return (new IndexateCollection($dependencies))
            ->map(fn($d) => $d->getType())
            ->filter(fn($d) => $d instanceof ReflectionNamedType && ! $d->isBuiltin())
            ->map(fn($d) => $this->resolve($d))
            ->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function alias(string $abstract, string $alias): void
    {
        if ($abstract === $alias) {
            throw new RuntimeException('alias and abstract can\'t be the same');
        }

        $this->aliasesAbstract[$alias] = $abstract;
    }

    /**
     * {@inheritDoc}
     */
    public function getAbstractByAlias(string $alias): string
    {
        if (! isset($this->aliasesAbstract[$alias])) {
            return $alias;
        }

        $alias = $this->aliasesAbstract[$alias];

        return $this->getAbstractByAlias($alias);
    }

    /**
     * check if container has a instance on his shareds
     */
    public function hasInstanceTo(string $type): bool
    {
        return isset($this->instances[$type]);
    }
}