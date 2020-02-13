@extends(config('cw_user.layout'))
@section('title', __('user::views.users'))
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                {!! Form::open(['route' => 'admin.users.store', 'files' => true, 'class' => 'horizontal-form']) !!}
                @include('user::users.partials.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection