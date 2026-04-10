<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Management API',
            ],

            'routes' => [
                'api' => 'api/documentation',
            ],

            'paths' => [
                'use_absolute_path' => true,
                'swagger_ui_assets_path' => 'vendor/swagger-api/swagger-ui/dist/',
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => 'json',

                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',

            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            'group_options' => [],
        ],

        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => null,
            'excludes' => [],
        ],

        'scanOptions' => [
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => '3.0.0',
        ],

        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'Token',
                ],
            ],
        ],

        // ✅ IMPORTANT (your errors were from missing these)
        'operations_sort' => null,
        'validator_url' => null,
        'additional_config_url' => null,

        'generate_always' => true,
        'generate_yaml_copy' => false,
        'proxy' => false,

        'ui' => [
            'display' => [
                'dark_mode' => false,
                'doc_expansion' => 'none',
                'filter' => true,
            ],

            'authorization' => [
                'persist_authorization' => false,
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => 'http://127.0.0.1:8000',
        ],
    ],
];
