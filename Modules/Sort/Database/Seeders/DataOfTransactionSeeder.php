<?php

namespace Modules\Sort\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sort\App\Models\CancellationReason;
use Modules\Sort\App\Models\OperationType;
use Modules\Sort\App\Models\RouteType;
use Modules\Sort\App\Models\TransactionStatus;

class DataOfTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Get the route data and create a new RouteType entry
        $data_route = $this->getDataRoute();
        foreach ($data_route as $data_route_){
            RouteType::create($data_route_);
        }
        // Get the operation type data and create a new OperationType entry
        $data_operation_type = $this->getOperationType();
        foreach ($data_operation_type as $data_operation_type_){
            OperationType::create($data_operation_type_);
        }


        // Get the transaction status data and create a new TransactionStatus entry
        $data_transaction_status = $this->getTransactionStatus();
        foreach ($data_transaction_status as $data_transaction_status_ ){
            TransactionStatus::create($data_transaction_status_);

        }
        $data_cancellation_reason= $this->getCancellationReason();
        foreach ($data_cancellation_reason as $data_cancellation_reason_ ){
            CancellationReason::create($data_cancellation_reason_);

        }


    }

    static function getDataRoute(): array
    {
        return [
            [
                'sort'=>1,
                'en' => [
                    'title' => 'general',
                ],
                'ar' => [
                    'title' => 'عادي',
                ],
            ],
            [
                'sort'=>2,
                'en' => [
                    'title' => 'distinct',
                ],
                'ar' => [
                    'title' => 'مميز',
                ],
            ]
        ];

    }


    static function getOperationType(): array
    {
        return [
            [
                'sort'=>1,
                'en' => [
                    'title' => 'Merge'

                ],
                'ar' => [
                    'title' => 'دمج',
                ],
            ],
            [
                'sort'=>2,
                'en' => [
                    'title' => 'Sort'

                ],
                'ar' => [
                    'title' => 'فرز',
                ],
            ]
        ];

    }


    static function getTransactionStatus(): array
    {
        return [
            [
                'parent_id'=>null,
                'en' => [
                    'title' => 'Processing'

                ],
                'ar' => [
                    'title' => 'قيد التنفيذ',
                ],
            ],
            [
                'parent_id'=>null,
                'en' => [
                    'title' => 'Payment'

                ],
                'ar' => [
                    'title' => 'قيد الدفع',
                ],
            ],
            [
                'parent_id'=>null,
                'en' => [
                    'title' => 'Completed'

                ],
                'ar' => [
                    'title' => 'مكتمل',
                ],
            ],
            [
                'parent_id'=>null,
                'en' => [
                    'title' => 'Cancelled'

                ],
                'ar' => [
                    'title' => 'ملغي',
                ],
            ],

            // sub status

            [
                'parent_id'=>1,
                'en' => [
                    'title' => 'Awaiting review and approval by management.'

                ],
                'ar' => [
                    'title' => ' بانتظار المراجعة والكشف من قبل الادارة',
                ],
            ],
            [
                'parent_id'=>1,
                'en' => [
                    'title' => 'Awaiting data completion after review.'

                ],
                'ar' => [
                    'title' => 'بانتظار استكمال البيانات بعد الكشف',
                ],
            ],
            [
                'parent_id'=>1,
                'en' => [
                    'title' => 'Awaiting the issuance of the sorting or merging invoice by the authority.'

                ],
                'ar' => [
                    'title' => 'بانتظار اصدار فاتورة الفرز او الدمج من قبل الهيئة',
                ],
            ],
            [
                'parent_id'=>1,
                'en' => [
                    'title' => 'Awaiting the issuance of the sorting report.'

                ],
                'ar' => [
                    'title' => 'بانتظار اصدار محضر الفرز',
                ],
            ],
            [
                'parent_id'=>2,
                'en' => [
                    'title' => 'The application invoice has been issued. Please pay it to complete the request.'

                ],
                'ar' => [
                    'title' => 'تم اصدار فاتورة التطبيق يرجي سدادها لاستكمال الطلب',
                ],
            ],

            [
                'parent_id'=>2,
                'en' => [
                    'title' => "The authority's invoice has been issued. Please pay it to complete the request."

                ],
                'ar' => [
                    'title' => 'تم اصدار فاتورة الهيئة يرجي سدادها لاستكمال الطلب',
                ],
                [
                    'parent_id'=>2,
                    'en' => [
                        'title' => 'Waiting for payment verification'

                    ],
                    'ar' => [
                        'title' => 'بانتظار التاكد من عملية الدفع',
                    ],
                ],
            ],
        ];

    }


    static function getCancellationReason(): array
    {
        return [
            [
                'status'=>1,
                'sort'=>1,
                'en' => [
                    'title' => 'I no longer need it',

                ],
                'ar' => [
                    'title' => 'لم اعد بحاجه اليها',
                ],
            ],
            [
                'status'=>1,
                'sort'=>2,
                'en' => [
                    'title' => 'The data is incorrect',
                ],
                'ar' => [
                    'title' => 'البيانات غير صحيحة',
                ],
            ],
        ];

    }
}
