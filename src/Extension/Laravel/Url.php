<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Laravel;

use Twig_Extension;
use Twig_SimpleFunction;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

/**
 * Access Laravels url class in your Twig templates.
 */
class Url extends Twig_Extension
{
    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Create a new url extension
     *
     * @param \Illuminate\Routing\UrlGenerator
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Url';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('asset', [$url, 'asset'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('action', [$url, 'action'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('url', [$this, 'url'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('route', [$url, 'route'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('secure_url', [$url, 'secure'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('secure_asset', [$url, 'secureAsset'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction(
                'url_*',
                function ($name) {
                    $arguments = array_slice(func_get_args(), 1);
                    $name      = Str::camel($name);

                    return call_user_func_array([$this->url, $name], $arguments);
                }
            ),
        ];
    }

    public function url($path = null, $parameters = [], $secure = null)
    {
        return $this->url->to($path, $parameters, $secure);
    }
}
