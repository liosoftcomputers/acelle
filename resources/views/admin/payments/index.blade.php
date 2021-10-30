@extends('layouts.backend')

@section('title', trans('messages.payment_gateways'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-credit-card2"></i> {{ trans('messages.payment_gateways') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="sub-section">
                <h2>{{ trans('messages.payment.all_available_gateways') }}</h2>
                <p>{{ trans('messages.payment.all_available_gateways.wording') }}</p>
                <div class="mc-list-setting mt-20">
                    @foreach ($gateways as $name => $gateway)
                        <div class="list-setting bg-{{ $gateway['name'] }}
                            {{ Acelle\Model\Setting::get('payment.' . $name) ? 'current' : '' }}">
                            <div class="list-setting-main">
                                <div class="title">
                                    <label>{{ trans('messages.payments.' . $name) }}</label>
                                </div>
                                <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                            </div>
                            <div class="list-setting-footer text-nowrap pl-4">
                                @if (Acelle\Model\Setting::get('payment.' . $name))
                                    @if (in_array($name, $enabledGateways))
                                        <span class="badge badge-large badge-success">
                                            {{ trans('messages.payment.active') }}
                                        </span>
                                        <a class="btn btn-mc_primary ml-5"
                                            link-method="post" href="{{ action('Admin\PaymentController@disable', $name) }}">
                                            {{ trans('messages.payment.disable') }}
                                        </a>
                                    @else
                                        <span class="badge badge-large badge-warning">
                                            {{ trans('messages.payment.inactive') }}
                                        </span>
                                        <a class="btn btn-mc_primary ml-5"
                                            link-method="post" href="{{ action('Admin\PaymentController@enable', $name) }}">
                                            {{ trans('messages.payment.enable') }}
                                        </a>
                                    @endif
                                    <a class="btn btn-mc_default ml-5" href="{{ action('Admin\PaymentController@edit', $name) }}">
                                        {{ trans('messages.payment.setting') }}
                                    </a>
                                @else
                                    <a class="btn btn-mc_primary ml-5" href="{{ action('Admin\PaymentController@edit', $name) }}">
                                        {{ trans('messages.payment.connect') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
