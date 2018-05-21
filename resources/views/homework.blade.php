@extends('layouts.master')


@section('content')

    <!--MULTIPLE HOMEWORK PAGE-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Bord</a>
                </li>
                <li class="breadcrumb-item active">Teme {{ (is_teacher()) ? "publicate" : "la care te-ai abonat" }}</li>
            </ol>

            <div class="errors">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="input-group">
            <!--search for homework-->
            <input name="homework-search" class="form-control" type="text" placeholder="Cauta tema...">
            <span class="input-group-append">
                          <button data-toggle="collapse" data-target="#demo" class="btn btn-secondary">Filtru <i class="fa fa-filter"></i></button>

                          <button class="btn btn-primary" type="button">
                            <i class="fa fa-search"></i>
                          </button>
                      </span>
          </div>
          <div id="demo" class="collapse">
            <div class="card">
                <div class="card-body">
                    <form>
                        <h6>An:&nbsp; </h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="year-filter form-check-input" value="1" name="year-filter"> 1
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="year-filter form-check-input" value="2" name="year-filter"> 2
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="year-filter form-check-input" value="3" name="year-filter"> 3
                            </label>
                        </div>
                    </form>
                    <hr>
                    <form>
                        <h6>Semestru:&nbsp; </h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="semester-filter form-check-input" value="1" name="optradio"> 1
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="semester-filter form-check-input" value="2" name="optradio"> 2
                            </label>
                        </div>
                    </form>
                    <hr>
                    <form>
                        <h6>Teme:&nbsp; </h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="verified-filter form-check-input" value="0" name="optradio"> Necorectate
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="verified-filter form-check-input" value="1" name="optradio"> Corectate
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="verified-filter form-check-input" value="2" name="optradio"> Toate
                            </label>
                        </div>
                    </form>

            </div>
        </div>
  </div>
          
<br>
                    @if (is_teacher())
                        <!--press to create new homework <TEACHER>-->
                        <a href="{{ url('/homework/create') }}" class="btn btn-primary btn-lg btn-block">Tema noua</a>
                        <!--press to compare homework <TEACHER>-->
                        <a href="{{ url('/compare') }}" class="btn btn-secondary btn-lg btn-block">Compara</a>
                        <hr class="mt-2">
                    @endif

                    @if ($homeworks->count() > 0)
                    <div class="card-columns">
                        @foreach ($homeworks as $homework)
                            <!-- Example Homework Card-->
                            <div class="card mb-3">
                                <div class="card-header bg-transparent">
                                    @if ($homework->course)
                                    <a href="{{ url('/course/' . $homework->course->slug) }}">{{ $homework->course->course_title }}</a>
                                    @endif
                                </div>
                                <div class="card-body text">
                                    <!--Homework title-->
                                    <h5 class="card-title">{{ $homework->name }}</h5>
                                    <!--Homework description-->
                                    <p class="card-text">{{ $homework->description }}</p>
                                </div>

                                <!--Homework deadline-->
                                <div class="card-footer bg-transparent">Termen
                                    limita: {{ $homework->deadline }}</div>
                                <div class="card-footer bg-transparent">
                                    <!--go to homework page-->
                                    <a href="{{ url('/homework/' . $homework->slug) }}"
                                       class="btn btn-info">Detalii</a>
                                    <!--Homework edit <TEACHER>-->
                                    @if (Auth::check() and is_homework_author($homework))
                                    <a href="{{ url('/homework/' . $homework->slug . '/edit') }}"
                                       class="btn btn-secondary">Editeaza</a>
                                    @endif
                                    <style>
                                        .eticheta{
                                            display:inline-block;
                                            font-weight: 400;
                                            text-align:center;
                                            white-space:nowrap;
                                            vertical-align:middle;
                                            -moz-user-select:none;
                                            border:1px solid transparent;
                                            padding: .375rem .75rem;
                                            font-size:1rem;
                                            line-height:1.5;
                                            border-radius:.25rem;
                                            color:white;
                                        }
                                    </style>
                                    <div class="bg-danger eticheta">
                                        Necorectate 
                                    </div>
                                    <div class="bg-success eticheta">
                                        Corectate
                                    </div>
                                    <div class="bg-primary eticheta">
                                        Noi <span class="badge badge-light">9</span>
                                        <span class="sr-only">unread messages</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                    @else
                        <h4 class="text-center" >Nicio tema aici, incearca sa te abonezi la cateva <a href="{{ url('/course') }}">cursuri</a></h4>
                    @endif


                    <!--pagination
                    
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Inapoi</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">Inainte</a></li>
                    </ul>
                    --->
                </div>
            </div>
        </div>
    </div>

    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
@endsection