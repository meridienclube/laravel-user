@extends('layouts.metronic')
@section('title', 'Pessoas')
@section('content')
  @include('partials.kt_subheader', [
    'breadcrumb' => [
      '#' => 'Lista de Pessoas'
    ],
    'buttons' => [
      route('users.create') => [
        'label' => 'Nova Pessoa',
        'icon' => 'fa fa-plus'
      ]
    ]
  ])
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
      <div class="col-md-12">
        @include('users.partials.filter')
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @include('users.partials.list')
      </div>
    </div>
  </div>
@endsection

@include('users.partials.kt_aside')
