<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityCharts\Foundation\ThirdParty\Gettext\Generators;

use GravityKit\GravityCharts\Foundation\ThirdParty\Gettext\Translations;

abstract class Generator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public static function toFile(Translations $translations, $file, array $options = [])
    {
        $content = static::toString($translations, $options);

        if (file_put_contents($file, $content) === false) {
            return false;
        }

        return true;
    }
}
