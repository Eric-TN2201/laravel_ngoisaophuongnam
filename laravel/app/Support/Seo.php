<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Seo
{
    public const STATIC_PAGES = [
        'home' => 'Trang chủ', 'about' => 'Giới thiệu', 'contact' => 'Liên hệ',
        'product.index' => 'Sản phẩm', 'news.index' => 'Tin tức', 'service.index' => 'Dịch vụ',
    ];

    public const CONTENT_PAGES = ['product.show', 'product.category', 'news.show', 'service.show'];

    /** Resolve unsaved form values through the same rules as a public page. */
    public static function preview(string $page, array $values): array
    {
        $request = Request::create(route('home'));
        $route = new \Illuminate\Routing\Route('GET', '/', fn () => null);
        $route->name($page);
        $request->setRouteResolver(fn () => $route);
        $name = (string) ($values['name'] ?? '');
        $record = (object) [
            'name' => $name, 'title' => $name,
            'description' => $values['description'] ?? '',
            'meta_title' => $values['meta_title'] ?? '',
            'meta_description' => $values['meta_description'] ?? '',
            'thumbnail' => null, 'banner' => null, 'image' => null,
        ];
        $data = match ($page) {
            'product.show' => ['product' => $record],
            'product.category' => ['category' => $record],
            'news.show' => ['news' => $record],
            'service.show' => ['service' => $record],
            default => [],
        };

        return self::forPage($request, $data, self::STATIC_PAGES[$page] ?? $name,
            setting('company_name') ?: 'Sao Phương Nam', $values);
    }

    public static function forPage(Request $request, array $data, string $pageTitle, string $company, ?array $overrides = null): array
    {
        $clean = static fn ($value) => Str::squish(strip_tags(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $company = $clean($company) ?: 'Sao Phương Nam';
        $contentTitle = match ($request->route()?->getName()) {
            'product.show' => $data['product']->name ?? $pageTitle,
            'news.show' => $data['news']->title ?? $pageTitle,
            'service.show' => $data['service']->title ?? $pageTitle,
            'product.category' => $data['subCategory']->name ?? $data['category']->name ?? $pageTitle,
            default => $pageTitle,
        };
        $title = $clean($contentTitle) ?: $company;
        $description = match ($request->route()?->getName()) {
            'home' => "Khám phá vật tư ngành in và giải pháp in ấn tại {$company}.",
            'about' => "Tìm hiểu về {$company}, thông tin công ty và các giải pháp vật tư ngành in và in ấn.",
            'contact' => "Liên hệ {$company} để được tư vấn về vật tư ngành in và giải pháp in ấn.",
            'product.index' => "Khám phá danh mục vật tư ngành in tại {$company}. Xem thông tin sản phẩm và liên hệ tư vấn.",
            'product.category' => "Khám phá sản phẩm {$title} tại {$company}. Xem thông tin chi tiết và liên hệ để được tư vấn phù hợp.",
            'news.index' => "Cập nhật tin tức, hoạt động và sự kiện mới nhất từ {$company}.",
            'service.index' => "Tìm hiểu các dịch vụ và giải pháp in ấn tại {$company}.",
            default => "Tìm hiểu {$title} tại {$company}.",
        };
        $image = asset('images/common/logo.png');
        $type = 'website';

        if ($request->routeIs('product.show') && isset($data['product'])) {
            $product = $data['product'];
            $title = $clean($product->meta_title) ?: $clean($product->name);
            $description = $clean($product->meta_description) ?: ($clean($product->description) ?: $description);
            $image = $product->thumbnail ? asset('storage/'.$product->thumbnail) : $image;
        } elseif ($request->routeIs('news.show') && isset($data['news'])) {
            $description = $clean($data['news']->description) ?: $description;
            $image = $data['news']->banner ? asset('storage/'.$data['news']->banner) : $image;
            $type = 'article';
        } elseif ($request->routeIs('service.show') && isset($data['service'])) {
            $description = $clean($data['service']->description) ?: $description;
            $image = $data['service']->image ? asset('storage/'.$data['service']->image) : $image;
        }

        $record = match ($request->route()?->getName()) {
            'product.show' => $data['product'] ?? null,
            'product.category' => $data['subCategory'] ?? $data['category'] ?? null,
            'news.show' => $data['news'] ?? null,
            'service.show' => $data['service'] ?? null,
            default => null,
        };
        if ($record) {
            $title = $clean($record->meta_title) ?: $title;
            $description = $clean($record->meta_description) ?: $description;
        } elseif (isset(self::STATIC_PAGES[$request->route()?->getName()])) {
            $prefix = 'seo_'.str_replace('.', '_', $request->route()->getName());
            $title = $clean($overrides !== null ? ($overrides['meta_title'] ?? '') : setting($prefix.'_title')) ?: $title;
            $description = $clean($overrides !== null ? ($overrides['meta_description'] ?? '') : setting($prefix.'_description')) ?: $description;
        }

        if ($company !== '' && !Str::contains(mb_strtolower($title), mb_strtolower($company))) {
            $title .= ' | '.$company;
        }

        $canonical = $request->url();
        if ($overrides === null) {
            $canonical = match ($request->route()?->getName()) {
                'product.show' => isset($data['product']) ? route('product.show', $data['product']) : $canonical,
                'product.category' => isset($data['category']) ? route('product.category', [
                    'category' => $data['category'], 'subCategory' => $data['subCategory'] ?? null,
                ]) : $canonical,
                'news.show' => isset($data['news']) ? route('news.show', $data['news']) : $canonical,
                'service.show' => isset($data['service']) ? route('service.show', $data['service']) : $canonical,
                default => isset(self::STATIC_PAGES[$request->route()?->getName()])
                    ? route($request->route()->getName()) : $canonical,
            };
        }
        if ($request->routeIs('product.index', 'product.category', 'news.index')) {
            $page = filter_var($request->query('page'), FILTER_VALIDATE_INT);
            if ($page && $page > 1) {
                $canonical .= '?page='.$page;
                $title .= ' - Trang '.$page;
            }
        }

        return [
            'title' => $title,
            'description' => Str::limit($clean($description), 160, '…'),
            'canonical' => $canonical,
            'image' => $image,
            'type' => $type,
            'site_name' => $company,
        ];
    }
}
