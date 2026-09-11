@props(['status' => 'pending', 'dot' => true])

@php
$map = [
    'pending'    => ['label' => 'Pending',    'class' => 'badge-pending'],
    'paid'       => ['label' => 'Lunas',      'class' => 'badge-success'],
    'failed'     => ['label' => 'Gagal',      'class' => 'badge-error'],
    'expired'    => ['label' => 'Kadaluarsa', 'class' => 'badge-gray'],
    'refunded'   => ['label' => 'Refund',     'class' => 'badge-info'],
    'active'     => ['label' => 'Aktif',      'class' => 'badge-active'],
    'used'       => ['label' => 'Digunakan',  'class' => 'badge-success'],
    'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'badge-error'],
    'open'       => ['label' => 'Buka',       'class' => 'badge-active'],
    'closed'     => ['label' => 'Ditutup',    'class' => 'badge-gray'],
    'full'       => ['label' => 'Penuh',      'class' => 'badge-error'],
    'coming_soon'=> ['label' => 'Segera',     'class' => 'badge-pending'],
];

$data  = $map[strtolower($status)] ?? ['label' => ucfirst($status), 'class' => 'badge-gray'];
$dot_c = match($data['class']) {
    'badge-success' => 'bg-green-500',
    'badge-pending' => 'bg-amber-500',
    'badge-error'   => 'bg-red-500',
    'badge-active'  => 'bg-blue-600',
    'badge-info'    => 'bg-blue-500',
    default         => 'bg-slate-400',
};
@endphp

<span class="badge {{ $data['class'] }}">
    @if($dot)
    <span class="w-1.5 h-1.5 rounded-full {{ $dot_c }} inline-block"></span>
    @endif
    {{ $data['label'] }}
</span>
