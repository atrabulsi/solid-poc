<?php

namespace App\Core\DDD\Domain\Models;

use App\Core\DDD\Domain\Exceptions\InvalidWaveStatus;

class WaveStatus
{
    private const DRAFT = 'draft';
    private const SCHEDULED = 'scheduled';
    private const LIVE = 'live';
    private const ENDED = 'ended';
    private const AVAILABLE_STATUSES = [
        self::DRAFT,
        self::SCHEDULED,
        self::LIVE,
        self::ENDED,
    ];

    private string $status;

    /**
     * WaveStatus constructor.
     * @param string $status
     * @throws InvalidWaveStatus
     */
    public function __construct(string $status)
    {
        $this->status = $status;
        $this->validate();
    }

    /**
     * @return WaveStatus
     */
    public static function draft(): WaveStatus
    {
        return new WaveStatus(self::DRAFT);
    }

    /**
     * @return WaveStatus
     */
    public static function scheduled(): WaveStatus
    {
        return new WaveStatus(self::SCHEDULED);
    }

    /**
     * @return WaveStatus
     */
    public static function live(): WaveStatus
    {
        return new WaveStatus(self::LIVE);
    }

    /**
     * @return WaveStatus
     */
    public static function ended(): WaveStatus
    {
        return new WaveStatus(self::ENDED);
    }

    /**
     * @throws InvalidWaveStatus
     */
    private function validate()
    {
        if (!in_array($this->status, self::AVAILABLE_STATUSES)) {
            throw new InvalidWaveStatus('Invalid wave status');
        }
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isDraft(): bool
    {
        return $this->status === self::DRAFT;
    }

    public function isScheduled(): bool
    {
        return $this->status === self::SCHEDULED;
    }

    public function isLive(): bool
    {
        return $this->status === self::LIVE;
    }

    public function isEnded(): bool
    {
        return $this->status === self::ENDED;
    }
}
