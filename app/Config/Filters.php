<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use App\Filters\AuthFilter;

class Filters extends BaseConfig
{
    /**
     * Configurasi alias filter
     */
    public array $aliases = [
        'csrf'        => CSRF::class,
        'toolbar'     => DebugToolbar::class,
        'honeypot'    => Honeypot::class,
        'auth'        => AuthFilter::class, // ✅ filter custom login
    ];

    /**
     * Filter global (jalan di semua request)
     */
    public array $globals = [
        'before' => [
            // 'csrf', // aktifkan jika butuh security form
        ],
        'after' => [
            'toolbar',
        ],
    ];

    /**
     * Filter berdasarkan method HTTP (optional)
     */
    public array $methods = [];

    /**
     * Filter berdasarkan route
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'Home/*',
                'pelanggan/*',
                'laporan/*',
                'peta/*',
                'cekTagihan/*',
                'profile/*',
            ]
        ]
    ];
}
