@extends('layouts.master')

@section('content')
<!--USER PROFILE PAGE-->

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Profil</a>
      </li>
    </ol>

<div class="row">
    <div class="col-12">

        <div class="mb-0 mt-0">
            <i class="fa fa-archive"></i> Setari cont</div>
        <hr class="mt-0">
        <div class="card card-register mx-auto mt-4">
            <div class="card">
                <div class="card-body">
                    <form action="#">
                        <div class="form-group">
                            <label for="name">Nume:</label>
                            <input type="name" class="form-control" id="name" value="Patlagica Ionut" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">An:</label>
                            <input type="email" class="form-control" id="an" value="2" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Nr. matricol:</label>
                            <input type="email" class="form-control" id="nr_matr" value="37hfjshdf" readonly>
                        </div>
                        <label for="email">Adresa de mail:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" value="patlagica.ion@yahoo.com" readonly>
                            <span class="input-group-append">
                                <button data-toggle="collapse" data-target="#changeEmail" class="btn btn-primary">Schimba <i class="fa fa-eraser"></i></button>
                            </span>
                        </div>
                        <label for="password">Parola:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="pass" value="*******" readonly>
                            <span class="input-group-append">
                                <button data-toggle="collapse" data-target="#changePassword" class="btn btn-primary">Schimba <i class="fa fa-eraser"></i></button>
                            </span>
                        </div>
                        <div id="changePassword" class="collapse">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Parola veche:</label>
                                        <input type="name" class="form-control" id="oldPass" placeholder="Scrie vechea ta parola">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Parola noua:</label>
                                        <input type="name" class="form-control" id="newPass" placeholder="Scrie noua ta parola">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Salveaza</button>
                                </div>
                            </div>
                        </div>
                        <div id="changeEmail" class="collapse">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Adresa de mail veche:</label>
                                        <input type="name" class="form-control" id="oldMail" placeholder="Scrie vechea ta adresa de mail">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Adresa de mail noua:</label>
                                        <input type="name" class="form-control" id="newMail" placeholder="Scrie noua ta adresa de mail">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Salveaza</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</div>
</div>

  
@endsection