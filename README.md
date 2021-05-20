# Laravel Automount

Laravel 的依賴注入很棒，但我們讓他更好！

## 關於

想想看，當你專案越來越大，一定有遇過又臭又長的建構子：

```php
class ProductController extends Controller
{
    protected ProductService $productService;

    protected ProductRepository $productRepository;

    protected ProductStockManager $productStockManager;

    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        ProductStockManager $productStockManager
    ) {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->productStockManager = $productStockManager;
    }

    public function __invoke(Request $request)
    {
        $this->productService->doSomething();
    }
}
```

透過 `AutoMountDependencies` 特性自動掛載依賴，讓我們省略繁雜的綁定過程。

```php
class ProductController extends Controller
{
    use AutoMountDependencies; // Add this.

    protected ProductService $productService;

    protected ProductRepository $productRepository;

    protected ProductStockManager $productStockManager;

    // We don't need the constructor anymore...

    public function __invoke(Request $request)
    {
        $this->productService->doSomething(); // Still works!
    }
}
```

## 安裝

此套件基於 PHP 7.4 的 `typed properties` 特性，請確保你的 PHP 更新到最新版本。

```bash
composer require a2workspace/laravel-automount
```