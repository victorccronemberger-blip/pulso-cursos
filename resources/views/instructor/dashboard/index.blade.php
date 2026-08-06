@extends('layouts.instructor')
@push('title', get_phrase('Dashboard'))
@push('meta')@endpush
@push('css')
@endpush
@section('content')

<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Dashboard') }}
            </h4>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     STAT CARDS
══════════════════════════════════════ --}}
<div class="row g-2 g-sm-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body" style="display: flex; align-items: center; gap: 14px;">
                <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                        <line x1="12" y1="7" x2="16" y2="7" />
                        <line x1="12" y1="11" x2="16" y2="11" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                    <p class="title card-title-hover fs-18px" style="margin: 0;">{{ (int) count_course_by_instructor(auth()->user()->id) }}</p>
                    <p class="sub-title fs-14px" style="margin: 0;">{{ get_phrase('Courses') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body" style="display: flex; align-items: center; gap: 14px;">
                <div style="background: linear-gradient(135deg, #fdf4ff, #f3e8ff); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="23 7 16 12 23 17 23 7" />
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                    <p class="title card-title-hover fs-18px" style="margin: 0;">{{ (int) count_instructor_lesson(auth()->user()->id) }}</p>
                    <p class="sub-title fs-14px" style="margin: 0;">{{ get_phrase('Lessons') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body" style="display: flex; align-items: center; gap: 14px;">
                <div style="background: linear-gradient(135deg, #fef9c3, #fef08a); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                    <p class="title card-title-hover fs-18px" style="margin: 0;">{{ (int) count_student_by_instructor(auth()->user()->id) }}</p>
                    <p class="sub-title fs-14px" style="margin: 0;">{{ get_phrase('Enrollment') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body" style="display: flex; align-items: center; gap: 14px;">
                <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                    <p class="title card-title-hover fs-18px" style="margin: 0;">{{ App\Models\User::where('role', 'student')->count() }}</p>
                    <p class="sub-title fs-14px" style="margin: 0;">{{ get_phrase('Students') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body" style="display: flex; align-items: center; gap: 14px;">
                <div style="background: linear-gradient(135deg, #fff1f2, #ffe4e6); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                    <p class="title card-title-hover fs-18px" style="margin: 0;">{{ App\Models\User::where('role', 'instructor')->count() }}</p>
                    <p class="sub-title fs-14px" style="margin: 0;">{{ get_phrase('Instructor') }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════
     REVENUE CHART
══════════════════════════════════════ --}}
@php
$monthlyData = $monthly_amount ?? array_fill(0, 13, 0);
$currentMonth = (int) date('n');
$prevMonth = $currentMonth > 1 ? $currentMonth - 1 : 12;
$curVal = isset($monthlyData[$currentMonth]) ? (float) $monthlyData[$currentMonth] : 0;
$prevVal = isset($monthlyData[$prevMonth]) ? (float) $monthlyData[$prevMonth] : 0;
$momDiff = $prevVal > 0 ? round((($curVal - $prevVal) / $prevVal) * 100, 1) : 0;
$momUp = $momDiff >= 0;
@endphp

<div class="row">
    <div class="col-xl-12">
        <div class="ol-card">

            {{-- Header --}}
            <div class="rev-header">

                {{-- Left: icon + title --}}
                <div class="rev-header-left">
                    <div class="rev-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <div class="rev-header-text">
                        <p class="rev-overline">{{ get_phrase('Analytics') }}</p>
                        <p class="rev-title">{{ get_phrase('Instructor Revenue This Year') }}</p>
                    </div>
                </div>

                {{-- Right: MoM + tabs + link --}}
                <div class="rev-header-right">

                    {{-- Month-over-month indicator --}}
                    <div class="rev-mom">
                        <span class="rev-mom-badge {{ $momUp ? 'up' : 'down' }}">
                            @if($momUp)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18 15 12 9 6 15" />
                            </svg>
                            +{{ $momDiff }}%
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                            {{ $momDiff }}%
                            @endif
                        </span>
                        <p class="rev-mom-text">{{ get_phrase('vs last month') }}</p>
                    </div>

                    {{-- Filter tabs --}}
                    <div class="rev-tabs">
                        <button class="rev-tab-btn active" data-range="all" onclick="switchRevTab(this, 'all')">{{ get_phrase('All') }}</button>
                        <button class="rev-tab-btn" data-range="q1" onclick="switchRevTab(this, 'q1')">Q1</button>
                        <button class="rev-tab-btn" data-range="q2" onclick="switchRevTab(this, 'q2')">Q2</button>
                        <button class="rev-tab-btn" data-range="q3" onclick="switchRevTab(this, 'q3')">Q3</button>
                        <button class="rev-tab-btn" data-range="q4" onclick="switchRevTab(this, 'q4')">Q4</button>
                    </div>

                    {{-- Link --}}
                    <a class="rev-link-btn" href="{{ route('instructor.payout.reports') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ get_phrase('Instructor Revenue') }}">
                        <i class="fi-rr-arrow-alt-right"></i>
                    </a>
                </div>
            </div>

            <div class="rev-divider"></div>

            {{-- Chart --}}
            <div class="rev-chart-wrap">
                <canvas id="myChart" class="mw-100 w-100" height="300"></canvas>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     PIE + PAYOUT
══════════════════════════════════════ --}}

@php
$courses = App\Models\Course::where('user_id', auth()->user()->id)->get()->groupBy('status');
$active = isset($courses['active']) ? $courses['active']->count() : 0;
$upcoming = isset($courses['upcoming']) ? $courses['upcoming']->count() : 0;
$pending = isset($courses['pending']) ? $courses['pending']->count() : 0;
$private = isset($courses['private']) ? $courses['private']->count() : 0;
$draft = isset($courses['draft']) ? $courses['draft']->count() : 0;
$inactive = isset($courses['inactive']) ? $courses['inactive']->count() : 0;
@endphp

<div class="row">
    <div class="col-md-5">
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="title fs-14px">{{ get_phrase('Course Status') }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn-link" href="{{ route('instructor.courses') }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="{{ get_phrase('Explore Courses') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                    </div>
                </div>

                @php
                $pieTotal = $active + $upcoming + $pending + $private + $draft + $inactive;
                $legend = [
                ['label' => 'Active', 'color' => '#12c093', 'value' => $active],
                ['label' => 'Upcoming', 'color' => '#1b84ff', 'value' => $upcoming],
                ['label' => 'Pending', 'color' => '#ff2583', 'value' => $pending],
                ['label' => 'Private', 'color' => '#1a1d2e', 'value' => $private],
                ['label' => 'Draft', 'color' => '#878d97', 'value' => $draft],
                ['label' => 'Inactive', 'color' => '#dadada', 'value' => $inactive],
                ];
                @endphp

                <div class="dash-pie-wrap">
                    <div class="dash-pie-canvas-wrap">
                        <canvas id="pie2"></canvas>
                        <div class="dash-pie-center">
                            <p class="dash-pie-center-number">{{ $pieTotal }}</p>
                            <p class="dash-pie-center-label">Total</p>
                        </div>
                    </div>
                    <div class="dash-pie-divider"></div>
                    <div class="dash-pie-legend">
                        @foreach($legend as $item)
                        @php $pct = $pieTotal > 0 ? round(($item['value'] / $pieTotal) * 100) : 0; @endphp
                        <div class="dash-legend-row">
                            <div class="dash-legend-top">
                                <div class="dash-legend-label">
                                    <span class="dash-legend-dot" style="background: {{ $item['color'] }};"></span>
                                    <span class="dash-legend-name">{{ get_phrase($item['label']) }}</span>
                                </div>
                                <div class="dash-legend-right">
                                    <span class="dash-legend-count">{{ $item['value'] }}</span>
                                    <span class="dash-legend-pct">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="dash-legend-bar-track">
                                <div class="dash-legend-bar-fill" style="width: {{ $pct }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="ol-card" id="unpaid-instructor-revenue">
            <div class="ol-card-body p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="title text-14px mb-3">{{ get_phrase('Pending Requested withdrawal') }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn-link" href="{{ route('instructor.payout.reports') }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="{{ get_phrase('Instructor Payout') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-hover mb-0">
                        <tbody>
                            @php
                            $payouts = App\Models\Payout::where('user_id', auth()->user()->id)
                            ->limit(20)
                            ->orderBy('id')
                            ->get();
                            @endphp
                            @foreach($payouts as $payout)
                            <tr>
                                <td>
                                    <p class="title fs-14px">{{ get_phrase('Name') }}: {{ $payout->user->name }}</p>
                                    <small>{{ get_phrase('Email') }}: <span class="text-muted font-13">{{ $payout->user->email }}</span></small>
                                </td>
                                <td>
                                    <p class="title fs-14px">{{ currency($payout->amount) }}</p>
                                    <small><span class="text-muted font-13">{{ get_phrase('Requested withdrawal amount') }}</span></small>
                                </td>
                                <td>
                                    @if($payout->status == 1)
                                    <span class="badge bg-success">{{ get_phrase('Paid') }}</span>
                                    @else
                                    <span class="badge bg-warning">{{ get_phrase('Pending') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/backend/vendors/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/backend/vendors/chart-js/chart.js') }}"></script>

<script>
    "use strict";

    // ── All monthly data from backend (index 0 unused, 1=Jan … 12=Dec) ──
    const allMonthlyData = <?php echo json_encode(array_values($monthly_amount)); ?>;

    const monthLabels = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    // Quarter slices
    const quarters = {
        all: {
            labels: monthLabels.slice(1),
            data: allMonthlyData.slice(1)
        },
        q1: {
            labels: monthLabels.slice(1, 4),
            data: allMonthlyData.slice(1, 4)
        },
        q2: {
            labels: monthLabels.slice(4, 7),
            data: allMonthlyData.slice(4, 7)
        },
        q3: {
            labels: monthLabels.slice(7, 10),
            data: allMonthlyData.slice(7, 10)
        },
        q4: {
            labels: monthLabels.slice(10, 13),
            data: allMonthlyData.slice(10, 13)
        },
    };

    // Gradient fill helper
    function makeGradient(ctx) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.22)');
        gradient.addColorStop(0.6, 'rgba(99, 102, 241, 0.06)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.00)');
        return gradient;
    }

    const ctx = document.getElementById('myChart').getContext('2d');

    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: quarters.all.labels,
            datasets: [{
                label: "{{ get_phrase('Instructor revenue') }}",
                data: quarters.all.data,
                fill: true,
                backgroundColor: makeGradient(ctx),
                borderColor: '#6366f1',
                borderWidth: 2.5,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#6366f1',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        color: '#5a6072',
                        usePointStyle: true,
                        pointStyleWidth: 8,
                        boxHeight: 6,
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    titleColor: '#fff',
                    bodyColor: '#9299b0',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: {
                        size: 12,
                        weight: '700'
                    },
                    bodyFont: {
                        size: 12
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(ctx) {
                            return ' ' + ctx.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: '#9299b0',
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        padding: 6,
                    }
                },
                y: {
                    grid: {
                        color: '#f0f1f5',
                        drawBorder: false,
                    },
                    border: {
                        display: false,
                        dash: [4, 4]
                    },
                    ticks: {
                        color: '#9299b0',
                        font: {
                            size: 11
                        },
                        padding: 8,
                        callback: function(val) {
                            if (val >= 1000) return (val / 1000).toFixed(1) + 'k';
                            return val;
                        }
                    }
                }
            }
        }
    });

    // ── Tab switcher ──
    function switchRevTab(btn, range) {
        document.querySelectorAll('.rev-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const q = quarters[range];
        revenueChart.data.labels = q.labels;
        revenueChart.data.datasets[0].data = q.data;
        revenueChart.data.datasets[0].backgroundColor = makeGradient(ctx);
        revenueChart.update('active');
    }

    // ── Pie / Donut chart ──
    const project_progress2 = document.getElementById('pie2');
    new Chart(project_progress2, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Upcoming', 'Pending', 'Private', 'Draft', 'Inactive'],
            datasets: [{
                backgroundColor: ["#12c093", "#1b84ff", "#ff2583", "#1a1d2e", "#878d97", "#dadada"],
                label: ' {{ get_phrase("Courses") }}',
                data: [{{ $active }}, {{ $upcoming }}, {{ $pending }}, {{ $private }}, {{ $draft }}, {{ $inactive }}],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderColor: '#ffffff',
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            cutout: '74%',
            animation: {
                animateRotate: true,
                duration: 900,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    titleColor: '#fff',
                    bodyColor: '#9299b0',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 12,
                        weight: '700'
                    },
                    bodyFont: {
                        size: 11
                    },
                },
            },
        },
    });
</script>
@endpush