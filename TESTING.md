# SubAlert 系统测试清单

## 功能测试

### 用户认证
- [ ] 用户注册功能
- [ ] 用户登录功能
- [ ] 用户退出功能
- [ ] 密码重置功能
- [ ] 个人资料编辑

### 订阅管理
- [ ] 添加订阅服务
- [ ] 编辑订阅信息
- [ ] 删除订阅服务
- [ ] 订阅列表筛选
- [ ] 订阅搜索功能
- [ ] 订阅续费功能

### 提醒功能
- [ ] 创建提醒设置
- [ ] 编辑提醒设置
- [ ] 删除提醒设置
- [ ] 邮件提醒发送
- [ ] 飞书提醒发送
- [ ] 企业微信提醒发送
- [ ] 提醒历史记录

### 统计分析
- [ ] 仪表板数据显示
- [ ] 月度支出统计
- [ ] 订阅类型分析
- [ ] 即将到期订阅
- [ ] 提醒统计数据

### 管理后台
- [ ] 管理员登录
- [ ] 用户管理功能
- [ ] 系统设置
- [ ] 数据统计查看

## API 测试

### 认证接口
- [ ] POST /api/register
- [ ] POST /api/login
- [ ] POST /api/logout
- [ ] POST /api/refresh

### 订阅接口
- [ ] GET /api/subscriptions
- [ ] POST /api/subscriptions
- [ ] GET /api/subscriptions/{id}
- [ ] PUT /api/subscriptions/{id}
- [ ] DELETE /api/subscriptions/{id}
- [ ] POST /api/subscriptions/{id}/renew

### 提醒接口
- [ ] GET /api/reminders
- [ ] POST /api/reminders
- [ ] PUT /api/reminders/{id}
- [ ] DELETE /api/reminders/{id}
- [ ] POST /api/reminders/{id}/read

### 统计接口
- [ ] GET /api/statistics/dashboard
- [ ] GET /api/statistics/subscriptions
- [ ] GET /api/statistics/reminders
- [ ] GET /api/statistics/monthly-expenses
- [ ] GET /api/statistics/expiring

## 系统测试

### 数据库
- [ ] MySQL 连接测试
- [ ] 数据库迁移执行
- [ ] 数据表创建验证
- [ ] 外键约束测试

### 定时任务
- [ ] 提醒发送任务
- [ ] 数据清理任务
- [ ] Laravel 调度器

### 邮件服务
- [ ] SMTP 配置测试
- [ ] 邮件模板渲染
- [ ] 邮件发送功能

### 第三方服务
- [ ] 飞书 Webhook 测试
- [ ] 企业微信 Webhook 测试

## 性能测试

### 响应时间
- [ ] 页面加载时间 < 2秒
- [ ] API 响应时间 < 500ms
- [ ] 数据库查询优化

### 并发测试
- [ ] 50 用户并发访问
- [ ] 数据库连接池测试
- [ ] 缓存机制验证

## 安全测试

### 认证安全
- [ ] JWT Token 验证
- [ ] 密码加密验证
- [ ] 会话管理测试

### 数据安全
- [ ] SQL 注入防护
- [ ] XSS 攻击防护
- [ ] CSRF 保护测试

### 权限控制
- [ ] 用户权限验证
- [ ] 管理员权限验证
- [ ] API 权限控制

## 部署测试

### Docker 部署
- [ ] Docker 镜像构建
- [ ] Docker Compose 启动
- [ ] 容器网络通信
- [ ] 数据持久化

### 手动部署
- [ ] 环境依赖安装
- [ ] 应用配置设置
- [ ] Web 服务器配置
- [ ] 定时任务设置

### 生产环境
- [ ] HTTPS 配置
- [ ] 域名绑定
- [ ] 性能优化
- [ ] 监控告警

## 测试命令

```bash
# 运行所有测试
php artisan test

# 检查代码风格
./vendor/bin/pint

# 测试邮件配置
php artisan tinker
Mail::raw('测试邮件', function($msg) { $msg->to('test@example.com')->subject('测试'); });

# 测试定时任务
php artisan reminders:send
php artisan reminders:cleanup

# 测试数据库连接
php artisan migrate:status

# 检查路由
php artisan route:list

# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 问题记录

记录在测试过程中发现的问题：

1. **问题描述**: 
   - 解决方案: 
   - 状态: [ ] 待解决 / [ ] 已解决

2. **问题描述**: 
   - 解决方案: 
   - 状态: [ ] 待解决 / [ ] 已解决

## 测试结论

- [ ] 所有核心功能正常
- [ ] API 接口测试通过
- [ ] 性能指标达标
- [ ] 安全测试通过
- [ ] 部署流程验证

**测试完成日期**: ____________
**测试人员**: ____________
**版本号**: ____________