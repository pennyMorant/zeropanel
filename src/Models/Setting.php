<?php

namespace App\Models;

class Setting extends Model
{
    protected $connection = 'default';
    protected $table = 'config';

    public static function obtain($item)
    {
        $config = self::where('item', '=', $item)->first();

        if ($config->type === 'bool') {
            return (bool) $config->value;
        } elseif ($config->type === 'int') {
            return (int) $config->value;
        } else if ($config->type === 'array'){
            return $config->value;
        }
        return (string) $config->value;
    }

    public static function getClass($class)
    {
        $configs = array();
        $all_configs = Setting::where('class', $class)->get();

        foreach ($all_configs as $config) {
            if ($config->type === 'bool') {
                $configs[$config->item] = (bool) $config->value;
            } elseif ($config->type === 'int') {
                $configs[$config->item] = (int) $config->value;
            } else {
                $configs[$config->item] = (string) $config->value;
            }
        }

        return $configs;
    }

    public static function getPublicConfig()
    {
        $configs = [];
        $all_configs = Setting::where('is_public', '1')->get();

        foreach ($all_configs as $config) {
            if ($config->type === 'bool') {
                $configs[$config->item] = (bool) $config->value;
            } elseif ($config->type === 'int') {
                $configs[$config->item] = (int) $config->value;
            } else if ($config->type === 'array') {
                $configs[$config->item] = $config->value;
            } else {
                $configs[$config->item] = (string) $config->value;
            }
        }

        return $configs;
    }
}