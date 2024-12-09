<?php

namespace Modules\Info\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Info\App\Models\Faq;

class InfoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /**
         *create default  faqs to app
         *
         **/
        $data_faqs=$this->getFaqs();

        foreach ($data_faqs as $data_faq){
           Faq::create($data_faq);
        }
    }


    static function getFaqs(): array
    {
        return [
            [
                'sort'=>2,
                'en' => [
                    'title' => 'How can I add a new property to the application?',
                    'description' => "<ol>
                                <li>Log in to your account on the application.</li>
                                <li>Navigate to the 'Add Property' section from the dashboard.</li>
                                <li>Fill in the required details such as property type, location, price, and features.</li>
                                <li>Upload high-quality images of the property.</li>
                                <li>Review the information to ensure accuracy and submit the listing.</li>
                                <li>Wait for the listing to be approved by the app's moderation team.</li>
                           </ol>"
                ],
                'ar' => [
                    'title' => 'كيف يمكنني إضافة عقار جديد إلى التطبيق؟',
                    'description' => "<ol>
                                <li>قم بتسجيل الدخول إلى حسابك على التطبيق.</li>
                                <li>انتقل إلى قسم 'إضافة عقار' من لوحة التحكم.</li>
                                <li>املأ التفاصيل المطلوبة مثل نوع العقار، الموقع، السعر والمواصفات.</li>
                                <li>قم برفع صور عالية الجودة للعقار.</li>
                                <li>راجع المعلومات للتأكد من دقتها ثم قم بإرسال الإعلان.</li>
                                <li>انتظر موافقة فريق المراجعة على الإعلان.</li>
                           </ol>"
                ],
            ],
            [
                'sort'=>1,
                'en' => [
                    'title' => 'How can I search for properties to rent or buy?',
                    'description' => "<ol>
                                <li>Open the application and go to the search section.</li>
                                <li>Select your preferences, such as location, property type, and budget range.</li>
                                <li>Use the filters to refine your search based on specific features (e.g., number of rooms, amenities).</li>
                                <li>Browse the listings and click on any property for more details.</li>
                                <li>Contact the property owner or agent directly through the app.</li>
                           </ol>"
                ],
                'ar' => [
                    'title' => 'كيف يمكنني البحث عن عقار للإيجار أو الشراء؟',
                    'description' => "<ol>
                                <li>افتح التطبيق وانتقل إلى قسم البحث.</li>
                                <li>اختر تفضيلاتك مثل الموقع، نوع العقار، ونطاق الميزانية.</li>
                                <li>استخدم الفلاتر لتحديد البحث بناءً على ميزات معينة (مثل عدد الغرف أو المرافق).</li>
                                <li>تصفح الإعلانات واضغط على أي عقار للحصول على المزيد من التفاصيل.</li>
                                <li>تواصل مع مالك العقار أو الوكيل مباشرة عبر التطبيق.</li>
                           </ol>"
                ],
            ],
            [
                'sort'=>3,
                'en' => [
                    'title' => 'How can I file a complaint or inquiry?',
                    'description' => "<ol>
                                <li>Log in to your account on the application.</li>
                                <li>Go to the 'Help and Support' section.</li>
                                <li>Select the type of issue you want to report (e.g., technical problem, property listing issue).</li>
                                <li>Write a detailed description of your issue or inquiry.</li>
                                <li>Attach any relevant screenshots or files to support your case.</li>
                                <li>Submit the complaint, and our team will get back to you within 24-48 hours.</li>
                           </ol>"
                ],
                'ar' => [
                    'title' => 'كيف يمكنني تقديم شكوى أو استفسار؟',
                    'description' => "<ol>
                                <li>قم بتسجيل الدخول إلى حسابك على التطبيق.</li>
                                <li>انتقل إلى قسم 'المساعدة والدعم'.</li>
                                <li>اختر نوع المشكلة التي ترغب في الإبلاغ عنها (مثل مشكلة تقنية أو مشكلة في الإعلان).</li>
                                <li>اكتب وصفًا تفصيليًا لمشكلتك أو استفسارك.</li>
                                <li>قم بإرفاق أي لقطات شاشة أو ملفات ذات صلة لدعم حالتك.</li>
                                <li>أرسل الشكوى، وسيتواصل معك فريقنا خلال 24-48 ساعة.</li>
                           </ol>"
                ],
            ]
        ];

    }
}
