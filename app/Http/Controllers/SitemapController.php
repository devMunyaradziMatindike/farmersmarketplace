<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static pages
        $sitemap .= '<sitemap><loc>https://musikawedu.co.zw/sitemap-static.xml</loc></sitemap>';
        
        // Categories
        $sitemap .= '<sitemap><loc>https://musikawedu.co.zw/sitemap-categories.xml</loc></sitemap>';
        
        // Products
        $sitemap .= '<sitemap><loc>https://musikawedu.co.zw/sitemap-products.xml</loc></sitemap>';
        
        $sitemap .= '</sitemapindex>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function static()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $staticPages = [
            ['url' => 'https://musikawedu.co.zw', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => 'https://musikawedu.co.zw/products', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => 'https://musikawedu.co.zw/login', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => 'https://musikawedu.co.zw/register', 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . $page['url'] . '</loc>';
            $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $sitemap .= '<priority>' . $page['priority'] . '</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function categories()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $categories = Category::all();
        
        foreach ($categories as $category) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>https://musikawedu.co.zw/products?category_id=' . $category->id . '</loc>';
            $sitemap .= '<lastmod>' . $category->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function products()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $products = Product::with('category', 'user')->get();
        
        foreach ($products as $product) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>https://musikawedu.co.zw/products/' . $product->id . '</loc>';
            $sitemap .= '<lastmod>' . $product->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
