<?php
declare(strict_types=1);

namespace App\Core;

/** Audit trail of admin actions: who changed what, and when. */
final class Audit
{
    /** @param array<string,mixed> $details */
    public static function log(string $action, string $entity = '', int|string|null $entityId = null, array $details = []): void
    {
        try {
            Database::insert('audit_log', [
                'user_id' => Auth::id(),
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId === null ? null : (string)$entityId,
                'details' => $details ? json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
                'ip_hash' => Request::ipHash(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Logger::error('Audit log failed: ' . $e->getMessage());
        }
    }
}
