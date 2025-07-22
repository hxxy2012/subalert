<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h1>管理仪表盘</h1>
        <p>欢迎回来，<?php echo e(Auth::guard('admin')->user()->nickname); ?>！</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">总用户数</h5>
                <h2><?php echo e($userStats['total_users']); ?></h2>
                <small>活跃用户：<?php echo e($userStats['active_users']); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">总订阅数</h5>
                <h2><?php echo e($subscriptionStats['total_subscriptions']); ?></h2>
                <small>活跃订阅：<?php echo e($subscriptionStats['active_subscriptions']); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">待发送提醒</h5>
                <h2><?php echo e($reminderStats['pending_reminders']); ?></h2>
                <small>已发送：<?php echo e($reminderStats['sent_reminders']); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">今日新用户</h5>
                <h2><?php echo e($userStats['new_users_today']); ?></h2>
                <small>本月：<?php echo e($userStats['new_users_this_month']); ?></small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>即将到期的订阅</h5>
            </div>
            <div class="card-body">
                <?php if($expiringSubscriptions->count() > 0): ?>
                    <?php $__currentLoopData = $expiringSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?php echo e($subscription->name); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($subscription->user->nickname); ?> - <?php echo e($subscription->expire_at->format('Y-m-d')); ?></small>
                            </div>
                            <div>
                                <span class="badge bg-warning"><?php echo e($subscription->getDaysUntilExpiry()); ?>天</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted">暂无即将到期的订阅</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>订阅类型分布</h5>
            </div>
            <div class="card-body">
                <?php if($subscriptionTypes->count() > 0): ?>
                    <?php $__currentLoopData = $subscriptionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?php echo e($type->type); ?></strong>
                            </div>
                            <div>
                                <span class="badge bg-primary"><?php echo e($type->count); ?></span>
                                <span class="badge bg-success">¥<?php echo e(number_format($type->total_price, 2)); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted">暂无数据</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/work/subalert/subalert/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>