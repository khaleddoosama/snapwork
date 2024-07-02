  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ route('admin.home') }}" class="brand-link">
          <img src="{{ asset('asset/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
              class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">{{ __('main.dashboard') }}</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="pb-3 mt-3 mb-3 user-panel d-flex">
              <div class="image">
                  <img src="{{ asset('asset/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                      alt="User Image">
              </div>
              <div class="info">
                  <a href="#" class="d-block">{{ Auth::user()->name }}</a>
              </div>
          </div>

          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                      aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  @can('dashboard.list')
                      {{--  home  --}}
                      <li class="nav-item">
                          <a href="{{ route('admin.home') }}"
                              class="nav-link @if (Request::is('*/admin')) active @endif">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  {{ __('main.dashboard') }}
                              </p>
                          </a>

                      </li>
                  @endcan

                  {{--  clients  --}}
                  {{-- @can('user.list') --}}
                  <li class="nav-item @if (Request::is('*/admin/clients') ||
                          Request::is('*/admin/clients/*') ||
                          Request::is('*/admin/freelancers') ||
                          Request::is('*/admin/freelancers/*')) menu-open @endif">
                      <a href="#" class="nav-link  @if (Request::is('*/admin/clients') ||
                              Request::is('*/admin/clients/*') ||
                              Request::is('*/admin/freelancers') ||
                              Request::is('*/admin/freelancers/*')) active @endif">
                          <i class="nav-icon fas fa-users"></i>
                          <p>
                              {{ __('attributes.users') }}
                          </p>
                      </a>
                      <ul class="nav nav-treeview" style=" @if (
                          !(Request::is('*/admin/clients') ||
                              Request::is('*/admin/clients/*') ||
                              Request::is('*/admin/freelancers') ||
                              Request::is('*/admin/freelancers/*')
                          )) display: none @endif">
                          <li class="nav-item">
                              <a href="{{ route('admin.user.clients') }}"
                                  class="nav-link @if (Request::is('*/admin/clients') || Request::is('*/admin/clients/*')) active @endif">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>
                                      {{ __('attributes.clients') }}
                                  </p>
                              </a>
                          </li>

                          <li class="nav-item">
                              <a href="{{ route('admin.user.freelancers') }}"
                                  class="nav-link @if (Request::is('*/admin/freelancers') || Request::is('*/admin/freelancers/*')) active @endif">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>
                                      {{ __('attributes.freelancers') }}
                                  </p>
                              </a>
                          </li>

                      </ul>
                  </li>
                  {{-- @endcan --}}

                  {{--  specializations  --}}
                  {{-- @can('specialization.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.specialization.index') }}"
                          class="nav-link @if (Request::is('*/admin/specialization') || Request::is('*/admin/specialization/*')) active @endif">
                          <i class="nav-icon fas fa-cogs"></i>
                          <p>
                              {{ __('attributes.specializations') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}


                  {{-- jobs --}}
                  {{-- @can('job.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.jobs.index') }}"
                          class="nav-link @if (Request::is('*/admin/jobs') || Request::is('*/admin/jobs/*')) active @endif">
                          <i class="nav-icon fas fa-briefcase"></i>
                          <p>
                              {{ __('attributes.jobs') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- applications --}}
                  {{-- @can('application.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.applications.index') }}"
                          class="nav-link @if (Request::is('*/admin/applications') || Request::is('*/admin/applications/*')) active @endif">
                          <i class="nav-icon fas fa-envelope"></i>
                          <p>
                              {{ __('attributes.applications') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- RequestsChange --}}
                  {{-- @can('request_change.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.request_changes.index') }}"
                          class="nav-link @if (Request::is('*/admin/request_changes') || Request::is('*/admin/request_changes/*')) active @endif">
                          <i class="nav-icon fas fa-exchange-alt"></i>
                          <p>
                              {{ __('attributes.request_changes') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- rates --}}
                  {{-- @can('rate.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.rates.index') }}"
                          class="nav-link @if (Request::is('*/admin/rates') || Request::is('*/admin/rates/*')) active @endif">
                          <i class="nav-icon fas fa-star"></i>
                          <p>
                              {{ __('attributes.rates') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- invitations --}}
                  {{-- @can('invitation.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.invitations.index') }}"
                          class="nav-link @if (Request::is('*/admin/invitations') || Request::is('*/admin/invitations/*')) active @endif">
                          <i class="nav-icon fas fa-user-plus"></i>
                          <p>
                              {{ __('attributes.invitations') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- messages --}}
                  {{-- @can('message.list') --}}
                  <li class="nav-item">
                      <a href="{{ route('admin.messages.index') }}"
                          class="nav-link @if (Request::is('*/admin/messages') || Request::is('*/admin/messages/*')) active @endif">
                          <i class="nav-icon fas fa-comments"></i>
                          <p>
                              {{ __('attributes.messages') }}
                          </p>
                      </a>
                  </li>
                  {{-- @endcan --}}

                  {{-- Permissions --}}
                  {{-- @can('permission.list')
                      <li class="nav-item">
                          <a href="{{ route('admin.permission.index') }}"
                              class="nav-link @if (Request::is('*/admin/permission') || Request::is('*/admin/permission/*')) active @endif">
                              <span class="icon nav-icon"><ion-icon name="key-outline"></ion-icon></span>
                              <p>
                                  {{ __('attributes.permissions') }}
                              </p>
                          </a>
                      </li>
                  @endcan --}}

                  {{-- Roles --}}
                  {{-- @can('role.list')
                      <li class="nav-item">
                          <a href="{{ route('admin.role.index') }}"
                              class="nav-link @if (Request::is('*/admin/role') || Request::is('*/admin/role/*')) active @endif">
                              <span class="icon nav-icon"><ion-icon name="people-outline"></ion-icon></span>
                              <span class="title">{{ __('attributes.roles') }}</span>
                          </a>
                      </li>
                  @endcan --}}

                  {{-- Roles In Permission --}}
                  {{-- @can('role_permission.list')
                      <li class="nav-item">
                          <a href="{{ route('admin.role_permission.index') }}"
                              class="nav-link @if (Request::is('*/admin/role_permission') || Request::is('*/admin/role_permission/*')) active @endif">
                              <span class="icon nav-icon"><ion-icon name="people-outline"></ion-icon></span>
                              <span class="title">{{ __('attributes.roles_in_permission') }}</span>
                          </a>
                      </li>
                  @endcan --}}

                  {{-- Admins --}}
                  {{-- @can('admin.list')
                      <li class="nav-item">
                          <a href="{{ route('admin.all_admin.index') }}"
                              class="nav-link @if (Request::is('*/admin/all_admin') || Request::is('*/admin/all_admin/*')) active @endif">
                              <span class="icon nav-icon"><ion-icon name="people-outline"></ion-icon></span>
                              <span class="title">{{ __('attributes.admin_manage') }}</span>
                          </a>
                      </li>
                  @endcan --}}
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
