@extends('admin.layout')
@section('title', Auth::user()->name.' | Panel de Administación SinSis')
@section('body')
  <!-- start section -->
  <section class="wow fadeIn main-admin-container">
    <header class="main-admin-header position-fixed">
       <a href={{ route('create-user') }} class="">+ Crear Nuevo Usuario</a> 
    </header>
    @if (Auth::user()->access_level == 3)    
     <!-- start tab style 01 section -->
     <section class="wow fadeIn">
        <div class="container tab-style2">            
            <div class="row">
                <div class="col-12">
                    <!-- start tab navigation -->
                    <ul class="nav nav-tabs alt-font text-uppercase text-small text-center font-weight-600 justify-content-center">
                        <li class="nav-item"><a class="nav-link active" href="#tab_sec1" data-toggle="tab">Administradores</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_sec2" data-toggle="tab">Clientes</a></li>                        
                    </ul>
                    <!-- end tab navigation -->
                </div>
            </div>                                
            <div class="tab-content">
                <!-- start tab content -->
                <div class="tab-pane med-text fade in active show" id="tab_sec1">
                    <div class="row align-items-center">
                        <div class="container">
                            @if (count($managers) > 0)                
                        <table class="table table-striped">
                            <thead class="thead">
                                <th scope="col">
                                    Nombre
                                </th>
                                <th scope="col">
                                    Correo
                                </th>
                                <th scope="col" class="text-center">
                                    Proyectos
                                </th>
                                <th scope="col" class="text-center">
                                    Estado
                                </th>
                                <th class="text-center">
                                    Fecha de registro
                                </th>
                            </thead>
                            @foreach ($managers as $manager)      
                            <!-- start features box item --> 
                                <div class="modal fade" id="mdal-{{ $manager->id }}" class="modalmanager" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title pl-2" id="exampleModalLongTitle">deshabilitar administrador</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        <span>¿Estas seguro de que deseas deshabilitar el administrador {{ $manager->name }}?</span><br>
                                        <span class="text-big"><strong> Una vez deshabilitado no se puede volver a habilitar</strong></span>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-cancel able-manager" data-md={{ $manager->id }}>Cancelar</button>
                                        <button type="button" class="btn btn-accept dissable-manager" data-mdl={{ $manager->id }}>Aceptar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <tr id="tr-{{ $manager->id }}" class="{{ $manager->status == 0 ? 'disable' : '' }}">
                                <td> <a href={{ route('user-projects',$manager->id) }}>{{ $manager->name }}</a></td>
                                <td>{{ $manager->email }}</td>
                                @php
                                    $manager_projects = DB::table('consultants_project')->where('user_id','=',$manager->id)->get();
                                @endphp
                                <td class="text-center">{{ count($manager_projects) }}</td>
                                <td class="text-center">
                                @csrf
                                    <input type="checkbox" name="chebox" class="mngr" id="chck-{{ $manager->id }}" data-manager={{ $manager->id }}  data-toggle="modal" data-target="#mdal-{{ $manager->id }}"{{ $manager->status == 1 ? 'checked' : '' }}/>
                                </td>
                                <td class="text-center">{{ date_format($manager->created_at,'d-m-Y') }}</td>
                            </tr>
                            <!-- end features box item -->
                            @endforeach
                        </table>
                        @else
                        <div class="text-center">
                            <h2><i class="far fa-frown"></i></h2>
                            <h2>No se han encontrado clientes.</h2>
                            <span>Puedes crear uno nuevo haciendo click en el boton de arriba.</span>
                        </div>
                        @endif
                    </div>
                    </div>
                </div>
                <!-- end tab content -->
                <!-- start tab content -->
                <div class="tab-pane fade in" id="tab_sec2">
                    <div class="row align-items-center">
                        <div class="container">
                            @if (count($clients) > 0)                
                        <table class="table table-striped">
                            <thead class="thead">
                                <th scope="col">
                                    Nombre
                                </th>
                                <th scope="col">
                                    Correo
                                </th>
                                <th scope="col">
                                    Empresa
                                </th>
                                <th scope="col" class="text-center">
                                    Estado
                                </th>
                                <th class="text-center">
                                    Fecha de registro
                                </th>
                            </thead>
                            @foreach ($clients as $client)      
                            <!-- start features box item -->
                            <div class="modal fade" id="mdal-{{ $client->id }}" class="modalclient" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title pl-2" id="exampleModalLongTitle">deshabilitar cliente</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        <span>¿Estas seguro de que deseas deshabilitar el cliente {{ $client->name }}?</span><br>
                                        <span class="text-big"><strong> Una vez deshabilitado no se puede volver a habilitar</strong></span>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-cancel jalaparo" data-md={{ $client->id }}>Cancelar</button> 
                                        <button type="button" class="btn btn-accept dissable-client" data-mdl={{ $client->id }}>Aceptar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <tr id="tr-{{ $client->id }}" class="{{ $client->status == 0 ? 'disable' : '' }}">
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>
                                    @foreach ($client->enterprises as $enterprise)
                                        {{ $enterprise->name }} <br>
                                    @endforeach 
                                </td>
                                <td class="text-center">
                                @csrf
                                <input type="checkbox" name="chebox" id="chck-{{ $client->id }}" class="clnt" data-client={{ $client->id }}  data-toggle="modal" data-target="#mdal-{{ $client->id }}"{{ $client->status == 1 ? 'checked' : '' }}/>
                                </td>
                                <td class="text-center">{{ date_format($client->created_at,'d-m-Y') }}</td>
                            </tr>
                            <!-- end features box item -->
                            @endforeach
                        </table>
                        @else
                        <div class="text-center">
                            <h2><i class="far fa-frown"></i></h2>
                            <h2>No se han encontrado clientes.</h2>
                            <span>Puedes crear uno nuevo haciendo click en el boton de arriba.</span>
                        </div>
                        @endif
                    </div>                        
                    </div>
                </div>
                <!-- end tab content -->              
            </div>       
        </div>
    </section>
    <!-- end tab style 01 section -->
    @else
    <div class="container projects-container">
        @if (count($clients) > 0)                
    <table class="table table-striped">
        <thead class="thead">
            <th scope="col">
                Nombre
            </th>
            <th scope="col">
                Correo
            </th>
            <th scope="col">
                Empresa
            </th>
            <th scope="col" class="text-center">
                Estado
            </th>
            <th class="text-center">
                Fecha de registro
            </th>
        </thead>
        @foreach ($clients as $client)      
        <!-- start features box item -->
        <tr id="tr-{{ $client->id }}" class="{{ $client->status == 0 ? 'disable' : '' }}">
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>
                @foreach ($client->enterprises as $enterprise)
                    {{ $enterprise->name }} <br>
                @endforeach 
            </td>
            <td class="text-center">
                <input type="checkbox" name="" id="" checked>
            </td>
            <td class="text-center">{{ date_format($client->created_at,'d-m-Y') }}</td>
        </tr>
        <!-- end features box item -->
        @endforeach
    </table>
    @else
    <div class="text-center">
        <h2><i class="far fa-frown"></i></h2>
        <h2>No se han encontrado clientes.</h2>
        <span>Puedes crear uno nuevo haciendo click en el boton de arriba.</span>
    </div>
    @endif
</div>    
@endif
</section>
@endsection