<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SeoTest extends TestCase
{
    public function createApplication(): Application
    {
        // Keep compiled views and logs outside the project's storage directory.
        $app = require __DIR__.'/../../bootstrap/app.php';
        $temporaryStorage = sys_get_temp_dir().'/spn-seo-tests-'.bin2hex(random_bytes(8));
        foreach (['framework/views', 'framework/cache', 'framework/sessions', 'logs', 'bootstrap/cache'] as $directory) {
            mkdir($temporaryStorage.'/'.$directory, 0777, true);
        }
        $app->useStoragePath($temporaryStorage);
        $app->useBootstrapPath($temporaryStorage.'/bootstrap');
        $app->useEnvironmentPath($temporaryStorage);
        $app->afterBootstrapping(LoadConfiguration::class, function ($app) use ($temporaryStorage) {
            // Apply isolation before any application provider can open a connection.
            $app['config']->set([
                'app.env' => 'testing', 'app.debug' => false,
                'app.key' => 'base64:'.base64_encode(random_bytes(32)),
                'database.default' => 'seo_test',
                'database.connections' => ['seo_test' => [
                    'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
                    'foreign_key_constraints' => true,
                ]],
                'cache.default' => 'array', 'session.driver' => 'array',
                'mail.default' => 'array', 'logging.default' => 'null',
                'view.compiled' => $temporaryStorage.'/framework/views',
            ]);
            $app->detectEnvironment(fn () => 'testing');
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('SEO tests require disposable SQLite in memory; no other database is permitted.');
        }
        parent::setUp();
        $this->assertSame('seo_test', config('database.default'));
        $this->assertSame(['seo_test'], array_keys(config('database.connections')));
        $connection = DB::connection('seo_test');
        $this->assertSame(':memory:', $connection->getConfig('database'));
        $this->assertSame('sqlite', $connection->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $this->assertSame('', $connection->selectOne('PRAGMA database_list')->file);
        // Only this verified in-memory connection receives schema changes.
        foreach (glob(database_path('migrations/*.php')) as $path) {
            if (str_ends_with($path, '2026_04_12_100000_change_stories_content_to_longtext.php')) {
                continue; // SQLite text is already unlimited; MySQL alteration needs DBAL.
            }
            (require $path)->up();
        }
        Setting::set('company_name', 'Sao Phương Nam');
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        if ($this->app) {
            DB::disconnect('seo_test');
        }
        parent::tearDown();
    }

    private function product(array $attributes = []): Product
    {
        $category = Category::create(['name' => 'Vật tư in', 'slug' => 'vat-tu-in', 'status' => 1]);

        return Product::create(array_merge([
            'category_id' => $category->id, 'name' => 'Giấy in', 'slug' => 'giay-in',
            'description' => '<p>Vật tư &amp; giấy in.</p>', 'status' => 1,
        ], $attributes));
    }

    private function editor(): User
    {
        $user = $this->user();
        $this->actingAs($user);

        return $user;
    }

    private function user(): User
    {
        return User::create([
            'name' => 'SEO editor', 'email' => bin2hex(random_bytes(8)).'@example.test',
            'password' => bcrypt('test-password'), 'role' => 'admin',
        ]);
    }

    private function graph(string $html): array
    {
        preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $match);
        $this->assertNotEmpty($match);

        return json_decode($match[1], true, 512, JSON_THROW_ON_ERROR)['@graph'];
    }

    public function test_metadata_is_escaped_unique_and_does_not_duplicate_company(): void
    {
        $product = $this->product(['meta_title' => 'Giấy "A" | Sao Phương Nam', 'meta_description' => '<p>Giấy &amp; vật tư.</p>', 'thumbnail' => 'products/a.jpg']);
        $url = route('product.show', $product);
        $response = $this->get($url.'?utm_source=test')->assertOk()
            ->assertSee('<title>Giấy &quot;A&quot; | Sao Phương Nam</title>', false)
            ->assertSee('name="description" content="Giấy &amp; vật tư."', false)
            ->assertSee('rel="canonical" href="'.$url.'"', false)
            ->assertSee('property="og:image" content="'.asset('storage/products/a.jpg').'"', false)
            ->assertSee('name="twitter:card"', false);
        $this->assertSame(1, substr_count($response->getContent(), '<title>'));
        $this->assertSame(1, substr_count($response->getContent(), 'rel="canonical"'));
        $this->assertSame('Giấy "A" | Sao Phương Nam', $product->fresh()->meta_title);
    }

    public function test_blank_metadata_falls_back_and_pagination_keeps_only_page(): void
    {
        $product = $this->product(['meta_title' => ' ', 'meta_description' => '']);
        $this->get(route('product.show', $product))->assertOk()
            ->assertSee('<title>Giấy in | Sao Phương Nam</title>', false)
            ->assertSee('content="Vật tư &amp; giấy in."', false);
        $this->get('/san-pham?page=2&utm_source=test')->assertOk()
            ->assertSee('rel="canonical" href="'.url('/san-pham').'?page=2"', false);
        Setting::set('company_name', ' ');
        $this->get('/')->assertOk()->assertSee('property="og:site_name" content="Sao Phương Nam"', false);
    }

    public function test_static_seo_settings_are_protected_validated_and_separate(): void
    {
        $this->get('/spn/admin/seo')->assertRedirect(route('admin.login'));
        $this->put('/spn/admin/seo', ['seo_home_title' => 'Unauthorized'])->assertRedirect(route('admin.login'));
        $this->editor();
        Setting::set('company_phone', '0900123456');
        $this->get('/spn/admin/seo')->assertOk()->assertSee('name="seo_service_index_title"', false);
        $this->put('/spn/admin/seo', ['seo_home_title' => 'Vật tư ngành in', 'company_phone' => 'changed'])
            ->assertSessionHasNoErrors()->assertRedirect(route('admin.seo.edit'));
        $this->assertSame('0900123456', Setting::get('company_phone'));
        $this->get('/')->assertOk()->assertSee('<title>Vật tư ngành in | Sao Phương Nam</title>', false);
        $this->put('/spn/admin/seo', ['seo_home_title' => str_repeat('x', 256)])
            ->assertSessionHasErrors('seo_home_title');
        $this->assertSame('Vật tư ngành in', Setting::get('seo_home_title'));
    }

    public function test_authenticated_non_admin_cannot_manage_or_preview_seo(): void
    {
        $user = $this->user();
        $user->update(['role' => 'user']);
        $this->actingAs($user);
        $this->get('/spn/admin/seo')->assertForbidden();
        $this->put('/spn/admin/seo', ['seo_home_title' => 'Denied'])->assertForbidden();
        $this->postJson('/spn/admin/seo/preview', ['page_route' => 'home'])->assertForbidden();
        $this->get(route('admin.product.create'))->assertForbidden();
        $this->assertNull(Setting::get('seo_home_title'));
    }

    public function test_existing_admin_login_and_logout_flow_remains_compatible(): void
    {
        $user = $this->user();
        $this->get(route('admin.login'))->assertOk();
        $this->post(route('admin.login'), [
            'email' => $user->email, 'password' => 'test-password',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.home'))->assertOk();
        $this->get(route('admin.seo.edit'))->assertOk();
        $this->get(route('admin.login'))->assertRedirect(route('admin.home'));
        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
        $this->get(route('admin.seo.edit'))->assertRedirect(route('admin.login'));
    }

    public function test_real_non_admin_login_does_not_grant_admin_access(): void
    {
        $user = $this->user();
        $user->update(['role' => 'user']);
        $this->post(route('admin.login'), [
            'email' => $user->email, 'password' => 'test-password',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.home'))->assertForbidden();
        $this->get(route('admin.seo.edit'))->assertForbidden();
        $this->put(route('admin.seo.update'), ['seo_home_title' => 'Denied'])->assertForbidden();
        $this->assertNull(Setting::get('seo_home_title'));
    }

    public function test_preview_matches_public_metadata_for_fallbacks_and_unsaved_values(): void
    {
        $product = $this->product(['meta_title' => 'Giấy | Sao Phương Nam', 'meta_description' => '']);
        $this->editor();
        $preview = $this->postJson('/spn/admin/seo/preview', [
            'page_route' => 'product.show', 'name' => $product->name,
            'description' => $product->description, 'meta_title' => $product->meta_title,
            'meta_description' => '',
        ])->assertOk()->json();
        $this->get(route('product.show', $product))->assertOk()
            ->assertSee('<title>'.e($preview['title']).'</title>', false)
            ->assertSee('name="description" content="'.e($preview['description']).'"', false);
        Setting::set('seo_home_title', 'Saved home title');
        $preview = $this->postJson('/spn/admin/seo/preview', [
            'page_route' => 'home', 'meta_title' => '', 'meta_description' => '',
        ])->assertOk()->json();
        $this->assertSame('Trang chủ | Sao Phương Nam', $preview['title']);
        $this->assertNotSame('', $preview['description']);
        $this->assertSame('Saved home title', Setting::get('seo_home_title'));
        $this->postJson('/spn/admin/seo/preview', ['page_route' => 'admin.home'])->assertUnprocessable();
    }

    public function test_admin_content_seo_fields_save_without_changing_product_metadata_or_urls(): void
    {
        $product = $this->product(['meta_title' => 'Saved title', 'meta_description' => 'Saved description']);
        $this->editor();
        $meta = ['meta_title' => 'Tiêu đề in ấn', 'meta_description' => 'Mô tả in ấn'];
        $category = $product->category;
        $this->put(route('admin.category.update', $category), ['name' => $category->name, 'status' => 1] + $meta)->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame($meta['meta_title'], $category->fresh()->meta_title);
        $this->post(route('admin.sub-category.store'), ['name' => 'Giấy', 'category_id' => $category->id, 'status' => 1] + $meta)->assertSessionHasNoErrors()->assertSessionHas('success');
        $sub = SubCategory::firstOrFail();
        $this->assertSame($meta['meta_description'], $sub->meta_description);
        foreach (['news', 'service'] as $type) {
            $this->post(route('admin.'.$type.'.store'), ['title' => 'In ấn', 'status' => 1] + $meta)->assertSessionHasNoErrors()->assertSessionHas('success');
            $record = $type === 'news' ? News::firstOrFail() : Service::firstOrFail();
            $this->assertSame($meta['meta_title'], $record->meta_title);
            $this->assertStringEndsWith('/'.$record->id, route($type.'.show', $record));
            $this->get(route($type.'.show', $record))->assertOk()->assertSee('<title>Tiêu đề in ấn | Sao Phương Nam</title>', false);
        }
        $this->put(route('admin.product.update', $product), [
            'name' => $product->name, 'category_id' => $category->id, 'status' => 1,
        ])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame('Saved title', $product->fresh()->meta_title);
        $this->assertSame('Saved description', $product->fresh()->meta_description);
        $this->assertSame('giay-in', $product->fresh()->slug);
        foreach (['product', 'category', 'sub-category', 'news', 'service'] as $type) {
            $this->get(route('admin.'.$type.'.create'))->assertOk()->assertSee('data-seo-editor', false)->assertSee('name="meta_description"', false);
        }
    }

    public function test_inactive_content_and_mismatched_categories_are_not_public(): void
    {
        $product = $this->product();
        $category = $product->category;
        $sub = SubCategory::create(['category_id' => $category->id, 'name' => 'Hidden sub', 'slug' => 'hidden-sub', 'status' => 0]);
        $product->update(['sub_category_id' => $sub->id]);
        $this->get(route('product.show', $product))->assertNotFound();
        $this->get(route('product.category', ['category' => $category, 'subCategory' => $sub]))->assertNotFound();
        $sub->update(['status' => 1]);
        $other = Category::create(['name' => 'Other', 'slug' => 'other', 'status' => 1]);
        $this->get(route('product.category', ['category' => $other, 'subCategory' => $sub]))->assertNotFound();
        $product->update(['category_id' => $other->id]);
        $this->get(route('product.show', $product))->assertNotFound();
        $product->update(['category_id' => $category->id]);
        $category->update(['status' => 0]);
        $this->get(route('product.show', $product))->assertNotFound();
        $this->get(route('product.category', $category))->assertNotFound();
        $this->get('/')->assertOk()->assertDontSee('Giấy in');
        $this->get('/san-pham')->assertOk()->assertDontSee('Giấy in');
        $user = $this->user();
        $news = News::create(['title' => 'Hidden news', 'status' => 0, 'created_by' => $user->id]);
        $service = Service::create(['title' => 'Hidden service', 'status' => 0]);
        foreach ([route('news.show', $news), route('service.show', $service), '/missing'] as $url) {
            $this->get($url)->assertNotFound()->assertSee('name="robots" content="noindex, follow"', false)
                ->assertDontSee('rel="canonical"', false)->assertDontSee('application/ld+json', false);
        }
    }

    public function test_sitemap_and_robots_only_list_eligible_current_public_urls(): void
    {
        $product = $this->product();
        $hidden = Product::create(['category_id' => $product->category_id, 'name' => 'Hidden', 'slug' => 'hidden', 'status' => 0]);
        $response = $this->get('/sitemap.xml?utm_source=test')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $urls = array_map(fn ($node) => (string) $node->loc, iterator_to_array($xml->url, false));
        $this->assertContains(route('product.show', $product), $urls);
        $this->assertNotContains(route('product.show', $hidden), $urls);
        foreach ($urls as $url) {
            $this->get($url)->assertOk()->assertSee('rel="canonical" href="'.$url.'"', false);
        }
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /spn/admin', false)
            ->assertSee('Sitemap: '.route('sitemap'), false)->assertDontSee('/tts/admin');
        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    public function test_sitemap_excludes_hidden_parents_and_unpublished_news_and_services(): void
    {
        $product = $this->product();
        $category = $product->category;
        $sub = SubCategory::create(['category_id' => $category->id, 'name' => 'Sub', 'slug' => 'sub', 'status' => 1]);
        $product->update(['sub_category_id' => $sub->id]);
        $news = News::create(['title' => 'Draft', 'status' => 0, 'created_by' => $this->user()->id]);
        $service = Service::create(['title' => 'Draft', 'status' => 0]);
        $category->update(['status' => 0]);
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        foreach ([route('product.show', $product), route('product.category', $category),
            route('product.category', ['category' => $category, 'subCategory' => $sub]),
            route('news.show', $news), route('service.show', $service)] as $url) {
            $this->assertStringNotContainsString('<loc>'.$url.'</loc>', $xml);
        }
        $category->update(['status' => 1]);
        $sub->update(['status' => 0]);
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('<loc>'.route('product.show', $product).'</loc>', false);
    }

    public function test_empty_sitemap_and_all_static_page_metadata_work(): void
    {
        $xml = simplexml_load_string($this->get('/sitemap.xml')->assertOk()->getContent());
        $this->assertCount(6, $xml->url);
        foreach (['/', '/gioi-thieu', '/lien-he', '/san-pham', '/tin-tuc', '/dich-vu'] as $path) {
            $response = $this->get($path)->assertOk()->assertSee('name="description"', false)
                ->assertSee('property="og:title"', false)->assertSee('name="twitter:title"', false);
            $this->assertSame(1, substr_count($response->getContent(), 'rel="canonical"'));
        }
    }

    public function test_additive_migration_rollback_preserves_products_and_other_content(): void
    {
        $product = $this->product(['meta_title' => 'Keep title', 'meta_description' => 'Keep description']);
        $migration = require database_path('migrations/2026_10_20_000000_add_seo_metadata_to_content.php');
        $migration->down();
        foreach (['categories', 'sub_categories', 'news', 'services'] as $table) {
            $this->assertFalse(Schema::hasColumn($table, 'meta_title'));
            $this->assertFalse(Schema::hasColumn($table, 'meta_description'));
        }
        $this->assertSame('Keep title', $product->fresh()->meta_title);
        $this->assertSame('Keep description', $product->fresh()->meta_description);
        $this->assertNotNull(Category::find($product->category_id));
        $migration->up();
        $this->assertTrue(Schema::hasColumn('categories', 'meta_title'));
    }

    public function test_numeric_detail_alias_uses_the_existing_canonical_url(): void
    {
        $service = Service::create(['title' => 'In ấn', 'status' => 1]);
        $this->get('/dich-vu/00'.$service->id.'?utm_source=test')->assertOk()
            ->assertSee('rel="canonical" href="'.route('service.show', $service).'"', false);
    }

    public function test_json_ld_matches_real_content_and_is_safe(): void
    {
        $product = $this->product(['meta_description' => '</script><script>alert(1)</script>', 'price' => 100]);
        Setting::setMany(['company_phone' => '0900123456', 'company_email' => 'info@example.com']);
        $html = $this->get(route('product.show', $product))->assertOk()->getContent();
        $this->assertStringNotContainsString('</script><script>alert(1)</script>', $html);
        $graph = $this->graph($html);
        $types = array_column($graph, '@type');
        $this->assertContains('Organization', $types);
        $this->assertContains('BreadcrumbList', $types);
        $this->assertContains('WebPage', $types);
        $this->assertNotContains('Product', $types);
        $this->assertArrayNotHasKey('email', $graph[0]);
        $this->assertSame('0900123456', $graph[0]['telephone']);
        $encoded = json_encode($graph);
        foreach (['offers', 'aggregateRating', 'review'] as $invented) {
            $this->assertStringNotContainsString('"'.$invented.'"', $encoded);
        }
        $user = $this->user();
        $news = News::create(['title' => 'Tin ngành in', 'status' => 1, 'created_by' => $user->id]);
        $graph = $this->graph($this->get(route('news.show', $news))->assertOk()->getContent());
        $article = collect($graph)->firstWhere('@type', 'Article');
        $this->assertSame($news->title, $article['headline']);
        $this->assertSame($news->created_at->toAtomString(), $article['datePublished']);
        $this->assertArrayNotHasKey('author', $article);
    }
}
