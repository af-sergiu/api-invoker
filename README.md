# api-invoker
Каркас для организации работы со сложными api. 

## Использование
### Разработка
Запуск контейнера `docker-compose up --build -d`
Остановка контейнера `docker-compose down`
Зайти в контейнер `docker exec -it ${PROJECT_NAME}-php-cli bash`
### Определение работы с api
В определении порядка работы с api участвуют 2, в редком случае 4 типажа. Это:
* `AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder` служит для конструирования класса запроса характерного для всех методов api. Он устанавливает токены, порядок сериализации массивов параметров в json, xml и тп, обязательные заголовки запросов.    Для создания нового строителя можно наследовать базовый абстрактный класс `AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder`;
    
* `AfSergiu\ApiInvoker\Contracts\Factories\Http\IMethodFactory` фабрику для методов api, которая устанавливает необходимые зависимости для методов данного api: строитель запросов, инвокер запросов и т.п. Для создания фабрики можно наследовать базовую абстрактную фабрику `AfSergiu\ApiInvoker\Factories\Http\MethodFactory`, которая реализовывает поставку всех зависимостей, относящихся к общей работе пакета, кроме строителя запроса и инвокера запроса. В пакете реализован готовый инвокер на базе Guzzle, который можно использовать. Для ее создания необходимо воспользоваться фабрикой `AfSergiu\ApiInvoker\Factories\Http\Invokers\GuzzleInvokerFactory`. В конструктор `AfSergiu\ApiInvoker\Factories\Http\MethodFactory::__construct(ContainerInterface $container)` необходимо передать контейнер для поставки миддлваров; 

* При необходимости можно переопределить дефолтный инвокер запросов - типаж `AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker`. Это может потребоваться для реализации иной стратегии отправки http запроса к api, например, с использованием ssl сертификата. Для создания инвокера следует наследовать базовый абстрактный класс `AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker`. Дефолтный инвокер использует библиотеку Guzzle, если вы будете использовать другой http клиент, то для него необходимо определить свой адаптер исключений.
### Определение методов api
 Каждый метод api определяется в классе, наследующем `AfSergiu\ApiInvoker\Http\Methods\BaseMethod`, где необходимо реализовать следующие свойства:
 * `$httpMethod` - http метода запроса
 * `$uri` - uri запроса
 * `$addHeaders` - массив заголовков, которые будут замерджины к заголовкам, определенным в билдере запроса данного api, если в api принято в заголовках передавать какие то параметры  
 * `$beforeMiddleware` массив мидлваров, которые будут вызваны перед выполнением запроса
 * `$afterMiddleware` массив мидлваров, которые будут вызваны после выполнения запроса
 ### Определение мидлваров
 Перед и после выполнения запроса вызываются миддлвары. Они указываются в массивах `AfSergiu\ApiInvoker\Http\Methods\BaseMethod`. Мидлвары могут определены в виде функции или строки с наименованием класса, реализующим интерфейсы `AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddleware` и `AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddleware`.
 

