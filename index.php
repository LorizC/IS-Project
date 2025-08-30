<?php
// Sample values – later connect with MySQL
$total_balance = 432568;
$total_change = 245860;
$total_expenses = 25.35;
$total_income = 22.56;
$balance_trend = 221478;
$monthly_expenses = [
    ["name" => "Food", "amount" => 1200, "percent" => 20, "color" => "bg-orange-500"],
    ["name" => "Transport", "amount" => 1200, "percent" => 15, "color" => "bg-yellow-500"],
    ["name" => "Healthcare", "amount" => 1200, "percent" => 18, "color" => "bg-red-400"],
    ["name" => "Education", "amount" => 1200, "percent" => 22, "color" => "bg-green-500"],
    ["name" => "Clothes", "amount" => 1200, "percent" => 10, "color" => "bg-blue-500"],
    ["name" => "Pets", "amount" => 1200, "percent" => 15, "color" => "bg-purple-500"],
];
?>
<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false }" x-bind:class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <title>Finance Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-20 bg-indigo-700 dark:bg-indigo-900 h-screen flex flex-col items-center py-6 space-y-10">
        <div class="w-12 h-12">
            <img src="images/icon.png" alt="Logo" class="w-full h-full object-contain rounded-full">
        </div>
        <nav class="flex flex-col space-y-8 text-white">
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="home"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Home</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="credit-card"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Wallet</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="dollar-sign"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Budget</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="target"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Goals</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="user"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Profile</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="bar-chart-2"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Analytics</span>
            </div>
            <div class="relative group flex items-center">
                <a href="#" class="hover:text-indigo-300"><i data-feather="file-text"></i></a>
                <span class="absolute left-12 px-2 py-1 text-xs text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition">Reports</span>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- Topbar -->
        <div class="flex justify-between items-center mb-6 relative" x-data="{ open: false }">
            <input type="text" placeholder="Search here" class="border px-4 py-2 rounded-lg w-1/3 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex space-x-6 items-center text-gray-600 dark:text-gray-300">
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="hover:text-indigo-600">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M6.05 
                                17.95l-1.414 1.414M17.95 17.95l1.414 1.414M6.05 
                                6.05L4.636 4.636M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/>
                    </svg>
                </button>
                <button class="hover:text-indigo-600"><i data-feather="bell"></i></button>
                <div class="relative">
                    <button @click="open = !open" class="w-10 h-10 bg-indigo-700 rounded-full flex items-center justify-center text-white">
                        <img src="images/profile1.png" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                    </button>
                    <div x-show="open" @click.outside="open = false" 
                        class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border py-3 z-50">
                        <div class="flex items-center space-x-3 px-4 pb-3 border-b dark:border-gray-700">
                            <img src="images/profile1.png" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">User Name</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">username@email.com</p>
                            </div>
                        </div>
                        <ul class="mt-2">
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200"><i data-feather="user" class="mr-3 text-indigo-600"></i> Profile</a></li>
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200"><i data-feather="credit-card" class="mr-3 text-indigo-600"></i> Wallets</a></li>
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200"><i data-feather="settings" class="mr-3 text-indigo-600"></i> Settings</a></li>
                        </ul>
                        <div class="border-t mt-2 pt-2 dark:border-gray-700">
                            <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600"><i data-feather="log-out" class="mr-3"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Title -->
        <h1 class="text-2xl font-bold mb-2">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Welcome to Expenses/Budget/Crypto? Management</p>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Balance</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_balance) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Change</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_change) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Expenses</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_expenses, 2) ?></h2>
                <p class="text-red-600 text-sm">▼ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Income</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_income, 2) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
        </div>

        <!-- Balance Trend & Expenses Breakdown -->
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-2">Balance Trends</h3>
                <h2 class="text-xl font-bold mb-2">₱<?= number_format($balance_trend) ?></h2>
                <canvas id="balanceChart"></canvas>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Monthly Expenses Breakdown</h3>
                
                <!-- Colored Line -->
                <div class="w-full h-2 flex rounded overflow-hidden mb-4">
                    <?php foreach($monthly_expenses as $expense): ?>
                        <div class="<?= $expense['color'] ?>" style="width: <?= $expense['percent'] ?>%"></div>
                    <?php endforeach; ?>
                </div>

                <!-- Expense List -->
                <ul class="space-y-3">
                    <?php foreach($monthly_expenses as $expense): ?>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 <?= $expense['color'] ?> rounded-full"></span>
                                <span><?= $expense['name'] ?></span>
                            </div>
                            <span>₱<?= number_format($expense['amount']) ?> (<?= $expense['percent'] ?>%)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>

    <script>
    const ctx = document.getElementById('balanceChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Balance',
                data: [50,100,80,120,150,130],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
    feather.replace();
    </script>
</body>
</html>
