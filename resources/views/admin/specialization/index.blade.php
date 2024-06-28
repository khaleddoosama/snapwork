@extends('admin.master')
@section('title')
    {{ __('attributes.specializations') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Wrapper. Contains page content -->
        <!-- Content Header (Page header) -->
        <x-custom.header-page title="{{ __('attributes.specializations') }}" />

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{-- @can('specialization.create') --}}
                                <x-custom.create-button route="admin.specialization.create"
                                    title="{{ __('buttons.create_specialization') }}" />
                            {{-- @endcan --}}
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.name') }}</th>
                                            <th>{{ __('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($specializations as $specialization)
                                            <tr>
                                                <td>{{ $specialization->id }}</td>
                                                <td>{{ $specialization->name }}</td>
                                                <td>
                                                    {{-- @can('specialization.edit') --}}
                                                        <x-custom.edit-button route="admin.specialization.edit"
                                                            id="{{ $specialization->id }}" />
                                                    {{-- @endcan --}}

                                                    {{-- @can('specialization.delete') --}}
                                                    <x-custom.delete-button route="admin.specialization.destroy"
                                                        id="{{ $specialization->id }}" />


                                                    {{-- @endcan --}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.name') }}</th>
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
