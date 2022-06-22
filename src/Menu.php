<?php

namespace TysonLaravel\Menus;

use Closure;
use Countable;
use Illuminate\Config\Repository;
use Illuminate\View\Factory;

class Menu implements Countable
{
    /**
     * The menus collections.
     *
     * @var array
     */
    protected $menus = [];

    /**
     * The constructor.
     *
     * @param Factory $views
     * @param Repository $config
     */
    public function __construct(Factory $views, Repository $config)
    {
        $this->views  = $views;
        $this->config = $config;
    }

    /**
     * Make new menu.
     *
     * @param string $name
     * @param Closure $callback
     *
     * @return \TysonLaravel\Menus\MenuBuilder
     */
    public function make($name, \Closure $callback)
    {
        return $this->create($name, $callback);
    }

    /**
     * Create new menu.
     *
     * @param string $name
     * @param Callable $resolver
     *
     * @return \TysonLaravel\Menus\Builder
     */
    public function create($name, Closure $resolver)
    {
        $builder = new Builder($name, $this->config);

        $builder->setViewFactory($this->views);

        $this->menus[$name] = $builder;

        return $resolver($builder);
    }

    /**
     * Get instance of the given menu if exists.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function instance($name)
    {
        return $this->has($name) ? $this->menus[$name] : null;
    }

    /**
     * Check if the menu exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->menus);
    }

    /**
     * Modify a specific menu.
     *
     * @param string $name
     * @param Closure $callback
     *
     * @return void
     */
    public function modify($name, Closure $callback)
    {
        $menu = collect($this->menus)->filter(function ($menu) use ($name) {
            return $menu->getName() == $name;
        })->first();

        $callback($menu);
    }

    /**
     * Render the menu tag by given name.
     *
     * @param $name
     * @param null $presenter
     *
     * @return string
     */
    public function render($name, $presenter = null, $bindings = [])
    {
        return $this->get($name, $presenter, $bindings);
    }

    /**
     * Render the menu tag by given name.
     *
     * @param string $name
     * @param string $presenter
     *
     * @return string|null
     */
    public function get($name, $presenter = null, $bindings = [])
    {
        return $this->has($name) ?
            $this->menus[$name]->setBindings($bindings)->render($presenter) : null;
    }

    /**
     * Get a stylesheet for enable multilevel menu.
     *
     * @return mixed
     */
    public function style()
    {
        return $this->views->make('menus::style')->render();
    }

    /**
     * Get all menus.
     *
     * @return array
     */
    public function all()
    {
        return $this->menus;
    }

    /**
     * Get count from all menus.
     *
     * @return int
     */
    public function count()
    {
        return count($this->menus);
    }

    /**
     * Empty the current menus.
     */
    public function destroy()
    {
        $this->menus = [];
    }
}
