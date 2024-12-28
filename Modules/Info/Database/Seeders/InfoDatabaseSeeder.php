<?php

namespace Modules\Info\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Info\App\Models\Faq;
use Modules\Info\App\Models\Info;

class InfoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         *create default  infos pages to app
         *
         **/
        $data_infos=$this->getInfos();

        foreach ($data_infos as $data_info){
            Info::create($data_info);
        }


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

    static function getInfos(): array
    {
        return [
            [
                'slug'=>'about-us',
                'en' => [
                    'title' => 'About Us',
                    'description' => "
            <h2>Welcome to Efrz Aqarek</h2>
            <p>Efrz Aqarek is a pioneering real estate application based in Saudi Arabia. Our platform is tailored to meet the needs of property seekers, buyers, and sellers alike, offering a comprehensive and user-friendly solution for real estate transactions.</p>
            <p>Our mission is to revolutionize the real estate industry by providing:</p>
            <ul>
                <li>Efficient property listings with advanced search filters.</li>
                <li>Direct communication between buyers, sellers, and agents.</li>
                <li>Secure and transparent transactions to build trust among users.</li>
            </ul>
            <p>We aim to be your trusted partner in finding or advertising properties, whether residential, commercial, or investment-focused.</p>
        "
                ],
                'ar' => [
                    'title' => 'من نحن',
                    'description' => "
            <h2>مرحبًا بك في افرز عقارك</h2>
            <p>افرز عقارك هو تطبيق عقاري مبتكر مقره المملكة العربية السعودية. منصتنا مصممة لتلبية احتياجات الباحثين عن العقارات، المشترين والبائعين على حد سواء، حيث نقدم حلًا شاملًا وسهل الاستخدام لعمليات العقارات.</p>
            <p>مهمتنا هي تغيير تجربة التعاملات العقارية من خلال تقديم:</p>
            <ul>
                <li>قوائم عقارية فعّالة مع مرشحات بحث متقدمة.</li>
                <li>اتصال مباشر بين المشترين، البائعين والوكلاء.</li>
                <li>تعاملات آمنة وشفافة لتعزيز الثقة بين المستخدمين.</li>
            </ul>
            <p>نهدف لأن نكون شريكك الموثوق في العثور على العقارات أو الإعلان عنها، سواء كانت سكنية، تجارية أو استثمارية.</p>
        "
                ],
            ],
            [
                'slug'=>'terms-and-conditions',
                'en' => [
                    'title' => 'Terms and Conditions',
                    'description' => "
            <h2>Terms and Conditions</h2>
            <p>By using Efrz Aqarek, you agree to abide by the following terms and conditions. These are designed to ensure a secure and fair experience for all users:</p>
            <ul>
                <li><strong>Accurate Information:</strong> All users must provide true and accurate details about their properties.</li>
                <li><strong>Respectful Communication:</strong> Users should maintain professionalism and respect when interacting with others.</li>
                <li><strong>Prohibited Activities:</strong> Fraudulent activities, spamming, and misrepresentation of properties are strictly forbidden.</li>
                <li><strong>Compliance:</strong> Users must adhere to local real estate laws and regulations in Saudi Arabia.</li>
            </ul>
            <p>We reserve the right to suspend or terminate accounts that violate these terms. For detailed information, please contact our support team.</p>
        "
                ],
                'ar' => [
                    'title' => 'الشروط والأحكام',
                    'description' => "
            <h2>الشروط والأحكام</h2>
            <p>باستخدام تطبيق افرز عقارك، فإنك توافق على الالتزام بالشروط والأحكام التالية. تم تصميم هذه الشروط لضمان تجربة آمنة وعادلة لجميع المستخدمين:</p>
            <ul>
                <li><strong>معلومات دقيقة:</strong> يجب على جميع المستخدمين تقديم تفاصيل صحيحة ودقيقة حول عقاراتهم.</li>
                <li><strong>التواصل باحترام:</strong> يجب على المستخدمين الحفاظ على المهنية والاحترام عند التفاعل مع الآخرين.</li>
                <li><strong>أنشطة محظورة:</strong> يُمنع منعًا باتًا الأنشطة الاحتيالية، الرسائل غير المرغوب فيها، وتشويه خصائص العقارات.</li>
                <li><strong>الامتثال:</strong> يجب على المستخدمين الالتزام بالقوانين واللوائح العقارية المحلية في المملكة العربية السعودية.</li>
            </ul>
            <p>نحتفظ بالحق في تعليق أو إنهاء الحسابات التي تنتهك هذه الشروط. لمزيد من المعلومات التفصيلية، يرجى التواصل مع فريق الدعم الخاص بنا.</p>
        "
                ],
            ],
            [
                'slug'=>'privacy-policy',
                'en' => [
                    'title' => 'Privacy Policy',
                    'description' => "
            <h2>Privacy Policy</h2>
            <p>Your privacy is of utmost importance to us at Efrz Aqarek. This policy outlines how we handle your personal data to ensure transparency and security:</p>
            <ul>
                <li><strong>Data Collection:</strong> We collect only necessary data to improve your experience, such as contact details and property preferences.</li>
                <li><strong>Usage:</strong> Your data is used solely for facilitating property searches, communications, and personalized recommendations.</li>
                <li><strong>Security:</strong> We implement advanced security measures to protect your personal information from unauthorized access.</li>
                <li><strong>Your Rights:</strong> You have the right to access, update, or delete your data at any time by contacting our support team.</li>
            </ul>
            <p>For any concerns or questions about our privacy practices, feel free to reach out to us.</p>
        "
                ],
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'description' => "
            <h2>سياسة الخصوصية</h2>
            <p>خصوصيتك هي الأولوية القصوى لدينا في افرز عقارك. توضح هذه السياسة كيفية تعاملنا مع بياناتك الشخصية لضمان الشفافية والأمان:</p>
            <ul>
                <li><strong>جمع البيانات:</strong> نجمع فقط البيانات اللازمة لتحسين تجربتك، مثل تفاصيل الاتصال وتفضيلات العقارات.</li>
                <li><strong>الاستخدام:</strong> تُستخدم بياناتك فقط لتسهيل عمليات البحث عن العقارات، التواصل، وتقديم التوصيات المخصصة.</li>
                <li><strong>الأمان:</strong> نطبق تدابير أمان متقدمة لحماية معلوماتك الشخصية من الوصول غير المصرح به.</li>
                <li><strong>حقوقك:</strong> لديك الحق في الوصول إلى بياناتك، تحديثها أو حذفها في أي وقت عبر التواصل مع فريق الدعم لدينا.</li>
            </ul>
            <p>لأي استفسارات أو مخاوف حول ممارسات الخصوصية لدينا، لا تتردد في التواصل معنا.</p>
        "
                ],
            ]
        ];

    }
}
