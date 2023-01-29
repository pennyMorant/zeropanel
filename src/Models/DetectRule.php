<?php

namespace App\Models;
use Pkly\I18Next\I18n;
/**
 * DetectLog Model
 */
class DetectRule extends Model
{
    protected $connection = 'default';

    protected $table = 'detect_list';

    /**
     * 规则类型
     */
    public function type(): string
    {
        return $this->type == 1 ? i18n::get()->t('packet plaintext match') : i18n::get()->t('packet hex match');
    }
}