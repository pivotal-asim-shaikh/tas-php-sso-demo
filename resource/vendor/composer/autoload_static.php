<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite82aad21d6a2c51df442c21e67fadcc7
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite82aad21d6a2c51df442c21e67fadcc7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite82aad21d6a2c51df442c21e67fadcc7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite82aad21d6a2c51df442c21e67fadcc7::$classMap;

        }, null, ClassLoader::class);
    }
}