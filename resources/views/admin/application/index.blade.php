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
                                            <th>{{ __('attributes.bid') }}</th>
                                            <th>{{ __('attributes.duration') }}</th>
                                            <th>{{ __('attributes.cover_letter') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
                                            <th>{{ __('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applications as $application)
                                            <tr>
                                                <td>{{ $application->id }}</td>
                                                <td><a href="{{ route('admin.jobs.show', $application->job) }}">{{ $application->job->title }}</a></td>
                                                <td>{{ $application->freelancer->name }}</td>
                                                <td>{{ $application->bid }}</td>
                                                <td>{{ $application->duration }}</td>
                                                <td>{!! Str::limit($application->cover_letter, 100) !!}
                                                <td>{{ $application->status }}</td>
                                                <td>
                                                    <a href="{{ route('admin.applications.show', $application) }}"
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
                                            <th>{{ __('attributes.bid') }}</th>
                                            <th>{{ __('attributes.duration') }}</th>
                                            <th>{{ __('attributes.cover_letter') }}</th>
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
