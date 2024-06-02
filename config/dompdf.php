<?php
return [
    'show_warnings' => false,
    'public_path' => null,
    'log_output_file' => null,
    'temp_dir' => sys_get_temp_dir(),
    'font_dir' => public_path('assets/fonts/'),
    'font_cache' => public_path('assets/fonts/'),
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_font' => 'Amiri',
    'dpi' => 96,
    'enable_php' => false,
    'enable_javascript' => true,
    'enable_remote' => true,
    'font_height_ratio' => 1.1,
    'is_html5_parser_enabled' => true,
    'is_font_subsetting_enabled' => false,
    'pdf_backend' => 'CPDF',
    'default_font_size' => '12',
    'fonts' => [
        'Amiri' => [
            'R' => 'Amiri-Regular.ttf',
            'B' => 'Amiri-Bold.ttf',
            'I' => 'Amiri-Italic.ttf',
            'BI' => 'Amiri-BoldItalic.ttf',
        ]
    ]
];
