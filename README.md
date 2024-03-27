<h1 align="center"> laravel-response </h1>

> 为 Laravel API 项目提供一个规范统一的响应数据格式。

## 介绍

`laravel-response` 主要用来统一 API 开发过程中「成功」、「失败」以及「异常」情况下的响应数据格式。

实现过程简单，在原有的 `\Illuminate\Http\JsonResponse`进行封装，使用时不需要有额外的心理负担。

遵循一定的规范，返回易于理解的 HTTP 状态码，并支持定义 `ResponseEnum` 来满足不同场景下返回描述性的业务操作码。

## 概览

- 统一的数据响应格式，固定包含：`code`、`status`、`data`、`message`、`error` (响应格式设计源于：[RESTful服务最佳实践](https://www.cnblogs.com/jaxu/p/7908111.html#a_8_2) )
- 你可以继续链式调用 `JsonResponse` 类中的所有 public 方法，比如 `Response::success()->header('X-foo','bar');`
- 合理地返回 Http 状态码，默认为 restful 严格模式，可以配置异常时返回 200 http 状态码（多数项目会这样使用）
- 支持格式化 Laravel 的 `Api Resource`、`Api  Resource Collection`、`Paginator`（简单分页）、`LengthAwarePaginator`（普通分页）、`Eloquent\Model`、`Eloquent\Collection`，以及简单的 `array` 和 `string`等格式数据返回
- 根据 debug 开关，合理返回异常信息、验证异常信息等
- 支持修改 Laravel 特地异常的状态码或提示信息，比如将 `No query results for model` 的异常提示修改成 `数据未找到`
- 支持配置返回字段是否显示，以及为她们设置别名，比如，将 `message` 别名设置为 `msg`，或者 分页数据第二层的 `data` 改成 `list`(res.data.data -> res.data.list)
- 分页数据格式化后的结果与使用 `league/fractal` （DingoApi 使用该扩展进行数据转换）的 transformer 转换后的格式保持一致，也就是说，可以顺滑地从 Laravel Api Resource 切换到 `league/fractal`

## 配置

- 发布配置文件

```shell
$ php artisan vendor:publish --provider="Three\LaravelResponse\ServiceProvider"
```

- 格式化异常响应（laravel 11 可省略这一步）


```php
// app/Exceptions/Handler.php
// 引入以后对于 API 请求产生的异常都会进行格式化数据返回
// 要求请求头 header 中包含 /json 或 +json，如：Accept:application/json
// 或者是 ajax 请求，header 中包含 X-Requested-With：XMLHttpRequest;

<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Three\LaravelResponse\Support\Traits\ExceptionTrait;

class Handler extends ExceptionHandler
{
    use ExceptionTrait;
    // ...
}
```

### 成功响应

#### 示例代码

```php
<?php
  
public function index()
{
    $users = User::all();

    return Response::success(new UserCollection($users));
}

public function paginate()
{
    $users = User::paginate(5);

    return Response::success(new UserCollection($users));
}

public function simplePaginate()
{
    $users = User::simplePaginate(5);

    return Response::success(new UserCollection($users));
}

public function item()
{
    $user = User::first();

    return Response::success(new UserResource($user));
}

public function array()
{
    return Response::success([
        'name' => 'Jiannel',
        'email' => 'longjian.huang@foxmail.com'
    ],'', ResponseEnum::SERVICE_REGISTER_SUCCESS);
}
```

#### 返回全部数据

支持自定义内层 data 字段名称，比如 rows、list

```json
{
    "status": "success",
    "code": 200,
    "message": "操作成功",
    "data": {
        "data": [
            {
                "nickname": "Joaquin Ondricka",
                "email": "lowe.chaim@example.org"
            },
            {
                "nickname": "Jermain D'Amore",
                "email": "reanna.marks@example.com"
            },
            {
                "nickname": "Erich Moore",
                "email": "ernestine.koch@example.org"
            }
        ]
    },
    "error": {}
}
```

#### 分页数据

支持自定义内层 data 字段名称，比如 rows、list

```json
{
    "status": "success",
    "code": 200,
    "message": "操作成功",
    "data": {
        "data": [
            {
                "nickname": "Joaquin Ondricka",
                "email": "lowe.chaim@example.org"
            },
            {
                "nickname": "Jermain D'Amore",
                "email": "reanna.marks@example.com"
            },
            {
                "nickname": "Erich Moore",
                "email": "ernestine.koch@example.org"
            },
            {
                "nickname": "Eva Quitzon",
                "email": "rgottlieb@example.net"
            },
            {
                "nickname": "Miss Gail Mitchell",
                "email": "kassandra.lueilwitz@example.net"
            }
        ],
        "meta": {
            "pagination": {
                "count": 5,
                "per_page": 5,
                "current_page": 1,
                "total": 12,
                "total_pages": 3,
                "links": {
                    "previous": null,
                    "next": "http://laravel-api.test/api/users/paginate?page=2"
                }
            }
        }
    },
    "error": {}
}
```

#### 返回简单分页数据

支持自定义内层 data 字段名称，比如 rows、list

```json
{
    "status": "success",
    "code": 200,
    "message": "操作成功",
    "data": {
        "data": [
            {
                "nickname": "Joaquin Ondricka",
                "email": "lowe.chaim@example.org"
            },
            {
                "nickname": "Jermain D'Amore",
                "email": "reanna.marks@example.com"
            },
            {
                "nickname": "Erich Moore",
                "email": "ernestine.koch@example.org"
            },
            {
                "nickname": "Eva Quitzon",
                "email": "rgottlieb@example.net"
            },
            {
                "nickname": "Miss Gail Mitchell",
                "email": "kassandra.lueilwitz@example.net"
            }
        ],
        "meta": {
            "pagination": {
                "count": 5,
                "per_page": 5,
                "current_page": 1,
                "links": {
                    "previous": null,
                    "next": "http://laravel-api.test/api/users/simple-paginate?page=2"
                }
            }
        }
    },
    "error": {}
}
```

#### 返回单条数据

```json
{
    "status": "success",
    "code": 200,
    "message": "操作成功",
    "data": {
        "nickname": "Joaquin Ondricka",
        "email": "lowe.chaim@example.org"
    },
    "error": {}
}
```

#### 其他快捷方法

```php
Response::ok();// 无需返回 data，只返回 message 情形的快捷方法
Response::localize(200101);// 无需返回 data，message 根据响应码配置返回的快捷方法
Response::accepted();
Response::created();
Response::noContent();
```

### 失败响应

#### 不指定 message

```php
public function fail()
{
    return Response::fail();
}
```

- 未配置多语言响应描述

```json
{
    "status": "fail",
    "code": 500,
    "message": "Http internal server error",
    "data": {},
    "error": {}
}
```

- 配置多语言描述

```json
{
    "status": "fail",
    "code": 500,
    "message": "操作失败",
    "data": {},
    "error": {}
}
```

#### 指定 message

```php
public function fail()
{
    return Response::fail('error');
}
```

返回数据

```json
{
    "status": "fail",
    "code": 500,
    "message": "error",
    "data": {},
    "error": {}
}
```

#### 指定 code

```php
public function fail()
{
    return Response::fail('',ResponseEnum::SERVICE_LOGIN_ERROR);
}
```

返回数据

```json
{
    "status": "fail",
    "code": 500102,
    "message": "登录失败",
    "data": {},
    "error": {}
}
```

#### 其他快捷方法

```php
Response::errorBadRequest();
Response::errorUnauthorized();
Response::errorForbidden();
Response::errorNotFound();
Response::errorMethodNotAllowed();
Response::errorInternal();
```

### 异常响应

#### 表单验证异常

```json
{
    "status": "error",
    "code": 422,
    "message": "验证失败",
    "data": {},
    "error": {
        "email": [
            "The email field is required."
        ]
    }
}
```

#### Controller 以外抛出异常

可以使用 abort 辅助函数抛出 HttpException 异常

```php
abort(500102,'登录失败');

// 返回数据

{
    "status": "fail",
    "code": 500102,
    "message": "登录失败",
    "data": {},
    "error": {}
}
```

#### 其他异常

开启 debug（`APP_DEBUG=true`）

```json
{
    "status": "error",
    "code": 404,
    "message": "Http not found",
    "data": {},
    "error": {
        "message": "",
        "exception": "Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException",
        "file": "/home/Code/laravel/vendor/laravel/framework/src/Illuminate/Routing/AbstractRouteCollection.php",
        "line": 43,
        "trace": [
            {
                "file": "/home/Code/laravel/vendor/laravel/framework/src/Illuminate/Routing/RouteCollection.php",
                "line": 162,
                "function": "handleMatchedRoute",
                "class": "Illuminate\\Routing\\AbstractRouteCollection",
                "type": "->"
            },
            {
                "file": "/home/Code/laravel/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
                "line": 646,
                "function": "match",
                "class": "Illuminate\\Routing\\RouteCollection",
                "type": "->"
            }
        ]
    }
}
```

关闭 debug

```json
{
    "status": "error",
    "code": 404,
    "message": "Http not found",
    "data": {},
    "error": {}
}
```



## 协议

MIT 许可证（MIT）。有关更多信息，请参见[协议文件](LICENSE)。