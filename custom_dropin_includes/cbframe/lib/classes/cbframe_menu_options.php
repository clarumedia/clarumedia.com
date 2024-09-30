<?php


class cbframe_menu_options extends cbframe_menu_options_core {


    public $arrOverrideMenuOptions=[


        "relator_erp" => [
            "title"=>"Relator ERP",
            "href"=>"/relator",
            "items"=>[
                "relator_overview"=>[
                    "href"=>"/relator",
                ],
                "relator_help"=>[
                    "href"=>"/relator/help/",
                ],
                "relator_training_videos"=>[
                    "href"=>"/videos/relator/training/",
                ],



            ]
        ],

        "multimedia" => [
            "onclick"=>"window.location='/multimedia'",            
        ],

        "contact" => [
            "onclick"=>"window.location='/contact'",
        ],

        "dms" => [
            "items"=>[
                "charts"=>[
                    "href"=>"/app-virtual-file-collection-edit.php?file_collection_id=101",
                ],
            ],
        ],

    ];

}