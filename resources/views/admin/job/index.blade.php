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
                                            <th>{{ __('attributes.title') }}</th>
                                            <th>{{ __('attributes.client') }}</th>
                                            <th>{{ __('attributes.specialization') }}</th>
                                            <th>{{ __('attributes.type') }}</th>
                                            <th>{{ __('attributes.location_type') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
                                            <th>{{ __('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobs as $job)
                                            <tr>
                                                <td>{{ $job->id }}</td>
                                                <td>{{ $job->title }}</td>
                                                <td>{{ $job->client->name }}</td>
                                                <td>{{ $job->specialization->name }}</td>
                                                <td>{{ $job->type ? 'open' : 'closed' }}</td>
                                                <td>{{ $job->location_type }}</td>
                                                <td>{{ $job->status }}</td>
                                                <td>
                                                    <a href="{{ route('admin.jobs.show', $job) }}"
                                                        class="btn btn-sm btn-info" title="{{ __('main.show') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($job->status == 'hide')
                                                        <form action="{{ route('admin.jobs.status', $job) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="show">
                                                            <button type="submit" title="Show"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.jobs.status', $job) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="hide">
                                                            <button type="submit" title="Hide"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fas fa-times"></i>
                                                        </form>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.title') }}</th>
                                            <th>{{ __('attributes.client') }}</th>
                                            <th>{{ __('attributes.specialization') }}</th>
                                            <th>{{ __('attributes.type') }}</th>
                                            <th>{{ __('attributes.location_type') }}</th>
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
