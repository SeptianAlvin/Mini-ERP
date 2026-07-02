@extends('layouts.app')

@section('title', 'Money Track')
@section('page-title', 'Ringkasan Keuangan')

@section('content')
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pemasukan</h2>
                <p class="text-2xl font-bold text-green-500">Rp {{ number_format($Pemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pengeluaran</h2>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($Pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Saldo Bersih</h2>
                <p class="text-2xl font-bold text-gray-700">Rp {{ number_format($total_saldo, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Bagian Grafik Utama (Tren Bulanan) -->
        <div class="mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-center sm:text-left mb-2 sm:mb-0">Tren Keuangan</h2>
                    <form action="{{ route('dashboard') }}" method="GET">
                        <select name="year" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm p-1.5 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bagian Grafik Komposisi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Doughnut (Komposisi Total) -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4 text-center">Komposisi Keuangan (Total)</h2>
                <div class="relative h-64 w-full">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
            
            <!-- Grafik Doughnut (Pengeluaran per Kategori) -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4 text-center">Pengeluaran Kategori ({{ $selectedYear }})</h2>
                <div class="relative h-64 w-full">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Doughnut Chart Data
        const totalIncome = {{ $Pemasukan }};
        const totalExpense = {{ $Pengeluaran }};
        
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [totalIncome, totalExpense],
                    backgroundColor: ['#10B981', '#EF4444'], // green-500, red-500
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Bar Chart Data
        const monthlyIncome = {!! $monthlyIncome !!};
        const monthlyExpense = {!! $monthlyExpense !!};
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: monthlyIncome,
                        backgroundColor: '#10B981', // green-500
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: monthlyExpense,
                        backgroundColor: '#EF4444', // red-500
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Category Chart Data
        const expenseLabels = {!! $expenseLabels !!};
        const expenseData = {!! $expenseData !!};
        
        // Generate colors for each category
        const backgroundColors = [
            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6', '#F43F5E', '#84CC16'
        ];

        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: expenseLabels.length > 0 ? expenseLabels : ['Belum ada pengeluaran'],
                datasets: [{
                    data: expenseData.length > 0 ? expenseData : [1],
                    backgroundColor: expenseData.length > 0 ? backgroundColors.slice(0, expenseData.length) : ['#E5E7EB'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (expenseData.length === 0) return 'Belum ada data';
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
    <div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Transaksi Terakhir</h2>
            <p class="text-gray-600">Daftar transaksi terakhir yang telah dilakukan</p>
            @if (count($recent_transactions) > 0)
                @foreach ($recent_transactions as $trx)
                    <div class="border-t py-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($trx->trans_date)->format('d M Y') }} - {{ optional($trx->category)->cat_name ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $trx->desc }}</p>
                        </div>
                        <p class="{{ optional($trx->category)->type === 'income' ? 'text-green-500' : 'text-red-600' }} font-bold text-lg">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">Belum ada transaksi.</p>
            @endif
        </div>
    </div>
@endsection


