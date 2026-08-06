@extends('layouts.instructor')
@push('title', get_phrase('Payout report'))
@push('meta')@endpush
@push('css')
<style>
    .daterangepicker {
        z-index: 9999 !important;
    }
</style>
@endpush
@section('content')

<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between flex-md-nowrap flex-wrap gap-3">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Payouts') }}
            </h4>
            @if ($payout_request)
            <a onclick="confirmModal('{{ route('instructor.payout.delete', $payout_request->id) }}')" href="javascript:void(0)" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-minus"></span>
                {{ get_phrase('Delete request') }}
            </a>
            @else
            <a href="#" onclick="ajaxModal('{{ route('modal', ['instructor.payout_report.withdrawal']) }}', '{{ get_phrase('Request a new withdrawal') }}')" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Request withdrawal') }}</span>
            </a>
            @endif
        </div>
    </div>
</div>

<div class="row g-2 g-sm-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 print-d-none mb-3">

    <div class="col">
        <div class="ol-card card-hover h-100" style="display: flex;">
            <div class="ol-card-body" style="display: flex; align-items: center; justify-content: flex-start; gap: 14px; width: 100%;">
                <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px; line-height: 1.3;">
                    <p class="title card-title-hover fs-18px" style="margin: 0; line-height: 1.2;">{{ number_format((float) $balance, 2, '.', '') }}</p>
                    <p class="sub-title fs-14px" style="margin: 0; line-height: 1.2;">{{ get_phrase('Available') }} ({{ currency() }})</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover h-100" style="display: flex;">
            <div class="ol-card-body" style="display: flex; align-items: center; justify-content: flex-start; gap: 14px; width: 100%;">
                <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px; line-height: 1.3;">
                    <p class="title card-title-hover fs-18px" style="margin: 0; line-height: 1.2;">{{ number_format((float) $total_payout, 2, '.', '') }}</p>
                    <p class="sub-title fs-14px" style="margin: 0; line-height: 1.2;">{{ get_phrase('Total payout') }} ({{ currency() }})</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="ol-card card-hover h-100" style="display: flex;">
            <div class="ol-card-body" style="display: flex; align-items: center; justify-content: flex-start; gap: 14px; width: 100%;">
                <div style="background: linear-gradient(135deg, #fef9c3, #fef08a); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px; line-height: 1.3;">
                    <p class="title card-title-hover fs-18px" style="margin: 0; line-height: 1.2;">{{ number_format((float) ($payout_request->amount ?? 0), 2, '.', '') }}</p>
                    <p class="sub-title fs-14px" style="margin: 0; line-height: 1.2;">{{ get_phrase('Requested') }} ({{ currency() }})</p>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="ol-card">
            <div class="ol-card-body">
                <div class="row mt-3 mb-4">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="custom-dropdown ms-2">
                            <button class="dropdown-header btn ol-btn-light">
                                {{ get_phrase('Export') }}
                                <i class="fi-rr-file-export ms-2"></i>
                            </button>
                            <ul class="dropdown-list">
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="downloadPDF('.print-table', 'payout-reports')"><i class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="window.print();"><i class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <form action="{{ route('instructor.payout.reports') }}" method="get">
                            <div class="row row-gap-3">
                                <div class="col-md-9">
                                    <div class="search-input flex-grow-1">
                                        <input type="text" class="form-control ol-form-control daterangepicker w-100" name="eDateRange" value="{{ date('m/d/Y', $start_date) . ' - ' . date('m/d/Y', $end_date) }}" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn ol-btn-primary w-100" onclick="update_date_range();">{{ get_phrase('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @if (count($payout_reports))
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($payout_reports) . ' ' . get_phrase('of') . ' ' . $payout_reports->total() . ' ' . get_phrase('data') }}
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table eTable eTable-2 print-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ get_phrase('Payout amount') }}</th>
                                        <th>{{ get_phrase('Payment type') }}</th>
                                        <th>{{ get_phrase('Date processed') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payout_reports as $key => $row)
                                    <tr class="gradeU">
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                <div class="dAdmin_profile_name">
                                                    <h4 class="title fs-14px">{{ currency($row->amount, 2) }}</h4>
                                                    <p>{{ date('D, d M Y', strtotime($row->created_at)) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($row->status == 0)
                                            <p class="badge bg-danger">{{ get_phrase('Pending') }}</p>
                                            @endif
                                            {{ ucfirst($row->payment_type) }}
                                        </td>
                                        <td>
                                            @if ($row->status == 0)
                                            <p class="badge bg-danger">{{ get_phrase('Pending') }}</p>
                                            @else
                                            {{ date('D, d M Y', strtotime($row->updated_at)) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($payout_reports) . ' ' . get_phrase('of') . ' ' . $payout_reports->total() . ' ' . get_phrase('data') }}
                            </p>
                            {{ $payout_reports->links() }}
                        </div>
                        @else
                        @include('instructor.no_data')
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
    function update_date_range() {
        var x = $("#selectedValue").html();
        $("#date_range").val(x);
    }
</script>
@endpush