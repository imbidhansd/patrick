<?php

return [
    'mode'                  => 'utf-8',
    'format'                => 'A4',
    'author'                => env('APP_NAME'),
    'subject'               => '',
    'keywords'              => '',
    'creator'               => env('APP_NAME'),
    'display_mode'          => 'fullpage',
    'tempDir'               => base_path('temp/'),
    'margin_top'			=> 35,
    'custom_font_path'      => public_path('fonts/'),
	'custom_font_data'      => [
		'signature_font' => [
			'R'  => 'Blancha.ttf',
		]
	]
];
