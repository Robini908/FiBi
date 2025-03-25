@props(['theme' => 'light'])

@php
    $colors = [
        'primary' => [
            'base' => '34, 197, 94', // green-500
            'light' => '187, 247, 208', // green-200
            'dark' => '22, 101, 52', // green-800
            'contrast' => '255, 255, 255', // white
        ],
        'secondary' => [
            'base' => '107, 114, 128', // gray-500
            'light' => '229, 231, 235', // gray-200
            'dark' => '31, 41, 55', // gray-800
            'contrast' => '255, 255, 255', // white
        ],
        'success' => [
            'base' => '34, 197, 94', // green-500
            'light' => '187, 247, 208', // green-200
            'dark' => '22, 101, 52', // green-800
            'contrast' => '255, 255, 255', // white
        ],
        'info' => [
            'base' => '6, 182, 212', // cyan-500
            'light' => '165, 243, 252', // cyan-200
            'dark' => '21, 94, 117', // cyan-800
            'contrast' => '255, 255, 255', // white
        ],
        'warning' => [
            'base' => '245, 158, 11', // amber-500
            'light' => '253, 230, 138', // amber-200
            'dark' => '146, 64, 14', // amber-800
            'contrast' => '255, 255, 255', // white
        ],
        'danger' => [
            'base' => '239, 68, 68', // red-500
            'light' => '254, 202, 202', // red-200
            'dark' => '153, 27, 27', // red-800
            'contrast' => '255, 255, 255', // white
        ],
        'surface' => $theme === 'dark' ? [
            'base' => '31, 41, 55', // gray-800
            'light' => '55, 65, 81', // gray-700
            'dark' => '17, 24, 39', // gray-900
            'contrast' => '243, 244, 246', // gray-100
        ] : [
            'base' => '255, 255, 255', // white
            'light' => '249, 250, 251', // gray-50
            'dark' => '243, 244, 246', // gray-100
            'contrast' => '17, 24, 39', // gray-900
        ],
        'text' => $theme === 'dark' ? [
            'base' => '243, 244, 246', // gray-100
            'muted' => '156, 163, 175', // gray-400
            'light' => '209, 213, 219', // gray-300
            'dark' => '255, 255, 255', // white
        ] : [
            'base' => '55, 65, 81', // gray-700
            'muted' => '107, 114, 128', // gray-500
            'light' => '156, 163, 175', // gray-400
            'dark' => '17, 24, 39', // gray-900
        ]
    ];
@endphp

<style>
    :root {
        @foreach($colors as $colorName => $colorValues)
            --color-{{ $colorName }}: {{ $colorValues['base'] }};
            --color-{{ $colorName }}-light: {{ $colorValues['light'] }};
            --color-{{ $colorName }}-dark: {{ $colorValues['dark'] }};
            --color-{{ $colorName }}-contrast: {{ $colorValues['contrast'] }};
        @endforeach
    }
</style> 