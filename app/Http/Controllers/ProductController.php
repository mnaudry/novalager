<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use App\Helpers\ProductHelper;

class ProductController extends Controller
{
  
    public function index()
    {
        return new ProductCollection(Product::all());
        
    }


    public function store(Request $request)
    {
    

            $validator = Validator::make($request->all(), [
                'title' => ['bail','required','unique:App\Models\Product'],
                'description' => ['required'],
                'image' => ['required',
                    File::types(['jpeg','jpg','gif','png'])->max(500),
                ],
                'pdf' => [
                    'required',
                    File::types(['pdf']),
                ],
               // 'amz_url' => ['required'],
            ], [
                'title.required' => 'Le titre est obligatoire',
                'title.unique' => 'Le produit :input existe deja',
                'description.required' => 'La description est obligatoire',
                'image.required' => "L'image est obligatoire",
                'image.mimes' => "les images supportes sont jpeg, jpg, gif ou png",
                'image.max' => "L'image ne doit pas depasser 500 ko",
                'pdf.required' => "Le fichier pdf est obligatoire",
                'pdf.mimes' => "Le fichier n'est pas un pdf",
            ]);



            if ($validator->fails()) {
               // return response()->json(["error" =>$validator->errors()],400);
              // return $this->createError($validator->errors(),400);
              return ProductHelper::createError($validator->errors(),400);
            }



            $validated = $validator->validated();

            //save file 

            $pdf =  $validated['pdf'];

            if(!$pdf->isValid()) {
               // return response()->json(['pdf' => ['Impossible de sauvegarder votre fichier pdf, veuillez recommencer']],400);
               return ProductHelper::createError(['pdf' => ['Impossible de sauvegarder votre fichier pdf, veuillez recommencer']],400);
            }



            $name = str_replace(" ","_",$validated['title']);

            $pdf->storeAs('public/pdfs', $name.".".$pdf->getClientOriginalExtension());
            $link = 'pdfs/'.$name.".".$pdf->getClientOriginalExtension();

            $validated['pdf'] =  $link ;


            $image =  $validated['image'];

            if(!$image->isValid()) {
                //return response()->json(['image' => ['Impossible de sauvegarder votre image, veuillez recommencer']],400);
                return ProductHelper::createError(['image' => ['Impossible de sauvegarder votre image, veuillez recommencer']],400);
            }



            $image->storeAs('public/images', $name.".".$image->getClientOriginalExtension());
            $link = 'images/'.$name.".".$image->getClientOriginalExtension();


            $validated['image'] =  $link ;

            //url and slug 
             $slug = str_replace(" ","-",$validated['title']);
             $slug = preg_replace("/[^a-z0-9\_\-\.]/i","",$slug);
             $validated['amz_url'] = $slug ;

            $product = Product::create($validated);

            return new ProductResource($product);

    }

  
    public function show(Product $product)
    {
       return new ProductResource($product);
    }

    public function showSlug(Request $request , $slug) {
        

        $product = Product::where('amz_url', $slug)->get();

        if(count($product) > 0){
            return new ProductResource($product[0]);
        }

       return response()->json([
        'message' => 'Record not found.'
            ], 404);
    }

}
