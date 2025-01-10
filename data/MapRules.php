<?php 
return [
    'convert' => [
        
    ],
    'links' => [
        'PIN2SOCKET' => [
            'pfs' => [
                'source' => 'circular_connectors_pin',
                'destination' => 'circular_connectors_socket'
            ],
            'rule' => [
                0 => [
                    0 => [
                        'id' => 1,
                        'source' => 'source_pf.connectionThread',
                        'condition' => '=',
                        'destination' => 'destination_pf.connectionThread'
                    ]
                ],
                1 => 'AND',
                2 => [
                    0 => [
                        'id' => 2,
                        'source' => 'source_pf.noOfContacts',
                        'condition' => '=',
                        'destination' => 'destination_pf.noOfContacts'
                    ]
                ]
            ]
        ]
    ],
    'required_pfs' => [
        0 => 'circular_connectors_pin',
        1 => 'circular_connectors_socket'
    ],
    'group_attrs' => [
        'circular_connectors_pin' => [
            0 => 'noOfContacts',
            1 => 'connectionThread'
        ],
        'circular_connectors_socket' => [
            0 => 'noOfContacts',
            1 => 'connectionThread'
        ]
    ],
    'replacement_rules' => [
        0 => [
            'pattern' => '/H2(1155)/',
            'replacement' => '1155'
        ],
        1 => [
            'pattern' => '/Mini-ATX/',
            'replacement' => 'Mini'
        ],
        2 => [
            'pattern' => '/Micro-ATX/',
            'replacement' => 'Micro'
        ],
        3 => [
            'pattern' => '/Mini ATX/',
            'replacement' => 'Mini'
        ],
        4 => [
            'pattern' => '/Micro ATX/',
            'replacement' => 'Micro'
        ],
        5 => [
            'pattern' => '/Extended ATX/',
            'replacement' => 'Micro'
        ],
        6 => [
            'pattern' => '/Socket H3/',
            'replacement' => ''
        ],
        7 => [
            'pattern' => '/Socket/',
            'replacement' => ''
        ],
        8 => [
            'pattern' => '/LGA/',
            'replacement' => ''
        ],
        9 => [
            'pattern' => '/SoDIMM/',
            'replacement' => ''
        ]
    ],
    'errors' => [
        'circular_connectors_pin2circular_connectors_socket' => [
            1 => [
                'rule' => 'circular_connectors_pin.noOfContacts = circular_connectors_socket.noOfContacts',
                'id' => 1
            ]
        ]
    ],
    'counters' => [
        
    ],
    'quantity_new' => [
        
    ]
];
