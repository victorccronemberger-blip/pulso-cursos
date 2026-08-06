@extends('layouts.admin')
@push('title', get_phrase('Team Members'))
@push('meta')
@endpush
@push('css')
@endpush

@section('content')

<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Team Members List') }}
            </h4>

            <a href="{{ route('admin.team.create') }}"
                class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Add New Member') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="ol-card p-4">
    <div class="ol-card-body">

        <div class="row print-d-none row-gap-3">
            <div class="col-md-6">
                <div class="custom-dropdown">
                    <button class="dropdown-header btn ol-btn-light">
                        {{ get_phrase('Export') }}
                        <i class="fi-rr-file-export ms-2"></i>
                    </button>
                    <ul class="dropdown-list">
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="downloadPDF('.print-table', 'team-list')">
                                <i class="fi-rr-file-pdf"></i>
                                {{ get_phrase('PDF') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="window.print();">
                                <i class="fi-rr-print"></i>
                                {{ get_phrase('Print') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                @if(count($teams) > 0)

                <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <p class="admin-tInfo">
                        {{ get_phrase('Showing') . ' ' . count($teams) . ' ' . get_phrase('of') . ' ' . $teams->total() . ' ' . get_phrase('data') }}
                    </p>
                </div>

                <div class="table-responsive" id="team_list">
                    <table class="table eTable eTable-2 print-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ get_phrase('Member') }}</th>
                                <th>{{ get_phrase('Designation') }}</th>
                                <th>{{ get_phrase('Sort Order') }}</th>
                                <th>{{ get_phrase('Status') }}</th>
                                <th class="print-d-none">{{ get_phrase('Options') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($teams as $key => $row)
                            <tr>
                                <th scope="row">
                                    <p class="row-number">{{ ++$key }}</p>
                                </th>

                                <!-- Profile -->
                                <td>
                                    <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                        <div class="dAdmin_profile_img">
                                            <img class="rounded-circle object-fit-cover"
                                                width="45"
                                                height="45"
                                                src="{{ get_image($row->photo) }}">
                                        </div>
                                        <div class="ms-1">
                                            <h4 class="title fs-14px">{{ $row->name }}</h4>
                                            <p class="sub-title2 text-12px">
                                                {{ Str::limit($row->bio, 40) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Designation -->
                                <td>
                                    <div class="dAdmin_info_name min-w-150px">
                                        <p>{{ $row->designation }}</p>
                                    </div>
                                </td>

                                <!-- Sort Order -->
                                <td>
                                    {{ $row->sort_order }}
                                </td>

                                <!-- Status -->
                                <td>
                                    @if($row->status)
                                    <span class="badge bg-success">
                                        {{ get_phrase('Active') }}
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        {{ get_phrase('Inactive') }}
                                    </span>
                                    @endif
                                </td>

                                <!-- Options -->
                                <td class="print-d-none">
                                    <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                        <button class="btn ol-btn-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                            <span class="fi-rr-menu-dots-vertical"></span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.team.edit', $row->id) }}">
                                                    {{ get_phrase('Edit') }}
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" onclick="confirmModal('{{ route('admin.team.delete', $row->id) }}')"
                                                    href="javascript:void(0)">{{ get_phrase('Delete') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @else
                @include('admin.no_data')
                @endif

                @if(count($teams) > 0)
                <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <p class="admin-tInfo">
                        {{ get_phrase('Showing') . ' ' . count($teams) . ' ' . get_phrase('of') . ' ' . $teams->total() . ' ' . get_phrase('data') }}
                    </p>
                    {{ $teams->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
@endpush