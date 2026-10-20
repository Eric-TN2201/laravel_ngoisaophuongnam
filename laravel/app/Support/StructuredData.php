<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StructuredData
{
    private static function text($value): string
    {
        return Str::squish(strip_tags(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    public static function forPage(Request $request, array $data, array $seo): array
    {
        $organizationId = route('home').'#organization';
        $organization = [
            '@type' => 'Organization', '@id' => $organizationId,
            'name' => $seo['site_name'], 'url' => route('home'),
            'logo' => asset('images/common/logo.png'),
        ];
        $address = self::text(setting('company_address'));
        if ($address !== '' && $address !== 'Địa chỉ công ty của bạn') {
            $organization['address'] = $address;
        }
        $phone = self::text(setting('company_phone'));
        if ($phone !== '' && preg_match('/^[+\d\s().-]+$/', $phone)) {
            $organization['telephone'] = $phone;
        }
        $email = trim((string) setting('company_email'));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !str_ends_with($email, '@example.com')) {
            $organization['email'] = $email;
        }
        $profiles = [];
        foreach (['company_facebook', 'company_zalo'] as $key) {
            $url = trim((string) setting($key));
            if (filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
                $profiles[] = $url;
            }
        }
        if ($profiles) {
            $organization['sameAs'] = array_values(array_unique($profiles));
        }
        $graph = [$organization];

        $breadcrumbs = self::breadcrumbs($request, $data, $seo);
        if (count($breadcrumbs) > 1) {
            $graph[] = [
                '@type' => 'BreadcrumbList', '@id' => $seo['canonical'].'#breadcrumb',
                'itemListElement' => array_map(fn ($item, $position) => [
                    '@type' => 'ListItem', 'position' => $position + 1,
                    'name' => self::text($item[0]), 'item' => $item[1],
                ], $breadcrumbs, array_keys($breadcrumbs)),
            ];
        }

        if ($request->routeIs('product.show') && isset($data['product'])) {
            $product = $data['product'];
            $node = [
                // Catalogue entries are informational pages without public offers or reviews.
                '@type' => 'WebPage', '@id' => $seo['canonical'].'#webpage',
                'url' => $seo['canonical'], 'name' => self::text($product->name),
                'description' => $seo['description'],
                'publisher' => ['@id' => $organizationId],
                'breadcrumb' => ['@id' => $seo['canonical'].'#breadcrumb'],
                'inLanguage' => 'vi',
            ];
            if ($product->thumbnail) {
                $node['image'] = [asset('storage/'.$product->thumbnail)];
            }
            $graph[] = $node;
        } elseif ($request->routeIs('news.show') && isset($data['news'])) {
            $news = $data['news'];
            $node = [
                '@type' => 'Article', '@id' => $seo['canonical'].'#article',
                'url' => $seo['canonical'], 'mainEntityOfPage' => $seo['canonical'],
                'headline' => self::text($news->title), 'description' => $seo['description'],
                'publisher' => ['@id' => $organizationId], 'inLanguage' => 'vi',
            ];
            if ($news->created_at) {
                $node['datePublished'] = $news->created_at->toAtomString();
            }
            if ($news->updated_at) {
                $node['dateModified'] = $news->updated_at->toAtomString();
            }
            if ($news->banner) {
                $node['image'] = [asset('storage/'.$news->banner)];
            }
            $graph[] = $node;
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    private static function breadcrumbs(Request $request, array $data, array $seo): array
    {
        $items = [['Trang chủ', route('home')]];
        if ($request->routeIs('home')) {
            return $items;
        }
        if ($request->routeIs('product.category', 'product.show')) {
            $items[] = ['Sản phẩm', route('product.index')];
            $product = $data['product'] ?? null;
            $category = $data['category'] ?? $product?->category;
            $sub = $data['subCategory'] ?? $product?->subCategory;
            if ($category) {
                $items[] = [$category->name, route('product.category', ['category' => $category])];
            }
            if ($category && $sub) {
                $items[] = [$sub->name, route('product.category', ['category' => $category, 'subCategory' => $sub])];
            }
            if ($product) {
                $items[] = [$product->name, $seo['canonical']];
            }
        } elseif ($request->routeIs('news.show') && isset($data['news'])) {
            $items[] = ['Tin tức', route('news.index')];
            $items[] = [$data['news']->title, $seo['canonical']];
        } elseif ($request->routeIs('service.show') && isset($data['service'])) {
            $items[] = ['Dịch vụ', route('service.index')];
            $items[] = [$data['service']->title, $seo['canonical']];
        } elseif (isset(Seo::STATIC_PAGES[$request->route()?->getName()])) {
            $labels = ['product.index' => 'Sản phẩm', 'news.index' => 'Tin tức', 'service.index' => 'Dịch vụ'];
            $items[] = [$labels[$request->route()->getName()] ?? Seo::STATIC_PAGES[$request->route()->getName()], $seo['canonical']];
        }
        // The last breadcrumb uses the same canonical, including pagination.
        if (count($items) > 1) {
            $items[array_key_last($items)][1] = $seo['canonical'];
        }

        return $items;
    }
}
