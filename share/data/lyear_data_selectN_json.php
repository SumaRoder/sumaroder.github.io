<?php
$arr = [
    [
        'id'   => '1',
        'name' => '北京市',
        'children' => [
            [
                'id' => '101',
                'name' => '北京市',
                'children' => [
                    [
                        'id' => '10101',
                        'name' => '东城区',
                    ],
                    [
                        'id' => '10102',
                        'name' => '西城区',
                    ],
                ]
            ]
        ]
    ],
    [
        
        'id'   => '5',
        'name' => '河北省',
        'children' => [
            [
                'id'   => '501',
                'name' => '石家庄市',
                'children' => [
                    [
                        'id'   => '5011',
                        'name' => '长安区',
                    ],
                    [
                        'id'   => '5012',
                        'name' => '桥西区',
                    ],
                ]
            ],
            [
                'id'   => '502',
                'name' => '邯郸市',
                'children' => [
                    [
                        'id'   => '5021',
                        'name' => '丛台区',
                    ],
                    [
                        'id'   => '5022',
                        'name' => '邯山区',
                    ],
                ]
            ],
        ]
    ]
];
echo json_encode($arr);