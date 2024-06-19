<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdf8aa192f7adbabcd1dc12d13c83136a
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Leo\\Simplex\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Leo\\Simplex\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdf8aa192f7adbabcd1dc12d13c83136a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdf8aa192f7adbabcd1dc12d13c83136a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdf8aa192f7adbabcd1dc12d13c83136a::$classMap;

        }, null, ClassLoader::class);
    }
}