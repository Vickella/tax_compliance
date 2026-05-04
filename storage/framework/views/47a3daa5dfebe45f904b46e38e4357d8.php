<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Automated Tax Filing System')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        /* Dark theme inputs */
        .form-control,
        .form-select,
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="email"],
        input[type="password"],
        input[type="search"],
        textarea,
        select {
            background-color: rgba(255,255,255,0.06) !important;
            color: rgba(255,255,255,0.92) !important;
            border: 1px solid rgba(255,255,255,0.18) !important;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        ::placeholder {
            color: rgba(255,255,255,0.55) !important;
        }

        .form-control:focus,
        .form-select:focus,
        input:focus,
        textarea:focus,
        select:focus {
            outline: none !important;
            background-color: rgba(255,255,255,0.08) !important;
            border-color: rgba(108, 99, 255, 0.85) !important;
            box-shadow: 0 0 0 0.20rem rgba(108, 99, 255, 0.20) !important;
        }

        select option {
            color: #0b1220 !important;
            background: white;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) rgba(255,255,255,0.05);
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .card {
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            padding: 1.25rem;
        }

        .module-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #e2e8f0;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .module-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .module-btn.active {
            background: rgba(108, 99, 255, 0.2);
            color: white;
            border-left: 3px solid #6c63ff;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-950 via-slate-950 to-purple-950 text-slate-100 antialiased overflow-hidden h-screen">
    
    
    <div class="flex h-screen overflow-hidden">
        
        
        <aside class="w-60 bg-black/30 border-r border-white/10 flex-shrink-0 flex flex-col overflow-hidden">
            
            
            <div class="px-4 py-5 border-b border-white/10 flex items-center gap-3 flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-green-600 flex items-center justify-center text-white font-bold">AT</div>
                <div>
                    <div class="font-semibold text-white text-sm">ATFS</div>
                    <div class="text-xs text-slate-400">Tax Filing & Compliance</div>
                </div>
            </div>
            
            
            <div class="flex-1 overflow-y-auto px-3 py-4">
                <nav class="space-y-1">
                    
                    
                    <button onclick="window.location='<?php echo e(route('dashboard')); ?>'" 
                            class="module-btn <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        🏠 Home
                    </button>
                    
                    
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-4 pb-1">Tax Filing</div>
                    
                    
                    <button onclick="window.location='<?php echo e(route('modules.index', ['module' => 'payroll'])); ?>'" 
                            class="module-btn <?php echo e(request()->is('m/payroll*') ? 'active' : ''); ?>">
                        💰 PAYE
                    </button>
                    
                    
                    <button onclick="window.location='<?php echo e(route('modules.tax.vat.index')); ?>'" 
                            class="module-btn <?php echo e(request()->is('m/tax/vat*') ? 'active' : ''); ?>">
                        📊 VAT
                    </button>
                    
                    
                    <button onclick="window.location='<?php echo e(route('modules.tax.income.index')); ?>'" 
                            class="module-btn <?php echo e(request()->is('m/tax/income*') ? 'active' : ''); ?>">
                        🏢 Income Tax
                    </button>
                    
                    
                    <button onclick="window.location='<?php echo e(route('modules.tax.qpd.index')); ?>'" 
                            class="module-btn <?php echo e(request()->is('m/tax/qpd*') ? 'active' : ''); ?>">
                        📋 Quarterly Payments
                    </button>

                    
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-4 pb-1">Settings</div>
                    
                    
                    <button onclick="window.location='<?php echo e(route('modules.index', ['module' => 'company-settings'])); ?>'" 
                            class="module-btn <?php echo e(request()->is('m/company-settings*') ? 'active' : ''); ?>">
                        ⚙️ Company Settings
                    </button>

                    
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-4 pb-1">Transactions</div>
                    
                    
                    <div class="relative group">
                        <button class="module-btn w-full flex items-center justify-between">
                            <span>📑 Transactions</span>
                            <span class="text-xs">▼</span>
                        </button>
                        
                        
                        <div class="hidden group-hover:flex flex-col bg-black/40 border border-white/10 rounded mt-1 absolute left-0 right-0 z-10">
                            <a href="<?php echo e(route('modules.index', ['module' => 'sales'])); ?>" 
                               class="px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                Sales
                            </a>
                            <a href="<?php echo e(route('modules.index', ['module' => 'purchases'])); ?>" 
                               class="px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                Purchases
                            </a>
                            <a href="<?php echo e(route('modules.index', ['module' => 'accounting'])); ?>" 
                               class="px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                Accounting
                            </a>
                            <a href="<?php echo e(route('modules.index', ['module' => 'inventory'])); ?>" 
                               class="px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                Inventory
                            </a>
                        </div>
                    </div>

                    <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-4 pb-1">Admin</div>
                        <button onclick="window.location='<?php echo e(route('admin.users.index')); ?>'"
                                class="module-btn <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                            👥 Users
                        </button>
                    <?php endif; ?>
                </nav>
            </div>
            
            
            <div class="border-t border-white/10 p-4 bg-black/20 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-300">
                        <?php echo e(auth()->user()->name ?? 'User'); ?>

                        <div class="text-xs text-slate-400">
                            <?php if(auth()->user()->isAdmin()): ?>
                                Administrator
                            <?php else: ?>
                                Accountant
                            <?php endif; ?>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-xs text-slate-400 hover:text-white px-2 py-1 rounded-lg hover:bg-white/10">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        
        <main class="flex-1 flex flex-col overflow-hidden">
            
            
            <div class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
            
            
            <footer class="bg-black/20 border-t border-white/10 flex-shrink-0 px-6 py-2 text-xs text-slate-400">
                <div class="flex justify-between">
                    <span>© 2026 Automated Tax Filing System. All rights reserved.</span>
                    <span>v1.0.0</span>
                </div>
            </footer>
        </main>
    </div>
</body>
</html><?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/layouts/app.blade.php ENDPATH**/ ?>