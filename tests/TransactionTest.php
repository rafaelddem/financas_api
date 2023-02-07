<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\Installment;
use financas_api\model\entity\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    // default data for correct installment
    private array $defaultData = array();
    private Transaction $transaction;

    protected function setUp() : void
    {
        $this->defaultData = [
            'id' => 0,
            'tittle' => 'Transacao',
            'transaction_date' => '2020-01-01',
            'processing_date' => '2020-01-01',
            'transaction_type' => 1,
            'gross_value' => 10.0,
            'discount_value' => 0.0,
            'relevance' => 0,
            'description' => '',
            'installments' => [
                new Installment(0, 0, '2020-01-01', 10.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
            ],
        ];

        $id = 0;
        $tittle = 'Transacao';
        $transaction_date = '2020-01-01';
        $processing_date = '2020-01-01';
        $transaction_type = 1;
        $gross_value = 10.0;
        $discount_value = 0.0;
        $installments = [
            new Installment(0, 0, '2020-01-01', 10.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
        ];
        $relevance = 0;
        $description = '';

        $this->transaction = new Transaction($id, $tittle, $transaction_date, $processing_date, $transaction_type, $gross_value, $discount_value, $installments, $relevance, $description);
    }

    /** Data Providers */

    public function dataForTittleWithAcceptedSizeAndCharacters()
    {
        return [
            'shortTittle' => ['Na'],
            'longTittle' => ['An example of a big tittle accept for transaction.'],
        ];
    }

    public function dataForTittleWithNotAcceptedSize()
    {
        return [
            'veryShortTittle' => ['N'],
            'veryLongTittle' => ['An example of a big tittle unaccept for transaction'],
        ];
    }

    public function dataForDescriptionWithAcceptedSizeAndCharacters()
    {
        return [
            'shortDescription' => [''],
            'longDescription' => ['Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has surv'],
        ];
    }

    public function dataForDescriptionWithNotAcceptedSize()
    {
        return [
            'veryLongDescription' => ['Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survi'],
        ];
    }

    public function dataForStringWithNotAcceptedCharacters()
    {
        return [
            ['Tittle with /'],
            ['Tittle with !'],
            ['Tittle with @'],
            ['Tittle with #'],
            ['Tittle with %'],
            ['Tittle with &'],
            ['Tittle with *'],
            ['Tittle with {'],
            ['Tittle with }'],
            ['Tittle with ?'],
            ['Tittle with <'],
            ['Tittle with >'],
            ['Tittle with :'],
            ['Tittle with ;'],
            ['Tittle with |'],
            ['Tittle with /'],
            // ['Tittle with $'],
            // ['Tittle with \\'],
            // ['Tittle with ['],
            // ['Tittle with ]'],
        ];
    }

    public function dataForDateInvalid()
    {
        return [
            ['AAAAAA'],
            ['yyyy-mm-dd'],
            ['999999999'],
            ['9898-98'],
            ['9898-98-98'],
            ['9898-9898-98'],
        ];
    }

    public function dataForTransactionWithAcceptedValues()
    {
        return [
            'OneInstallment_simpleValues' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 10.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 1,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 10.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_simpleValues' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 5.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 10.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_SameDates_simpleValues' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 10.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_simpleValues' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 2.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 2.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 2.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 2.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 2.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 10.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'oneInstallment_discount' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 2.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01',  8.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 1,
                    'discount_value_total' => 2.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 8.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_sameDates_discount' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 2.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 4.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-01-01', 4.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 2.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 8.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_discount' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 4.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 3.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 3.0, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 4.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 6.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_differentValues_discount' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 20.99,
                    'discount_value' => 1.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 3.99, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 1.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 19.99,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_differentValues_twoDiscount' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 20.99,
                    'discount_value' => 1.00,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 4.00, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 3.99, 0.99, 0.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 1.99,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.0,
                    'net_value' => 19.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'oneInstallment_interest' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 10.0, 0.0, 1.0, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 1,
                    'discount_value_total' => 0.0,
                    'interest_value' => 1.0,
                    'rounding_value' => 0.0,
                    'net_value' => 11.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_sameDates_interest' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 0.5, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 0.5, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 1.0,
                    'rounding_value' => 0.0,
                    'net_value' => 11.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_interest' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.0,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.0, 0.0, 1.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 5.0, 0.0, 0.75, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 1.75,
                    'rounding_value' => 0.0,
                    'net_value' => 11.75,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_differentValues_interest' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 20.99,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 4.20, 0.0, 0.2, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 4.20, 0.0, 0.2, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 4.20, 0.0, 0.2, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 4.20, 0.0, 0.2, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 4.19, 0.0, 0.2, 0.0, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 0.0,
                    'interest_value' => 1.0,
                    'rounding_value' => 0.0,
                    'net_value' => 21.99,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'oneInstallment_round' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.99,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 10.99, 0.0, 0.0, 0.01, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 1,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.01,
                    'net_value' => 11.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_sameDates_round' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.10,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.05, 0.0, 0.0, -0.05, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-01-01', 5.05, 0.0, 0.0, -0.05, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => 0.1,
                    'net_value' => 10.0,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'twoInstallments_round' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 10.5,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 5.25, 0.0, 0.0, 0.05, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 5.25, 0.0, 0.0, -0.25, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 2,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => -0.20,
                    'net_value' => 10.30,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_differentValues_round' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 20.99,
                    'discount_value' => 0.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 4.20, 0.0, 0.0, 0.05, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 4.20, 0.0, 0.0, -0.20, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 4.20, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 4.20, 0.0, 0.0, 0.0, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 4.19, 0.0, 0.0, 0.01, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 0.0,
                    'interest_value' => 0.0,
                    'rounding_value' => -0.14,
                    'net_value' => 20.85,
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
            'fiveInstallments_differentValues_discount_round' => [
                'transaction' => [
                    'id' => 0,
                    'tittle' => 'Transacao',
                    'transaction_date' => '2020-01-01',
                    'processing_date' => '2020-01-01',
                    'transaction_type' => 1,
                    'gross_value' => 59.99,
                    'discount_value' => 5.0,
                    'installments' => [
                        new Installment(0, 0, '2020-01-01', 11.00, 2.0, 0.0,  0.00, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-02-01', 11.00, 0.0, 0.0,  0.00, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-03-01', 11.00, 0.0, 1.0,  0.00, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-04-01', 11.00, 2.0, 0.0,  0.00, 1, 1, 1, ''),
                        new Installment(0, 0, '2020-05-01', 10.99, 0.0, 1.0,  0.01, 1, 1, 1, ''),
                    ],
                    'number_of_installments' => 5,
                    'discount_value_total' => 9.0,
                    'interest_value' => 2.0,
                    'rounding_value' => 0.01,
                    'net_value' => 53.00, 
                    'relevance' => 0,
                    'description' => '',
                ]
            ], 
        ];
    }

    public function dataForRelevanceWithAcceptedValues()
    {
        return [
            [0],
            [1],
            [2],
        ];
    }

    public function dataForRelevanceWithNotAcceptedValues()
    {
        return [
            // [(int) -INF],
            [-10],
            [-3],
            [-2],
            [-1],
            [3],
            [10],
            // [(int) INF],
        ];
    }

    /** Tests */

    /**
     * @dataProvider dataForTransactionWithAcceptedValues
     */
    public function testCreateInstallmentWithAcceptedValues(array $defaultData)
    {
        $data = $defaultData;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
        self::assertEquals($data['id'], $transaction->getId());
        self::assertEquals($data['tittle'], $transaction->getTittle());
        self::assertEquals($data['transaction_date'], $transaction->getTransactionDate());
        self::assertEquals($data['processing_date'], $transaction->getProcessingDate());
        self::assertEquals($data['transaction_type'], $transaction->getTransactionType());
        self::assertEquals($data['gross_value'], $transaction->getGrossValue());
        self::assertEquals($data['discount_value_total'], $transaction->getDiscountValue());
        self::assertEquals($data['relevance'], $transaction->getRelevance());
        self::assertEquals($data['description'], $transaction->getDescription());
        self::assertEquals($data['number_of_installments'], $transaction->getNumberOfInstallments());
        self::assertEquals($data['net_value'], $transaction->getNetValue());
    }

    /**
     * @dataProvider dataForTittleWithAcceptedSizeAndCharacters
     */
    public function testCreateTransactionWithTittleWithAcceptedSizeAndCharacters(string $tittle)
    {
        $data = $this->defaultData;
        $transaction = new Transaction($data['id'], $tittle, $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
        self::assertEquals($tittle, $transaction->getTittle());
    }

    /**
     * @dataProvider dataForTittleWithNotAcceptedSize
     */
    public function testCreateTransactionWithTittleWithNotAcceptedSize(string $tittle)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'tittle\' attribute need to be between 2 and 50 characters');

        $data = $this->defaultData;
        $transaction = new Transaction($data['id'], $tittle, $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    /**
     * @dataProvider dataForStringWithNotAcceptedCharacters
     */
    public function testCreateTransactionWithTittleWithNotAcceptedCharacters(string $tittle)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'tittle\' attribute only accepts letters and numbers');

        $data = $this->defaultData;
        $transaction = new Transaction($data['id'], $tittle, $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    public function testCreateInstallmentWithTransactionDateEmpty()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'transaction_date\' need to be informed');

        $data = $this->defaultData;
        $data['transaction_date'] = '';
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    /**
     * @dataProvider dataForDateInvalid
     */
    public function testCreateInstallmentWithTransactionDateInvalid(string $date)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'transaction_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')');

        $data = $this->defaultData;
        $data['transaction_date'] = $date;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    /**
     * @dataProvider dataForRelevanceWithAcceptedValues
     */
    public function testCreateTransactionTypeWithRelevanceWithAcceptedValues(int $relevance)
    {
        $data = $this->defaultData;
        $data['relevance'] = $relevance;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);

        self::assertEquals($relevance, $transaction->getRelevance());
    }

    /**
     * @dataProvider dataForRelevanceWithNotAcceptedValues
     */
    public function testCreateTransactionTypeWithRelevanceWithNotAcceptedValues(int $relevance)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'relevance\' attribute was not accepted. You need to use one of the accepted values: \'0\', \'1\' or \'2\'');

        $data = $this->defaultData;
        $data['relevance'] = $relevance;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    /**
     * @dataProvider dataForDescriptionWithAcceptedSizeAndCharacters
     */
    public function testCreateTransactionWithDescriptionWithAcceptedSizeAndCharacters(string $description)
    {
        $data = $this->defaultData;
        $data['description'] = $description;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
        self::assertEquals($description, $transaction->getdescription());
    }

    /**
     * @dataProvider dataForDescriptionWithNotAcceptedSize
     */
    public function testCreateTransactionWithDescriptionWithNotAcceptedSize(string $description)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'description\' attribute must be a maximum of 255 characters');

        $data = $this->defaultData;
        $data['description'] = $description;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }

    /**
     * @dataProvider dataForStringWithNotAcceptedCharacters
     */
    public function testCreateTransactionWithDescriptionWithNotAcceptedCharacters(string $description)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'description\' attribute only accepts letters and numbers');

        $data = $this->defaultData;
        $data['description'] = $description;
        $transaction = new Transaction($data['id'], $data['tittle'], $data['transaction_date'], $data['processing_date'], $data['transaction_type'], $data['gross_value'], $data['discount_value'], $data['installments'], $data['relevance'], $data['description']);
    }
}
?>