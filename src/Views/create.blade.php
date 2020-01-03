@extends('layouts.metronic')
@section('title', __('users'))
@section('content')

    @include('partials.kt_subheader', [
      'breadcrumb' => [
        route('users.index') => __('users.list')
      ],
      [
        '#' => __('back')
      ],
      'buttons' => [
            route('users.index') => [
              'label' => __('return'),
              'icon' => 'flaticon2-back'
            ]
          ]
    ])

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        {!! Form::open(['route' => 'users.store', 'class' => 'horizontal-form']) !!}
        @include('users.partials.form')
        {!! Form::close() !!}
    </div>

@endsection

@include('users.partials.kt_aside')

@push('styles')
    <link href="{{ asset('assets/css/pages/users/wizard-4.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')

@endpush
