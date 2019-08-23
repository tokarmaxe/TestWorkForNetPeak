<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a3770945fc465cbcb42a2409d34c86b
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'src\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a3770945fc465cbcb42a2409d34c86b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a3770945fc465cbcb42a2409d34c86b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}