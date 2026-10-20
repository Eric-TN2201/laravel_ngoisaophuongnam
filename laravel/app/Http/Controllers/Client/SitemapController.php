<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\SubCategory;
use XMLWriter;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElementNS(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $add = function (string $url, $updatedAt = null) use ($xml) {
            $xml->startElement('url');
            $xml->writeElement('loc', $url);
            // Omit dates for static pages rather than inventing a modification time.
            if ($updatedAt) {
                $xml->writeElement('lastmod', $updatedAt->toAtomString());
            }
            $xml->endElement();
        };

        foreach (['home', 'about', 'contact', 'product.index', 'news.index', 'service.index'] as $route) {
            $add(route($route));
        }

        foreach (Category::where('status', 1)->cursor() as $category) {
            $add(route('product.category', ['category' => $category]), $category->updated_at);
        }

        $subCategories = SubCategory::where('status', 1)
            ->whereHas('category', fn ($query) => $query->where('status', 1))
            ->with('category');
        foreach ($subCategories->lazyById(500) as $subCategory) {
            $add(route('product.category', ['category' => $subCategory->category, 'subCategory' => $subCategory]), $subCategory->updated_at);
        }

        foreach (Product::visible()->cursor() as $product) {
            $add(route('product.show', $product), $product->updated_at);
        }
        foreach (News::posted()->cursor() as $news) {
            $add(route('news.show', $news), $news->updated_at);
        }
        foreach (Service::posted()->cursor() as $service) {
            $add(route('service.show', $service), $service->updated_at);
        }

        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $body = "User-agent: *\nDisallow: /spn/admin\nDisallow: /admin-fast-route\n\nSitemap: ".route('sitemap')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
