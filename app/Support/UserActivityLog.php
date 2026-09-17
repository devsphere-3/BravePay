<?php

namespace App\Support;

use Illuminate\Support\Arr;

class UserActivityLog
{
    public const SESSION_KEY = 'admin_registrations';

    public static function all(): array
    {
        $items = session(self::SESSION_KEY, []);

        return array_map(function ($item) {
            $item = is_object($item) ? (array) $item : $item;

            if (!isset($item['created_at'])) {
                $item['created_at'] = now()->toDateTimeString();
            }

            $item['competition'] = $item['competition'] ?? ['name' => 'Event'];
            $item['payment'] = $item['payment'] ?? ['status' => $item['status'] ?? 'pending'];
            $item['tickets'] = $item['tickets'] ?? [];

            return $item;
        }, is_array($items) ? $items : []);
    }

    public static function saveRegistration(array $registration): void
    {
        $items = self::all();
        $orderCode = $registration['order_code'] ?? null;

        if (!$orderCode) {
            return;
        }

        $normalized = array_merge([
            'id' => $orderCode,
            'status' => 'pending',
            'payment' => ['status' => 'pending', 'amount' => 0],
            'tickets' => [],
            'created_at' => now()->toDateTimeString(),
        ], $registration, [
            'id' => $orderCode,
        ]);

        $index = collect($items)->search(fn ($item) => ($item['order_code'] ?? null) === $orderCode);

        if ($index === false) {
            $items[] = $normalized;
        } else {
            $items[$index] = array_merge($items[$index], $normalized, [
                'updated_at' => now()->toDateTimeString(),
            ]);
        }

        session([self::SESSION_KEY => $items]);
    }

    public static function updateRegistration(string $orderCode, array $updates): void
    {
        $items = self::all();
        $index = collect($items)->search(fn ($item) => ($item['order_code'] ?? null) === $orderCode);

        if ($index === false) {
            return;
        }

        $items[$index] = array_merge($items[$index], $updates, [
            'id' => $items[$index]['id'] ?? $orderCode,
            'updated_at' => now()->toDateTimeString(),
        ]);

        session([self::SESSION_KEY => $items]);
    }

    public static function findRegistration(string $identifier): ?array
    {
        foreach (self::all() as $item) {
            if (($item['id'] ?? null) === $identifier || ($item['order_code'] ?? null) === $identifier) {
                return $item;
            }
        }

        return null;
    }

    public static function recent(): array
    {
        $items = self::all();
        usort($items, fn ($a, $b) => strtotime($b['created_at'] ?? now()) <=> strtotime($a['created_at'] ?? now()));

        return array_slice($items, 0, 10);
    }

    public static function dashboardStats(): array
    {
        $registrations = self::all();
        $totalParticipants = 0;
        $totalRevenue = 0;
        $paid = 0;
        $pending = 0;
        $failed = 0;
        $checkedIn = 0;

        foreach ($registrations as $reg) {
            $count = (int) ($reg['participant_count'] ?? 0);
            $totalParticipants += $count;

            $status = strtolower((string) ($reg['status'] ?? ($reg['payment']['status'] ?? 'pending')));
            if ($status === 'paid') {
                $paid++;
                $totalRevenue += (int) ($reg['total_amount'] ?? 0);
            } elseif ($status === 'pending') {
                $pending++;
            } elseif ($status === 'failed') {
                $failed++;
            }

            foreach (($reg['tickets'] ?? []) as $ticket) {
                if (($ticket['status'] ?? null) === 'used') {
                    $checkedIn++;
                }
            }
        }

        return [
            'total_registrations' => count($registrations),
            'total_participants' => $totalParticipants,
            'total_transactions' => count($registrations),
            'paid_transactions' => $paid,
            'pending_transactions' => $pending,
            'failed_transactions' => $failed,
            'total_revenue' => $totalRevenue,
            'checked_in' => $checkedIn,
        ];
    }
}
