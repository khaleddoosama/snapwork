@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <x-custom.header-page title="{{ $title }}" />


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.job_title') }}</th>
                                            <th>{{ __('attributes.freelancer') }}</th>
                                            <th>{{ __('attributes.new_bid') }}</th>
                                            <th>{{ __('attributes.new_duration') }}</th>
                                            <th>{{ __('attributes.type') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
                                            <th>{{ __('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($request_changes as $request_change)
                                            <tr>
                                                <td>{{ $request_change->id }}</td>
                                                <td><a href="{{ route('admin.jobs.show', $request_change->job) }}">{{ $request_change->job->title }}</a></td>
                                                <td>{{ $request_change->application->freelancer->name }}</td>
                                                <td>{{ $request_change->new_bid }}</td>
                                                <td>{{ $request_change->new_duration }}</td>
                                                <td>
                                                    @if ($request_change->type == 'change')
                                                        <span class="badge badge-warning">{{ $request_change->type }}</span>
                                                    @elseif ($request_change->type == 'cancel')
                                                        <span class="badge badge-danger">{{ $request_change->type }}</span>
                                                    @elseif ($request_change->type == 'submit')
                                                        <span class="badge badge-success">{{ $request_change->type }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($request_change->status == 'pending')
                                                        <span class="badge badge-warning">{{ $request_change->status }}</span>
                                                    @elseif ($request_change->status == 'accept')
                                                        <span class="badge badge-success">{{ $request_change->status }}</span>
                                                    @elseif ($request_change->status == 'decline')
                                                        <span class="badge badge-danger">{{ $request_change->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.request_changes.show', $request_change) }}"
                                                        title="{{ __('main.show') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.job_title') }}</th>
                                            <th>{{ __('attributes.freelancer') }}</th>
                                            <th>{{ __('attributes.new_bid') }}</th>
                                            <th>{{ __('attributes.new_duration') }}</th>
                                            <th>{{ __('attributes.type') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
                                            <th>{{ __('main.actions') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
@endsection
