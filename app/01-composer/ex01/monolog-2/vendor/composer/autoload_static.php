<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3e74c0318d7fda7246ed52c29163fa29
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3e74c0318d7fda7246ed52c29163fa29::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3e74c0318d7fda7246ed52c29163fa29::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3e74c0318d7fda7246ed52c29163fa29::$classMap;

        }, null, ClassLoader::class);
    }
}
