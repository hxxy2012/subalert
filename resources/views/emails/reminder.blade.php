<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>订阅到期提醒</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; text-align: center; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .footer { background-color: #6c757d; color: white; text-align: center; padding: 10px; border-radius: 0 0 5px 5px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .warning { color: #856404; background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>订阅到期提醒</h1>
        </div>
        
        <div class="content">
            <p>亲爱的 {{ $user->nickname }}，</p>
            
            <p>您的订阅服务即将到期，请及时续费以免影响使用：</p>
            
            <div class="warning">
                <h3>{{ $subscription->name }}</h3>
                <p><strong>到期时间：</strong>{{ $subscription->expire_at->format('Y年m月d日') }}</p>
                <p><strong>剩余天数：</strong>{{ $daysLeft }}天</p>
                <p><strong>订阅价格：</strong>¥{{ $subscription->price }}</p>
                @if($subscription->cycle)
                    <p><strong>订阅周期：</strong>{{ $subscription->cycle_display }}</p>
                @endif
            </div>
            
            @if($daysLeft <= 0)
                <p style="color: #dc3545; font-weight: bold;">⚠️ 该订阅已经过期，请尽快续费！</p>
            @elseif($daysLeft <= 3)
                <p style="color: #fd7e14; font-weight: bold;">⚠️ 该订阅即将在{{ $daysLeft }}天内到期，请及时续费！</p>
            @else
                <p style="color: #ffc107; font-weight: bold;">📅 该订阅将在{{ $daysLeft }}天后到期，请注意及时续费。</p>
            @endif
            
            <p style="margin-top: 20px;">
                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn">立即管理订阅</a>
            </p>
            
            <p>如果您已经续费，请忽略此邮件。</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} SubAlert. 订阅提醒管理工具</p>
            <p><small>这是一封系统自动发送的邮件，请勿回复。</small></p>
        </div>
    </div>
</body>
</html>