@extends('layouts.master')


@section('content')

<div class="row">
  <div class="col-12">
    <form action="{{ url('/course/' . $course->slug . '/subscribe') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="card text-center">

        <div class="card-body">
          <!--Course title-->
          <h5 class="card-title">{{ $course->course_title }}</h5>
          <!--Course year-->
          <hr><p class="card-text">An: {{ $course->year }}</p>
          <!--Course semester-->
          <hr><p class="card-text">Semestru: {{ $course->semester }}</p>
          <!--Course teachers-->
          <hr>
          <p class="card-text">Profesori: {{ $teachers_string }}</p>
          <hr>
          <!--Course description-->
          <p class="card-text">Descriere: {{ $course->description }}</p><hr>

            <!--press to follow course-->
            @if (can_subscribe($course->id))
              <button type="submit" class="btn btn-primary">Aboneaza-te</button>
            @endif

            @if (is_course_teacher($course->id))
              <a href="{{ url('/course/' . $course->slug . '/edit') }}" class="btn btn-secondary">Editeaza</a>
            @endif

        </div>

        <!--date/time when posted-->
        <div class="card-footer text-muted">
          {{ $elapsed_time }}
        </div>

      </div>
    </form>
  </div>
</div>
@endsection