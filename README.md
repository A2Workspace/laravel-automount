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

    public function __invoke(Request $request)
    {
        $this->productService->doSomething(); // Still works!
    }
}
```

## 安裝

```bash
composer config repositories.a2workspace/laravel-automount vcs https://github.com/A2Workspace/laravel-automount.git
composer require "a2workspace/laravel-automount:*"
```