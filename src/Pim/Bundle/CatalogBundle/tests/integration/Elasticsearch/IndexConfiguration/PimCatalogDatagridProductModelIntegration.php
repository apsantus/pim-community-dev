<?php

namespace Pim\Bundle\CatalogBundle\tests\integration\Elasticsearch\IndexConfiguration;

/**
 * Search use cases of products and models in a "smart datagrid way".
 * It returns either products or models depending on where is information is stored.
 *
 * @author    Samir Boulil <samir.boulil@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class PimCatalogDatagridProductModelIntegration extends AbstractPimCatalogProductModelIntegration
{
    public function testDefaultDisplay()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testSearchTshirtInDescription()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        [
                            'query_string' => [
                                'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                'query'         => '*T-shirt*',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts(
            $productsFound,
            [
                'model-tshirt',
                'model-tshirt-unique-color',
                'model-tshirt-unique-size',
            ]
        );
    }

    public function testSearchColorRed()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.color-option.<all_channels>.<all_locales>' => ['red'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, [
            'model-tshirt-red',
            'model-tshirt-unique-color',
            'tshirt-unique-size-red',
            'running-shoes-s-red',
            'running-shoes-m-red',
            'running-shoes-l-red',
        ]);
    }

    public function testSearchColorGrey()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, ['model-tshirt-grey', 'model-hat']);
    }

    public function testSearchColorBlue()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.color-option.<all_channels>.<all_locales>' => ['blue'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, [
            'model-tshirt-blue',
            'tshirt-unique-size-blue',
            'running-shoes-s-blue',
            'running-shoes-m-blue',
            'running-shoes-l-blue',
            'watch',
        ]);
    }

    public function testSearchSizeS()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.size-option.<all_channels>.<all_locales>' => ['s'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts(
            $productsFound,
            [
                'tshirt-grey-s',
                'tshirt-blue-s',
                'tshirt-red-s',
                'tshirt-unique-color-s',
                'model-running-shoes-s',
                'biker-jacket-leather-s',
                'biker-jacket-polyester-s',
            ]
        );
    }

    public function testSearchSizeM()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.size-option.<all_channels>.<all_locales>' => ['m'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts(
            $productsFound,
            [
                'tshirt-grey-m',
                'tshirt-red-m',
                'tshirt-blue-m',
                'tshirt-unique-color-m',
                'hat-m',
                'model-running-shoes-m',
                'biker-jacket-leather-m',
                'biker-jacket-polyester-m',
            ]
        );
    }

    public function testSearchColorGreyAndSizeS()
    {
        $query = [
            'query' => [
                'bool' => [
                    'minimum_should_match' => 1,
                    'should'               => [
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'terms' => [
                                                    'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.size-option.<all_channels>.<all_locales>' => ['s'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'terms' => [
                                                    'values.size-option.<all_channels>.<all_locales>' => ['s'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'has_parent' => [
                                                    'type'  => 'pim_catalog_product_model_parent_2',
                                                    'query' => [
                                                        'terms' => [
                                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.size-option.<all_channels>.<all_locales>' => ['s'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, ['tshirt-grey-s']);
    }

    public function testSearchColorGreyAndSizeM()
    {
        $query = [
            'query' => [
                'bool' => [
                    'minimum_should_match' => 1,
                    'should'               => [
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'terms' => [
                                                    'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.size-option.<all_channels>.<all_locales>' => ['m'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'terms' => [
                                                    'values.size-option.<all_channels>.<all_locales>' => ['m'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'has_parent' => [
                                                    'type'  => 'pim_catalog_product_model_parent_2',
                                                    'query' => [
                                                        'terms' => [
                                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.size-option.<all_channels>.<all_locales>' => ['m'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, ['tshirt-grey-m', 'hat-m']);
    }

    /**
     * Search for a model parent 1 in its values and the value of his parent.
     *
     * @group todo
     */
    public function testSearchColorGreyAndDescriptionTshirt()
    {
        $this->getAllClauses([], [], []);

        $query = [
            'query' => [
                'bool' => [
                    'minimum_should_match' => 1,
                    'should'               => [
                        // Color in level 1 - Description in level 2
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_2',
                                            'query' => [
                                                'query_string' => [
                                                    'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                                    'query'         => '*T-shirt*',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        // Color and description in level 1
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'query_string' => [
                                                    'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                                    'query'         => '*T-shirt*',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'terms' => [
                                                    'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        // Color and Description in level product
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'query_string' => [
                                            'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                            'query'         => '*T-shirt*',
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        // Color in level product and description in level 1
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'query_string' => [
                                                    'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                                    'query'         => '*T-shirt*',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        // Color in level product and description in level 2
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'has_parent' => [
                                                    'type'  => 'pim_catalog_product_model_parent_2',
                                                    'query' => [
                                                        'query_string' => [
                                                            'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                                            'query'         => '*T-shirt*',
                                                        ],

                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'terms' => [
                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        // Color and description in level 2
                        [
                            'bool' => [
                                'filter' => [
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'has_parent' => [
                                                    'type'  => 'pim_catalog_product_model_parent_2',
                                                    'query' => [
                                                        'query_string' => [
                                                            'default_field' => 'values.description-text.<all_channels>.<all_locales>',
                                                            'query'         => '*T-shirt*',
                                                        ],

                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'has_parent' => [
                                            'type'  => 'pim_catalog_product_model_parent_1',
                                            'query' => [
                                                'has_parent' => [
                                                    'type'  => 'pim_catalog_product_model_parent_2',
                                                    'query' => [
                                                        'terms' => [
                                                            'values.color-option.<all_channels>.<all_locales>' => ['grey'],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, ['model-tshirt-grey']);
    }

    public function testSearchMaterialCotton()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.material-option.<all_channels>.<all_locales>' => ['cotton'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts(
            $productsFound,
            [
                'model-tshirt-grey',
                'model-tshirt-red',
                'model-tshirt-unique-color',
                'model-tshirt-unique-size',
            ]
        );
    }

    public function testSearchMaterialLeather()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.material-option.<all_channels>.<all_locales>' => ['leather'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts(
            $productsFound,
            [
                'model-running-shoes',
                'model-biker-jacket-leather',
            ]
        );
    }

    /**
     * Is not part of any use case but a proof of concept regarding the query of an attribute which is hold by the
     * model_parent_2 (grand father).
     */
    public function testSearchSizeMAndGrandParentColorWhite()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        [
                            'has_parent' => [
                                'type'  => 'pim_catalog_product_model_parent_1',
                                'query' => [
                                    'has_parent' => [
                                        'type'  => 'pim_catalog_product_model_parent_2',
                                        'query' => [
                                            'terms' => [
                                                'values.color-option.<all_channels>.<all_locales>' => ['white'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'terms' => [
                                'values.size-option.<all_channels>.<all_locales>' => ['m'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults(
            $query,
            [
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_1',
                AbstractPimCatalogProductModelIntegration::PRODUCT_MODEL_DOCUMENT_TYPE . '_2',
            ]
        );

        $this->assertProducts($productsFound, ['biker-jacket-polyester-m', 'biker-jacket-leather-m']);
    }

    /**
     * @param $attributes
     * @param $values
     * @param $levels
     */
    protected function getAllClauses($attributes, $values, $levels)
    {
        $levels = ['level_1', 'level_2', 'product'];
        $clauses = [
            ['attribute' => 'description', 'value' => 'tshirt'],
            ['attribute' => 'color', 'value' => 'gray'],
//            ['attribute' => 'bite', 'value' => 'chatte'],
//            ['attribute' => 'bite1', 'value' => 'chatte1'],
        ];
        $query = [
            'query' => [
                'bool' => [
                    'should' => [],
                ],
            ],
        ];

        // Pour chaque niveaux i
        for ($i = 0; $i < count($levels); $i++) {
            // pour chaque niveau j
            for ($j = 0; $j < count($levels); $j++) {
                // Si ils sont égaux, alors on ajoute toutes les clauses à ce niveau
//                if ($i === $j) {
//                    $simpleClauses = [];
//                    foreach ($clauses as $clause) {
//                        $simpleClause = $this->getFormattedClause(
//                            $levels[$i],
//                            $clause['attribute'],
//                            $clause['value']
//                        );
//                        $query['query']['bool']['should'][] = [
//                            'bool' => [
//                                'filter' => $simpleClause,
//                            ],
//                        ];
//                    }
//                } else {
                    for ($k = 0; $k < count($clauses); $k++) {
                        for ($l = $k; $l < count($clauses); $l++) {
                            if ($k !== $l) {
                                $query['query']['bool']['should'][] = [
                                    'bool' => [
                                        'filter' => [
                                            $this->getformattedclause($levels[$i], $clauses[$k]['attribute'],
                                                $clauses[$k]['value']),
                                            $this->getFormattedClause($levels[$j], $clauses[$l]['attribute'],
                                                $clauses[$l]['value']),
                                        ],
                                    ],
                                ];
                            }
                        }
                    }
//                }
            }
        }

        echo "hello \n";
        var_dump(count($query['query']['bool']['should']));
        var_dump(json_encode($query));

        return $query;
    }

    private function getFormattedClause($level, $attribute, $value)
    {
        switch($level) {
            case 'level_1':
                return [
                    'has_parent' => [
                        'type' => 'pim_catalog_product_mdel_parent_1',
                        'query' => [
                            'term' => [
                                $attribute => $value
                            ]
                        ]
                    ]
                ];
                break;
            case 'level_2':
                return [
                    'has_parent' => [
                        'type'  => 'pim_catalog_product_model_parent_1',
                        'query' => [
                            'has_parent' => [
                                'type'  => 'pim_catalog_product_mdel_parent_2',
                                'query' => [
                                    'term' => [
                                        $attribute => $value,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
                break;
            case 'product':
                return [
                    'term' => [
                        $attribute => $value,
                    ],
                ];
                break;
            default:
                var_dump("bite");
                break;
        }
    }

    protected function get_combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }

        return $result;
    }

}
