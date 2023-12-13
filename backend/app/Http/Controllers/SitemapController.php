<?php

namespace App\Http\Controllers;

use Alomgyar\Authors\Author;
use Alomgyar\Categories\Category;
use Alomgyar\Posts\Post;
use Alomgyar\Products\Product;
use Alomgyar\Shops\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SitemapController extends Controller
{
    private function getDomain($store){
        return $store == 0
            ? env('ALOM_URL', env('APP_URL'))
            : env('OLCSO_URL', env('APP_URL'));
    }

    public function index(Request $request,$store)
    {
        $productCount = Product::where('store_'.$store,1)->count();

        $data = [$this->getDomain($store)."/sitemap/basic.xml"];
        for($i=0;$i*50000<$productCount;$i++){
            $data[] = $this->getDomain($store)."/sitemap/product/".($i+1).'.xml';
        }

        $authorCount = Author::count();
        for($i=0;$i*50000<$authorCount;$i++){
            $data[] = $this->getDomain($store)."/sitemap/author/".($i+1).'.xml';
        }

        return response()->json($data);
    }
    public function authors(Request $request,$store,$i)
    {
        $products = Product::select([
            "author_id",
            DB::raw("max(updated_at) as updated_at")
        ])
            ->join('product_author',"product_id","product.id")
            ->where('store_'.$store,1)
            ->groupBy('author_id');

        $authors = Author::select([
            "slug",
            DB::raw("greatest(author.updated_at,b.updated_at) as updated_at")
        ])
            ->leftJoinSub($products,'b',function ($query){
                $query->on('b.author_id','author.id');
            })
            ->limit(50000)
            ->offset(($i-1)*50000)
            ->get();

        $data = [];
        foreach ($authors as $author){
            $data[] = [
                'loc'=>$this->getDomain($store)."/szerzo/".$author['slug'],
                'lastmod'=>date('c', strtotime($author['updated_at']))
            ];
        }

        return response()->json($data);
    }

    public function products(Request $request,$store,$i)
    {
        $products = Product::select(["slug","updated_at"])
            ->where('store_'.$store,1)
            ->limit(50000)
            ->offset(($i-1)*50000)
            ->get();

        $data = [];
        foreach ($products as $product){
            $data[] = [
                'loc'=>$this->getDomain($store)."/konyv/".$product['slug'],
                'lastmod'=>date('c', strtotime($product['updated_at']))
            ];
        }

        return response()->json($data);
    }

    public function basic(Request $request,$store)
    {
        $data = [
            [
                'loc'=>$this->getDomain($store),
                'changefreq'=>'daily',
            ],
            [
                'loc'=>$this->getDomain($store).'/konyvlista',
                'changefreq'=>'daily'
            ],
            [
                'loc'=>$this->getDomain($store).'/akciok',
                'changefreq'=>'daily'
            ],
        ];

        $categories = Category::select(['slug'])->get();
        foreach ($categories as $category){
            $data[] = [
                'loc'=>$this->getDomain($store)."/konyvlista/".$category['slug'],
                'changefreq'=>'daily'
            ];
        }
        $subCategories = Category::select(['category.slug','subcategory.slug as slug2'])
            ->join('category_subcategory','category_subcategory.category_id','category.id')
            ->join('subcategory','category_subcategory.subcategory_id','subcategory.id')
            ->join('product_subcategory','product_subcategory.subcategory_id','subcategory.id')
            ->join('product','product.id','product_subcategory.product_id')
            ->where('subcategory.status',1)
            ->where('store_'.$store,1)
            ->groupBy('category_subcategory.id')
            ->get();

        foreach ($subCategories as $category){
            $data[] = [
                'loc'=>$this->getDomain($store)."/konyvlista/".$category['slug']."/".$category['slug2'],
                'changefreq'=>'daily'
            ];
        }

        if($store == 0){
            $data[] = [
                'loc'=>$this->getDomain($store).'/ekonyvlista',
                'changefreq'=>'daily'
            ];
            foreach ($categories as $category){
                $data[] = [
                    'loc'=>$this->getDomain($store)."/ekonyvlista/".$category['slug'],
                    'changefreq'=>'daily'
                ];
            }

            $shopUpdated = Shop::select(DB::raw("max(updated_at) as updated_at"))->pluck('updated_at');

            $data[] = [
                'loc'=>$this->getDomain($store).'/konyvesboltok',
                'lastmod'=>$shopUpdated
            ];
            $data[] = [
                'loc'=>$this->getDomain($store).'/magazin',
                'changefreq'=>'daily'
            ];

            $posts = Post::select(['slug','updated_at'])->get();
            foreach ($posts as $post){
                $data[] = [
                    'loc'=>$this->getDomain($store)."/magazin/".$post['slug'],
                    'lastmod'=>date('c', strtotime($post['updated_at']))
                ];
            }
        }

        return response()->json($data);
    }
}