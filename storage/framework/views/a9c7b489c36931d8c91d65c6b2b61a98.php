<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>订阅管理</h1>
    <a href="<?php echo e(route('subscriptions.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> 添加订阅
    </a>
</div>

<!-- 搜索和筛选 -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('subscriptions.index')); ?>">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="搜索订阅名称" 
                           value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">全部类型</option>
                        <option value="video" <?php echo e(request('type') == 'video' ? 'selected' : ''); ?>>视频</option>
                        <option value="music" <?php echo e(request('type') == 'music' ? 'selected' : ''); ?>>音乐</option>
                        <option value="software" <?php echo e(request('type') == 'software' ? 'selected' : ''); ?>>软件</option>
                        <option value="communication" <?php echo e(request('type') == 'communication' ? 'selected' : ''); ?>>通讯</option>
                        <option value="other" <?php echo e(request('type') == 'other' ? 'selected' : ''); ?>>其他</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>正常</option>
                        <option value="paused" <?php echo e(request('status') == 'paused' ? 'selected' : ''); ?>>暂停</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>已取消</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>已过期</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>创建时间</option>
                        <option value="expire_at" <?php echo e(request('sort') == 'expire_at' ? 'selected' : ''); ?>>到期时间</option>
                        <option value="price" <?php echo e(request('sort') == 'price' ? 'selected' : ''); ?>>价格</option>
                        <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>名称</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="order" class="form-select">
                        <option value="desc" <?php echo e(request('order') == 'desc' ? 'selected' : ''); ?>>降序</option>
                        <option value="asc" <?php echo e(request('order') == 'asc' ? 'selected' : ''); ?>>升序</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">搜索</button>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-outline-secondary">重置</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 订阅列表 -->
<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title"><?php echo e($subscription->name); ?></h5>
                        <span class="badge bg-<?php echo e($subscription->status == 'active' ? 'success' : ($subscription->status == 'expired' ? 'danger' : 'warning')); ?>">
                            <?php echo e($subscription->status_display); ?>

                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <span class="badge bg-secondary"><?php echo e($subscription->type_display); ?></span>
                        <span class="badge bg-info"><?php echo e($subscription->cycle_display); ?></span>
                    </div>
                    
                    <p class="card-text">
                        <strong>价格：</strong>¥<?php echo e($subscription->price); ?><br>
                        <strong>到期时间：</strong><?php echo e($subscription->expire_at->format('Y-m-d')); ?><br>
                        <?php if($subscription->isExpiring(7)): ?>
                            <span class="text-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                <?php echo e($subscription->getDaysUntilExpiry()); ?>天后到期
                            </span>
                        <?php elseif($subscription->isExpired()): ?>
                            <span class="text-danger">
                                <i class="bi bi-x-circle"></i> 已过期
                            </span>
                        <?php endif; ?>
                    </p>
                    
                    <?php if($subscription->note): ?>
                        <p class="card-text"><small class="text-muted"><?php echo e(Str::limit($subscription->note, 50)); ?></small></p>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="<?php echo e(route('subscriptions.show', $subscription)); ?>" class="btn btn-outline-primary btn-sm">查看</a>
                        <a href="<?php echo e(route('subscriptions.edit', $subscription)); ?>" class="btn btn-outline-success btn-sm">编辑</a>
                        <?php if($subscription->isExpiring(30) || $subscription->isExpired()): ?>
                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#renewModal<?php echo e($subscription->id); ?>">
                                续费
                            </button>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('subscriptions.destroy', $subscription)); ?>" class="d-inline" 
                              onsubmit="return confirm('确定要删除这个订阅吗？')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm">删除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 续费模态框 -->
        <?php if($subscription->isExpiring(30) || $subscription->isExpired()): ?>
            <div class="modal fade" id="renewModal<?php echo e($subscription->id); ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo e(route('subscriptions.renew', $subscription)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="modal-header">
                                <h5 class="modal-title">续费 - <?php echo e($subscription->name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="expire_at<?php echo e($subscription->id); ?>" class="form-label">新的到期时间</label>
                                    <input type="date" class="form-control" id="expire_at<?php echo e($subscription->id); ?>" 
                                           name="expire_at" required min="<?php echo e(now()->format('Y-m-d')); ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">确认续费</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5>暂无订阅记录</h5>
                    <p class="text-muted">开始添加您的第一个订阅吧！</p>
                    <a href="<?php echo e(route('subscriptions.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> 添加订阅
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- 分页 -->
<div class="d-flex justify-content-center">
    <?php echo e($subscriptions->appends(request()->query())->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/work/subalert/subalert/resources/views/subscriptions/index.blade.php ENDPATH**/ ?>