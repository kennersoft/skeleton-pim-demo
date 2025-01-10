<?php 
return [
    'type' => [
        'custom' => [
            'icon' => [
                'nature' => 'image',
                'createVersion' => false,
                'validations' => [
                    'mime' => [
                        'pattern' => 'image',
                        'message' => 'Only images'
                    ],
                    'ratio' => 1,
                    'size' => [
                        'skip' => true
                    ]
                ],
                'renditions' => [
                    
                ]
            ],
            'gallery-image' => [
                'nature' => 'image',
                'createVersion' => true,
                'validations' => [
                    'mime' => [
                        'pattern' => 'image',
                        'message' => 'Only images'
                    ],
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 50000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 50000
                        ]
                    ],
                    'quality' => [
                        'min' => 0,
                        'max' => 50000
                    ],
                    'colorDepth' => [
                        0 => 8
                    ],
                    'colorSpace' => [
                        0 => 'RGB',
                        1 => 'SRGB',
                        2 => 'GRAY',
                        3 => 'UNDEFINED',
                        4 => 'TRANSPARENT',
                        5 => 'OHTA',
                        6 => 'LAB',
                        7 => 'XYZ',
                        8 => 'YCBCR',
                        9 => 'YCC',
                        10 => 'YIQ',
                        11 => 'YPBPR',
                        12 => 'YUV',
                        13 => 'CMYK',
                        14 => 'HSB',
                        15 => 'HSL',
                        16 => 'HWB',
                        17 => 'REC601LUMA',
                        18 => 'REC709LUMA',
                        19 => 'LOG',
                        20 => 'CMY'
                    ],
                    'scale' => [
                        'min' => [
                            'width' => 10,
                            'height' => 10
                        ]
                    ],
                    'unique' => [
                        
                    ]
                ],
                'renditions' => [
                    'githumb1' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => false,
                        'fileNameMask' => '{{original}}-{{date:Y-d-m}}-{{rand:5}}',
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 400,
                                'h' => 400,
                                'type' => 'crop-center'
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb2' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 800,
                                'h' => 800
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb3' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 1280,
                                'h' => 1280
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb4' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 1920,
                                'h' => 1920
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ]
                ]
            ],
            'description-image' => [
                'nature' => 'image',
                'validations' => [
                    'mime' => [
                        'pattern' => 'image',
                        'message' => 'Only images'
                    ],
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ],
                    'quality' => [
                        'min' => 10,
                        'max' => 100
                    ],
                    'colorDepth' => [
                        0 => 8
                    ],
                    'colorSpace' => [
                        0 => 'RGB',
                        1 => 'SRGB'
                    ],
                    'scale' => [
                        'min' => [
                            'width' => 1,
                            'height' => 1
                        ]
                    ],
                    'unique' => [
                        
                    ]
                ],
                'renditions' => [
                    'githumb1' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => false,
                        'fileNameMask' => '{{original}}-{{date:Y-d-m}}-{{rand:5}}',
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 400,
                                'h' => 400,
                                'type' => 'crop-center'
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb2' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 800,
                                'h' => 800
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb3' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 1280,
                                'h' => 1280
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ],
                    'githumb4' => [
                        'auto' => true,
                        'nature' => 'image',
                        'createVersion' => true,
                        'handlers' => [
                            'resize' => [
                                'upscale' => true,
                                'w' => 1920,
                                'h' => 1920
                            ]
                        ],
                        'validations' => [
                            
                        ]
                    ]
                ]
            ],
            'office-document' => [
                'nature' => 'file',
                'validations' => [
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ],
                    'extension' => [
                        0 => 'doc',
                        1 => 'dot',
                        2 => 'docx',
                        3 => 'dotx',
                        4 => 'dotm',
                        5 => 'docb',
                        6 => 'xls',
                        7 => 'xlt',
                        8 => 'xlm',
                        9 => 'xlxs',
                        10 => 'xlsm',
                        11 => 'xltx',
                        12 => 'xltm',
                        13 => 'ppt',
                        14 => 'pot',
                        15 => 'pps',
                        16 => 'pptx',
                        17 => 'pptm',
                        18 => 'potx',
                        19 => 'potm',
                        20 => 'ppam',
                        21 => 'ppsx',
                        22 => 'ppsm',
                        23 => 'sldx',
                        24 => 'sldm',
                        25 => 'mdb',
                        26 => 'accdb',
                        27 => 'accdr',
                        28 => 'accdt',
                        29 => 'vaccdr',
                        30 => 'rtf',
                        31 => 'odt',
                        32 => 'ott',
                        33 => 'odm',
                        34 => 'ods',
                        35 => 'ots',
                        36 => 'odg',
                        37 => 'otg',
                        38 => 'odp',
                        39 => 'otp',
                        40 => 'odf',
                        41 => 'odc',
                        42 => 'odb',
                        43 => 'xlsx'
                    ],
                    'mime' => [
                        'list' => [
                            0 => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            1 => 'application/x-vnd.oasis.opendocument.chart',
                            2 => 'application/vnd.oasis.opendocument.chart',
                            3 => 'application/vnd.oasis.opendocument.formula',
                            4 => 'application/x-vnd.oasis.opendocument.formula',
                            5 => 'application/vnd.oasis.opendocument.presentation-template',
                            6 => 'application/x-vnd.oasis.opendocument.presentation-template',
                            7 => 'application/x-vnd.oasis.opendocument.presentation',
                            8 => 'application/vnd.oasis.opendocument.presentation',
                            9 => 'application/x-vnd.oasis.opendocument.graphics-template',
                            10 => 'application/vnd.oasis.opendocument.graphics-template',
                            11 => 'application/x-vnd.oasis.opendocument.graphics',
                            12 => 'application/vnd.oasis.opendocument.graphics',
                            13 => 'application/x-vnd.oasis.opendocument.spreadsheet-template',
                            14 => 'application/vnd.oasis.opendocument.spreadsheet-template',
                            15 => 'application/x-vnd.oasis.opendocument.spreadsheet',
                            16 => 'application/vnd.oasis.opendocument.spreadsheet',
                            17 => 'application/vnd.oasis.opendocument.textmaster',
                            18 => 'application/x-vnd.oasis.opendocument.textmaster',
                            19 => 'application/doc',
                            20 => 'application/ms-doc',
                            21 => 'application/msword',
                            22 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            23 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
                            24 => 'application/vnd.ms-word.template.macroEnabled.12',
                            25 => 'application/vnd.ms-excel',
                            26 => 'application/excel',
                            27 => 'application/msexcel',
                            28 => 'application/x-excel',
                            29 => 'application/xlt',
                            30 => 'application/x-msexcel',
                            31 => 'application/x-ms-excel',
                            32 => 'application/x-dos_ms_excel',
                            33 => 'application/xls',
                            34 => 'application/vnd.ms-excel.sheet.macroEnabled.12',
                            35 => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
                            36 => 'application/vnd.ms-excel.template.macroEnabled.12',
                            37 => 'application/vnd.ms-powerpoint',
                            38 => 'application/mspowerpoint',
                            39 => 'application/ms-powerpoint',
                            40 => 'application/mspowerpnt',
                            41 => 'application/vnd-mspowerpoint',
                            42 => 'application/powerpoint',
                            43 => 'application/x-powerpoint',
                            44 => 'application/x-m',
                            45 => 'application/x-mspowerpoint',
                            46 => 'application/x-dos_ms_powerpnt',
                            47 => 'application/pot',
                            48 => 'application/x-soffic',
                            49 => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            50 => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
                            51 => 'application/vnd.openxmlformats-officedocument.presentationml.template',
                            52 => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
                            53 => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                            54 => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
                            55 => 'application/msaccess',
                            56 => 'application/x-msaccess',
                            57 => 'application/vnd.msaccess',
                            58 => 'application/vnd.ms-access',
                            59 => 'application/mdb',
                            60 => 'application/x-mdb',
                            61 => 'zz-application/zz-winassoc-mdb',
                            62 => 'application/rtf',
                            63 => 'application/x-rtf',
                            64 => 'text/rtf',
                            65 => 'text/richtext',
                            66 => 'application/x-soffice',
                            67 => 'application/vnd.oasis.opendocument.text',
                            68 => 'application/x-vnd.oasis.opendocument.text',
                            69 => 'application/vnd.oasis.opendocument.text-template'
                        ]
                    ]
                ],
                'renditions' => [
                    
                ]
            ],
            'text' => [
                'nature' => 'file',
                'validations' => [
                    'extension' => [
                        0 => 'txt'
                    ],
                    'mime' => [
                        'list' => [
                            0 => 'text/plain',
                            1 => 'application/txt'
                        ]
                    ],
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ]
                ],
                'renditions' => [
                    
                ]
            ],
            'csv' => [
                'nature' => 'file',
                'validations' => [
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ],
                    'mime' => [
                        'list' => [
                            0 => 'text/comma-separated-values',
                            1 => 'text/csv',
                            2 => 'application/csv',
                            3 => 'application/excel',
                            4 => 'application/vnd.ms-excel',
                            5 => 'application/vnd.msexcel',
                            6 => 'text/anytext',
                            7 => 'text/plain'
                        ]
                    ],
                    'extension' => [
                        0 => 'csv'
                    ]
                ],
                'renditions' => [
                    
                ]
            ],
            'pdf-document' => [
                'nature' => 'file',
                'preview' => true,
                'validations' => [
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ],
                    'mime' => [
                        'list' => [
                            0 => 'application/pdf',
                            1 => 'application/x-pdf',
                            2 => 'application/acrobat',
                            3 => 'applications/vnd.pdf',
                            4 => 'text/pdf',
                            5 => 'text/x-pdf'
                        ]
                    ],
                    'extension' => [
                        0 => 'pdf'
                    ],
                    'PDFValidation' => [
                        
                    ]
                ],
                'renditions' => [
                    
                ]
            ],
            'archive' => [
                'nature' => 'file',
                'validations' => [
                    'size' => [
                        'private' => [
                            'min' => 0,
                            'max' => 100000000
                        ],
                        'public' => [
                            'min' => 0,
                            'max' => 100000000
                        ]
                    ],
                    'mime' => [
                        'list' => [
                            0 => 'application/x-archive',
                            1 => 'application/x-cpio',
                            2 => 'application/x-shar',
                            3 => 'application/x-iso9660-image',
                            4 => 'application/x-sbx',
                            5 => 'application/x-tar',
                            6 => 'application/x-bzip2',
                            7 => 'application/gzip',
                            8 => 'application/x-gzip',
                            9 => 'application/x-lzip',
                            10 => 'application/x-lzma',
                            11 => 'application/x-lzop',
                            12 => 'application/x-snappy-framed',
                            13 => 'application/x-xz',
                            14 => 'application/x-compress',
                            15 => 'application/x-7z-compressed',
                            16 => 'application/x-ace-compressed',
                            17 => 'application/x-astrotite-afa',
                            18 => 'application/x-alz-compressed',
                            19 => 'application/vnd.android.package-archive',
                            20 => 'application/octet-stream',
                            21 => 'application/x-freearc',
                            22 => 'application/x-arj',
                            23 => 'application/x-b1',
                            24 => 'application/vnd.ms-cab-compressed',
                            25 => 'application/x-cfs-compressed',
                            26 => 'application/x-dar',
                            27 => 'application/x-dgc-compressed',
                            28 => 'application/x-apple-diskimage',
                            29 => 'application/x-gca-compressed',
                            30 => 'application/java-archive',
                            31 => 'application/x-lzh',
                            32 => 'application/x-lzx',
                            33 => 'application/x-rar',
                            34 => 'application/x-rar-compressed',
                            35 => 'application/x-stuffit',
                            36 => 'application/x-stuffitx',
                            37 => 'application/x-gtar',
                            38 => 'application/x-ms-wim',
                            39 => 'application/x-xar',
                            40 => 'application/zip',
                            41 => 'application/x-zoo',
                            42 => 'application/x-par2'
                        ]
                    ]
                ],
                'renditions' => [
                    
                ]
            ]
        ],
        'default' => [
            'validations' => [
                
            ],
            'renditions' => [
                
            ]
        ]
    ],
    'attributeMapping' => [
        'size' => [
            'field' => 'size'
        ],
        'orientation' => [
            'field' => 'orientation'
        ],
        'width' => [
            'field' => 'width'
        ],
        'height' => [
            'field' => 'height'
        ],
        'color-depth' => [
            'field' => 'colorDepth'
        ],
        'color-space' => [
            'field' => 'colorSpace'
        ]
    ]
];
