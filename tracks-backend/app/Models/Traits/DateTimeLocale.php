<?php

namespace App\Models\Traits;

use Auth;
use Carbon\Carbon;
use App\Services\LocaleService;

trait DateTimeLocale
{
    private $timezone = null;

    public function getCurrentTimezone(): string
    {
        if (! empty($this->timezone)) {
            return $this->timezone;
        }

        $user = Auth::user();
        if (! empty($user)) {
            $this->timezone = $user->locale['timezone'] ?? '';
        }

        if (! empty($this->timezone)) {
            return $this->timezone;
        }

        $this->timezone = app(LocaleService::class)::FALLBACK_TIMEZONE;

        return $this->timezone;
    }

    public function fromDateTime($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = new Carbon($value);
        }

        return $value->setTimezone('UTC');
    }

    protected function asDateTime($value): Carbon
    {
        if (is_string($value)) {
            $value = new Carbon($value, 'UTC');
        }

        return $value;
    }

    protected function formatDate($value): string
    {
        if (is_string($value)) {
            $value = new Carbon($value, $this->getCurrentTimezone());
        }

        $value->setTimezone('UTC');

        return $value->format('Y-m-d H:i:s');
    }
}
