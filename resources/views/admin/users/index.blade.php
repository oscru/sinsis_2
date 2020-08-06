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
                            <tr>
                                <td> <a href={{ route('user-projects',$manager->id) }}>{{ $manager->name }}</a></td>
                                <td>{{ $manager->email }}</td>
                                @php
                                    $manager_projects = DB::table('consultants_project')->where('user_id','=',$manager->id)->get();
                                @endphp
                                <td class="text-center">{{ count($manager_projects) }}</td>
                                <td class="text-center">
                                    <input type="checkbox" name="" id="" checked>
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
                            <tr>
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
        <tr>
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