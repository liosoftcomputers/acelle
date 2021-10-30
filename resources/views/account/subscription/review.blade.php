@extends('layouts.frontend')

@section('title', trans('messages.subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="active">{{ trans('messages.subscription') }}</li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.plan.review') }}</span>
        </h1>
    </div>

@endsection

@section('content')
<form action="{{ action('AccountSubscriptionController@create', ['plan_uid' => $plan->uid]) }}" method="POST" class="proceed_with_payment">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <div class="sub-section mb-30">
                <p>{{ trans('messages.plan.review.wording') }}</p>
                
                <ul class="mc-inline-list">
                    <li>                        
                        <desc>{{ trans('messages.plan_name') }}</desc>
                        <value>{{ $plan->name }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.price') }}</desc>
                        <value>{{ Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}/{{ $plan->displayFrequencyTime() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.sending_total_quota_label') }}</desc>
                        <value>{{ $plan->displayTotalQuota() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_lists_label') }}</desc>
                        <value>{{ $plan->displayMaxList() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_subscribers_label') }}</desc>
                        <value>{{ $plan->displayMaxSubscriber() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_campaigns_label') }}</desc>
                        <value>{{ $plan->displayMaxCampaign() }}</value>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-1">{{ trans('messages.subscription.choose_payment_method') }}</h2>
            <p>{{ trans('messages.plan.review.wording') }}</p>
            <div class="sub-section mb-30 choose-payment-methods">      
                @foreach(Acelle\Model\Setting::getEnabledPaymentGateways() as $gateway)
                    <div class="d-flex align-items-center choose-payment choose-payment-{{ $gateway }}">
                        <div class="text-right pl-2 pr-2">
                            <div class="d-flex align-items-center form-group-mb-0">
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => $gateway, 'text' => ''],
                                    ],
                                ])
                                <div class="check"></div>
                            </div>
                        </div>
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.frontend_payment.' . $gateway) }}</h4>
                            <p class="mb-3">{{ trans('messages.frontend_payment.' . $gateway . '.desc') }}</p>
                        </div>                        
                    </div>
                @endforeach
            </div>
            <div class="sub-section">
                <div class="row">
                    <div class="col-md-4">
                        @if (!$plan->isFree())
                            <button link-method="POST"
                                class="btn btn-mc_primary">
                                    {{ trans('messages.payment.proceed_with_payment') }}
                            </button>
                        @else
                            <button link-method="POST"
                                class="btn btn-mc_primary">
                                    {{ trans('messages.subscription.get_started') }}
                            </button>
                        @endif
                    </div>
                    <div class="col-md-8">
                        {!! trans('messages.payment.agree_service_intro', ['plan' => $plan->name]) !!}
                    </div>
                </div>
            </div>            
        </div>
    </div>
</form>
<form class="" method="POST" action="{{ action('AccountSubscriptionController@abort') }}">
    {{ csrf_field() }}
    
    <a href="javascript:;" onclick="$(this).closest('form').submit()"
        class="text-muted mt-4"
        style="font-size: 13px; text-decoration: underline;color:#333"
    >{{ trans('messages.change_mind_cancel_subscription') }}</a>
</form>
    <script>
        $('.proceed_with_payment').on('submit', function(e) {
            if (!$('.choose-payment-methods>div [type=radio]:checked').length) {
                e.preventDefault();

                swal({
                    title: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                    text: "",
                    confirmButtonColor: "#00695C",
                    type: "error",
                    allowOutsideClick: true,
                    confirmButtonText: LANG_OK,
                    customClass: "swl-success",
                    html: true
                });
            }                
        });

        $('.choose-payment-methods>div').on('click', function() {
            $(this).find('[type=radio]').prop('checked', true);

            $('.choose-payment-methods>div').removeClass('current');
            $('.choose-payment-methods>div [type=radio]:checked').closest('.choose-payment').addClass('current');
        });

        $('.choose-payment-methods>div').removeClass('current');
        $('.choose-payment-methods>div [type=radio]:checked').closest('.choose-payment').addClass('current');        

        if ($('.choose-payment-methods>div [type=radio]').length == 1) {
            $('.choose-payment-methods>div').first().click();
        }
    </script>

@endsection