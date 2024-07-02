@extends('admin.master')
@section('title')
    {{ __('attributes.skills') }}
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Wrapper. Contains page content -->
        <!-- Content Header (Page header) -->
        <x-custom.header-page title="{{ __('attributes.skills') }}" />

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{-- @can('skill.create') --}}
                                <x-custom.create-button route="admin.skill.create"
                                    title="{{ __('buttons.create_skill') }}" />
                            {{-- @endcan --}}
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.name') }}</th>
                                            {{-- <th>{{ __('main.actions') }}</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($skills as $skill)
                                            <tr>
                                                <td>{{ $skill->id }}</td>
                                                <td>{{ $skill->name }}</td>
                                                {{-- <td> --}}
                                                    {{-- @can('skill.edit') --}}
                                                        {{-- <x-custom.edit-button route="admin.skill.edit"
                                                            id="{{ $skill->id }}" /> --}}
                                                    {{-- @endcan --}}

                                                    {{-- @can('skill.delete') --}}
                                                    {{-- <x-custom.delete-button route="admin.skill.destroy"
                                                        id="{{ $skill->id }}" /> --}}


                                                    {{-- @endcan --}}

                                                {{-- </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('attributes.name') }}</th>
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
