# Laravel Automount

Laravel 的依賴注入很棒，但我們讓他更好！

- [關於](##關於)
- [安裝](##安裝)
- [如何使用](##如何使用)
  - [不會被處理的屬性](##不會被處理的屬性)
- [限制](##限制)
  - [覆寫建構子](##覆寫建構子)
  - [避免私有屬性](##避免私有屬性)


## 關於

想想看，當你專案越來越大，一定有遇過又臭又長的建構子：

```php
use A2Workspace\AutoMount\AutoMountDependencies;

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
use A2Workspace\AutoMount\AutoMountDependencies;

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

此套件基於 PHP 7.4 的
[Typed Properties](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties)
新功能，請確保你的 PHP 更新到最新版本。

```bash
composer require a2workspace/laravel-automount
```

## 如何使用

僅須在目標類別上使用 `AutoMountDependencies` 特性:

```php
use A2Workspace\AutoMount\AutoMountDependencies;

class PaymentService
{
    use AutoMountDependencies;
}
```

接著，有型別定義的類別屬性就會在建構時自動做依賴注入。

```php
use A2Workspace\AutoMount\AutoMountDependencies;

class PaymentService
{
    use AutoMountDependencies;

    protected PaymentGatewayFactory $factory;
}
```

**注意**: 考慮到繼承，請避免使用 `private` 私有在要被處理的屬性上。

### 不會被處理的屬性

以下類型的屬性會被略過處理:
1. 基本型別 (int, float, bool, array ...)
1. 未定義型別的屬性
1. 定義為 [Nullable](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.nullable) 的屬性
1. 有初始值的屬性

```php
use A2Workspace\AutoMount\AutoMountDependencies;

class PaymentService
{
    use AutoMountDependencies;

    protected int $amount; // Pass.

    protected array $gateways; // Pass.

    protected $something; // Pass.

    protected ?PaymentGateway $gateway = null; // Pass.

    protected PaymentGatewayFactory $factory; // Do inject.
}
```

## 限制

### 覆寫建構子

`AutoMountDependencies` 特性中定義了在建構子中執行依賴掛載的動作。當你需要覆寫 (Override) `__construct()` 時，記得手動呼叫 `mountDependencies()` 方法。

```php
use A2Workspace\AutoMount\AutoMountDependencies;

class PaymentService
{
    use AutoMountDependencies;

    public function __construct()
    {
        $this->mountDependencies(); // Add this line.

        // ...
    }
}
```

### 避免私有屬性

當型別屬性被定義為私有，會導致繼承後的子類別，在建構時無法寫入屬性而造成錯誤。

請謹慎使用 `private` ，或將屬性定義為 [Nullable](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.nullable) 。

```php
use A2Workspace\AutoMount\AutoMountDependencies;

abstract class BasePaymentGateway
{
    use AutoMountDependencies;

    private PaymentServiceProvider $service;
}

class PaymentGateway extends BasePaymentGateway
{
    // ...
}

$gateway = new PaymentGateway; // 拋出 PaymentGateway::$service 未被初始之錯誤
```