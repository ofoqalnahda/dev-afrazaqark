<?php

namespace Modules\Notification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\App\Models\User;
use Modules\Info\App\Models\Faq;
use Modules\Notification\App\Models\Notification;

class NotificationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_faqs=$this->getNotify();

        foreach ($data_faqs as $data_faq){
            foreach (User::all() as $user){
                $data_faq['user_id']=$user->id;
                Notification::create($data_faq);
            }

        }
    }
    static public function getNotify(){
//        'type',['Transaction','Notification','Discount']
        return [
            [
                'type' => 'Notification',
                'en' => [
                    'title' => 'Welcome to Efrz Aqarek',
                    'body' => "Welcome to the Efrz Aqarek application. We hope you have an exceptional experience with us."
                ],
                'ar' => [
                    'title' => 'اهلا بك في افرز عقارك',
                    'body' => "مرحبا بك في تطبيق افرز عقارك نتمنا لك تجربه مميزه معنا"
                ],
            ],
            [
                'type' => 'Discount',
                'en' => [
                    'title' => 'Take Advantage Now',
                    'body' => "On the occasion of Saudi National Day, enjoy a 20% discount on all our services. Get started now!"
                ],
                'ar' => [
                    'title' => 'اغتنم الفرصه الان',
                    'body' => "بمناسبة اليوم الوطني السعودي الان فقط خصم علي جميع خدماتنا 20% افرز الان"
                ],
            ]
        ];

    }
}
