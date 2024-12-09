<?php

namespace Modules\Home\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Home\App\Models\Offer;
use Modules\Home\App\Models\Slider;

class HomeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Create Sliders default
         */
        $type_sliders=['None','ULR','Sort','Merge'];
        foreach ($type_sliders as $key =>$type_slider) {
            Slider::create([
               'type'=> $type_slider,
               'sort'=> $key,
               'url'=> $type_slider =='ULR'?'https://github.com/':null ,
            ]);
        }


        /**
         * Create offers default
         */
        $names_offers=['test 1','test 2'];
        foreach ($names_offers as $key =>$names_offer) {
            Offer::create([
                'name'=> $names_offer,
                'sort'=> $key,
            ]);
        }

    }
}
