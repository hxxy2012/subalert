# SubAlert API 文档

## 基础信息

- **Base URL**: `https://your-domain.com/api`
- **认证方式**: Bearer Token (Laravel Sanctum)
- **内容类型**: `application/json`

## 认证接口

### 用户注册
```http
POST /api/register
```

**请求参数:**
```json
{
  "nickname": "用户昵称",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "13800138000"
}
```

**响应示例:**
```json
{
  "code": 201,
  "message": "注册成功",
  "data": {
    "user": {
      "id": 1,
      "nickname": "用户昵称",
      "email": "user@example.com",
      "phone": "13800138000",
      "status": "active"
    },
    "token": "your-access-token",
    "token_type": "Bearer"
  }
}
```

### 用户登录
```http
POST /api/login
```

**请求参数:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**响应示例:**
```json
{
  "code": 200,
  "message": "登录成功",
  "data": {
    "user": {
      "id": 1,
      "nickname": "用户昵称",
      "email": "user@example.com"
    },
    "token": "your-access-token",
    "token_type": "Bearer"
  }
}
```

### 用户退出
```http
POST /api/logout
Authorization: Bearer {token}
```

**响应示例:**
```json
{
  "code": 200,
  "message": "退出成功"
}
```

### 刷新令牌
```http
POST /api/refresh
Authorization: Bearer {token}
```

## 订阅管理接口

### 获取订阅列表
```http
GET /api/subscriptions
Authorization: Bearer {token}
```

**查询参数:**
- `type`: 订阅类型 (video, music, software, communication, other)
- `status`: 订阅状态 (active, paused, cancelled, expired)
- `page`: 页码
- `per_page`: 每页数量

**响应示例:**
```json
{
  "code": 200,
  "message": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Netflix",
        "type": "video",
        "price": "49.00",
        "cycle": "monthly",
        "expire_at": "2024-08-22",
        "status": "active",
        "auto_renew": true
      }
    ],
    "total": 10
  }
}
```

### 创建订阅
```http
POST /api/subscriptions
Authorization: Bearer {token}
```

**请求参数:**
```json
{
  "name": "Netflix",
  "type": "video",
  "price": 49.00,
  "cycle": "monthly",
  "expire_at": "2024-08-22",
  "auto_renew": true,
  "note": "家庭账户",
  "account_info": "account@example.com"
}
```

### 获取订阅详情
```http
GET /api/subscriptions/{id}
Authorization: Bearer {token}
```

### 更新订阅
```http
PUT /api/subscriptions/{id}
Authorization: Bearer {token}
```

### 删除订阅
```http
DELETE /api/subscriptions/{id}
Authorization: Bearer {token}
```

### 续费订阅
```http
POST /api/subscriptions/{id}/renew
Authorization: Bearer {token}
```

## 提醒管理接口

### 获取提醒列表
```http
GET /api/reminders
Authorization: Bearer {token}
```

**查询参数:**
- `status`: 提醒状态 (pending, sent, read, failed)
- `is_active`: 是否激活 (true, false)

### 创建提醒
```http
POST /api/reminders
Authorization: Bearer {token}
```

**请求参数:**
```json
{
  "subscription_id": 1,
  "remind_type": "email,feishu",
  "remind_before_days": 7,
  "is_active": true
}
```

### 更新提醒
```http
PUT /api/reminders/{id}
Authorization: Bearer {token}
```

### 删除提醒
```http
DELETE /api/reminders/{id}
Authorization: Bearer {token}
```

### 标记为已读
```http
POST /api/reminders/{id}/read
Authorization: Bearer {token}
```

## 统计分析接口

### 获取仪表板数据
```http
GET /api/statistics/dashboard
Authorization: Bearer {token}
```

**响应示例:**
```json
{
  "code": 200,
  "message": "success",
  "data": {
    "subscriptions": {
      "total_subscriptions": 15,
      "active_subscriptions": 12,
      "expired_subscriptions": 2,
      "total_monthly_revenue": 299.00
    },
    "monthly_expense": 299.00,
    "expiring_soon": 3,
    "reminders": {
      "total_reminders": 20,
      "pending_reminders": 5,
      "sent_reminders": 15
    }
  }
}
```

### 获取订阅统计
```http
GET /api/statistics/subscriptions
Authorization: Bearer {token}
```

### 获取提醒统计
```http
GET /api/statistics/reminders
Authorization: Bearer {token}
```

### 获取月度支出
```http
GET /api/statistics/monthly-expenses
Authorization: Bearer {token}
```

**查询参数:**
- `year`: 年份 (默认当前年份)

### 获取即将到期的订阅
```http
GET /api/statistics/expiring
Authorization: Bearer {token}
```

**查询参数:**
- `days`: 天数 (默认7天)

## 错误代码

| 状态码 | 说明 |
|--------|------|
| 200 | 请求成功 |
| 201 | 创建成功 |
| 400 | 请求参数错误 |
| 401 | 未认证 |
| 403 | 无权限 |
| 404 | 资源不存在 |
| 422 | 验证失败 |
| 500 | 服务器错误 |

## 请求示例

### cURL
```bash
# 登录
curl -X POST https://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# 获取订阅列表
curl -X GET https://your-domain.com/api/subscriptions \
  -H "Authorization: Bearer your-access-token"

# 创建订阅
curl -X POST https://your-domain.com/api/subscriptions \
  -H "Authorization: Bearer your-access-token" \
  -H "Content-Type: application/json" \
  -d '{"name":"Netflix","type":"video","price":49.00,"cycle":"monthly","expire_at":"2024-08-22"}'
```

### JavaScript (Axios)
```javascript
// 设置基础配置
const api = axios.create({
  baseURL: 'https://your-domain.com/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

// 登录
const login = async (email, password) => {
  const response = await api.post('/login', { email, password });
  localStorage.setItem('token', response.data.data.token);
  return response.data;
};

// 设置认证头
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// 获取订阅列表
const getSubscriptions = async () => {
  const response = await api.get('/subscriptions');
  return response.data;
};
```

## 数据字典

### 订阅类型 (type)
- `video`: 视频
- `music`: 音乐
- `software`: 软件
- `communication`: 通讯
- `other`: 其他

### 订阅周期 (cycle)
- `monthly`: 月付
- `quarterly`: 季付
- `yearly`: 年付
- `custom`: 自定义

### 订阅状态 (status)
- `active`: 正常
- `paused`: 暂停
- `cancelled`: 已取消
- `expired`: 已过期

### 提醒类型 (remind_type)
- `email`: 邮件
- `feishu`: 飞书
- `wechat`: 企业微信
- `system`: 系统消息

多种类型可用逗号分隔，如: `email,feishu`

### 提醒状态
- `pending`: 待发送
- `sent`: 已发送
- `read`: 已读
- `failed`: 发送失败