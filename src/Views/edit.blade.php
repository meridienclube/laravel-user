@extends('layouts.metronic')
@section('title', 'Pessoas')
@section('content')

    @include('partials.kt_subheader', [
      'breadcrumb' => [
        route('users.index') => 'Lista de Pessoas',
        '#' => 'Editar Pessoa'
      ],
      'buttons' => [
        route('users.index') => [
          'label' => __('return'),
          'icon' => 'flaticon2-back'
        ]
      ]
    ])

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        {!! Form::model($user, ['route' => ['users.update', $user->id], 'files' => true, 'method' => 'put', 'class' => 'horizontal-form']) !!}
        @include('users.partials.form')
        {!! Form::close() !!}
    </div>

@endsection

@include('users.partials.kt_aside')
