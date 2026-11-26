<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Log channel "tap": every entry of level error or higher gets its own code, written in front of the
 * message and as `kode_error` in the context, so one specific failure can be quoted and searched for.
 */
class AddErrorCode
{
    public function __invoke(Logger $logger): void
    {
        $logger->getLogger()->pushProcessor(function (LogRecord $record): LogRecord {
            // A stack channel runs the processor of each channel it contains: mark once only.
            if ($record->level->isLowerThan(Level::Error) || isset($record->context['kode_error'])) {
                return $record;
            }

            $code = 'ERR-'.strtoupper(bin2hex(random_bytes(4)));

            return $record->with(
                message: "[{$code}] {$record->message}",
                context: [...$record->context, 'kode_error' => $code],
            );
        });
    }
}
