<?php $__env->startSection('content'); ?>
<div class="hero-section text-center py-5">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary mb-4">SubAlert</h1>
        <p class="lead mb-4">专业的订阅提醒管理工具，帮您轻松管理各种订阅服务</p>
        
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <p class="text-muted">
                    再也不用担心忘记续费导致服务中断！SubAlert 支持多种提醒方式，让您的订阅管理更加轻松。
                </p>
            </div>
        </div>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg px-4 me-md-2">
                    <i class="bi bi-person-plus"></i> 立即注册
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-primary btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right"></i> 登录
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-speedometer2"></i> 进入仪表盘
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="features-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">功能特色</h2>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-bell text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">智能提醒</h5>
                        <p class="card-text">支持邮件、飞书、企业微信等多种提醒方式，确保不错过任何重要的续费时间。</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-graph-up text-success" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">支出分析</h5>
                        <p class="card-text">详细的订阅支出统计和分析，帮您更好地管理和规划订阅预算。</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-shield-check text-info" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">安全可靠</h5>
                        <p class="card-text">企业级安全保障，您的订阅信息和个人数据得到全面保护。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cta-section py-5">
    <div class="container text-center">
        <h2 class="mb-4">开始管理您的订阅</h2>
        <p class="lead mb-4">简单几步，轻松管理所有订阅服务</p>
        
        <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg">
                <i class="bi bi-rocket-takeoff"></i> 免费开始使用
            </a>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/work/subalert/subalert/resources/views/welcome.blade.php ENDPATH**/ ?>