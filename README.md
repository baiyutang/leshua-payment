# Leshua Payment
乐刷聚合支付 PHP SDK

非官方，但保证好用，节省调试时间，可二次开发。

欢迎开发者进行技术讨论，反馈共建。

商用时，具体逻辑请与乐刷技术支持确定。

## 特性
- [x] 参数组装
- [ ] 参数格式校验
- [x] 签名
- [x] 返回验证签名
- [ ] 日志记录
- [ ] DEBUG 模式
- [x] 开箱即用，无其他依赖
- [ ] 单元测试

## 定位
- 好用
- 易懂
- 便于改造
- 开源精神

## 编写原则
- 面向对象
- 参数说明详细清晰有意义
- 按照实际业务有参数顺序有调整，或修改用词表达
- 乐刷返回参数仅做数据格式转换，不做任何字段及类型变形或改变

## 文件目录
```
src/
  - Exceptions/ 异常类
  - Requests/
            - Activity/ 营销活动等接口，暂未实现
            - Merchant/ 商户报件及配置相关
                      - BaseMerchant.php 抽象商户类
                      - Pictrue.php 文件上传请求类
            - Transaction/ 交易相关请求
                      - BasePay.php 抽象支付请求类，把两个支付接口共有的参数设置都放在这里
                      - CloseOrder.php 关闭订单请求类
                      - GetWxPayFaceAuthInfo.php 获取微信刷脸凭证
                      - QRPay.php 条码支付
                      - QueryRefund.php 退款查询
                      - QueryStatus.php 交易结果查询
                      - QueryUPUserId.php 银联云闪付，授权码获取用户ID
                      - QueryWXOpenId.php 微信，授权码查询用户openid
                      - Refund.php 退款
                      - UnifiedOrder.php 统一订单
            - BaseRequest.php 基础请求接口
            - Utils/ 工具类相关
                  - SignUtil.php 签名工具类，签名、验证相关具体逻辑都放在这里
                  - StrUtil.php 字符工具类，随机字符、请求流水号、xml 转 array
  - Config.php 配置类，需要灵活配置的参数相关，**暂时**抽象到这里，不是理想状态
  - LeshuaClient.php SDK 客户端类，充当本 SDK 的客户端，把各请求类定义好的 URL 、参数、特殊 header 设置，组装发送出去，并返回结果

```

## 用法
```php
<?php
require "vendor/autoload.php";

try
  $qr = new QRPay();
  // 可链式调用
  // 输入 `set` IDE 会提示所有支持参数，对应请求类里 public 权限的 set 开头方法
  $qr->setMerchantId('1234567890')
    ->setThirdOrderId('2011035660004545455xxx')
    ->setNotifyUrl('http://www.you-url.com/path');
   // ...

    $ret = $client->send($qr);
} catch (BadRequestException $e) {
    die($e->getMessage());
} catch (VerifyFailureException $e) {
    die($e->getMessage());
}
```

## 开发日志
| 版本号 | 日期 | 说明 |
| --- | --- | --- |
| 0.1.0  | 20201116 | 初始化版本，支付相关接口已定义好，待调试反馈 |

