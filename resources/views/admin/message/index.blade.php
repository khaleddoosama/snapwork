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
                                            <th>{{ __('attributes.from') }}</th>
                                            <th>{{ __('attributes.to') }}</th>
                                            <th>{{ __('attributes.content') }}</th>
                                            <th>{{ __('attributes.sent_at') }}</th>
                                            <th>{{ __('attributes.read_at') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($messages as $message)
                                            <tr>
                                                <td>{{ $message->id }}</td>
                                                <td>{{ $message->sender->name }}</td>
                                                <td>{{ $message->receiver->name }}</td>
                                                <td>{{ $message->content }}</td>
                                                <td>{{ $message->sent_at }}</td>
                                                <td>{{ $message->read_at }}</td>
                                                <td>{{ $message->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.from') }}</th>
                                            <th>{{ __('attributes.to') }}</th>
                                            <th>{{ __('attributes.content') }}</th>
                                            <th>{{ __('attributes.sent_at') }}</th>
                                            <th>{{ __('attributes.read_at') }}</th>
                                            <th>{{ __('attributes.status') }}</th>
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
