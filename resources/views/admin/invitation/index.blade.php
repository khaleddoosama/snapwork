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
                                            <th>{{ __('attributes.author') }}</th>
                                            <th>{{ __('attributes.to') }}</th>
                                            {{-- <th>{{ __('main.actions') }}</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invitations as $invitation)
                                            <tr>
                                                <td>{{ $invitation->id }}</td>
                                                <td><a
                                                        href="{{ route('admin.jobs.show', $invitation->job) }}">{{ $invitation->job->title }}</a>
                                                </td>
                                                <td>{{ $invitation->job->client->name }}</td>
                                                <td>{{ $invitation->freelancer->name }}</td>
                                                {{-- <td>
                                                    <a href="{{ route('admin.invitations.show', $invitation) }}"
                                                        title="{{ __('main.show') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.job_title') }}</th>
                                            <th>{{ __('attributes.author') }}</th>
                                            <th>{{ __('attributes.to') }}</th>
                                            {{-- <th>{{ __('main.actions') }}</th> --}}
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
