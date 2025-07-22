<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h1>仪表盘</h1>
        <p>欢迎回来，<?php echo e(Auth::user()->nickname); ?>！</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">总订阅数</h5>
                <h2><?php echo e($totalSubscriptions); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">月度支出</h5>
                <h2>¥<?php echo e(number_format($monthlyExpense, 2)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">即将到期</h5>
                <h2><?php echo e($expiringSubscriptions->count()); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">待处理提醒</h5>
                <h2><?php echo e($reminderCount); ?></h2>
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
                                <small class="text-muted"><?php echo e($subscription->expire_at->format('Y-m-d')); ?> 到期</small>
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
                <h5>最近添加的订阅</h5>
            </div>
            <div class="card-body">
                <?php if($recentSubscriptions->count() > 0): ?>
                    <?php $__currentLoopData = $recentSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?php echo e($subscription->name); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($subscription->created_at->format('Y-m-d')); ?> 添加</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">¥<?php echo e($subscription->price); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted">暂无订阅记录</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <h5>快速操作</h5>
                <a href="<?php echo e(route('subscriptions.create')); ?>" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> 添加订阅
                </a>
                <a href="<?php echo e(route('reminders.create')); ?>" class="btn btn-success me-2">
                    <i class="bi bi-bell"></i> 设置提醒
                </a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-info">
                    <i class="bi bi-list"></i> 查看所有订阅
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/work/subalert/subalert/resources/views/dashboard.blade.php ENDPATH**/ ?>