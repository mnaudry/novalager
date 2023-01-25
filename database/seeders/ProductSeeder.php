<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create 3 products

        Product::create([
            'title' => 'yeastlife o',
            'description' => 'ABV YeastLife O™ is the result of intensive Research & Development studies on the impact of yeast nutrition on the fermentation of high sugar/low nutrient-based fermented beverages, including hard seltzer.',
            'image' => 'images/yeastlife_o.jpeg',
            'pdf'=> 'pdfs/yeastlife_o.pdf',
            'amz_url' => 'yeastlife-o',
        ]);


        Product::create([
            'title' => 'lalbrew novaleger',
            'description' => 'LalBrew NovaLager™ est une véritable levure hybride de fermentation basse Saccharomyces pastorianus issue de la nouvelle lignée du groupe III qui a été sélectionné pour produire des bières lager pures avec des saveurs typiques et une performance de fermentation supérieure.',
            'image' => 'images/lalBrew_novaleger.jpeg',
            'pdf'=> 'pdfs/lalBrew_novaleger.pdf',
            'amz_url' => 'lalBrew-novaleger',
        ]);


        Product::create([
            'title' => 'lalbrew farmhouse',
            'description' => 'LalBrew Farmhouse™ is a non-diastatic hybrid that has been selected to make saison-style and farmhouse style beers.',
            'image' => 'images/lalBrew_farmhouse.jpeg',
            'pdf'=> 'pdfs/lalBrew_farmhouse.pdf',
            'amz_url' => 'lalBrew-farmhouse',
        ]);
    }
}
