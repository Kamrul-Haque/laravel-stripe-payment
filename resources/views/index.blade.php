@extends('stripe-payments.layout')


@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p>Invalid Data:</p>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <br>
            @endif
            <h2 class="text-center">Stripe Payments</h2>
            <br>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Intend ID</th>
                            <th>User</th>
                            <th>Payment</th>
                            <th>Amount</th>
                            <th>Refunded</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stripePayments as $stripePayment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stripePayment->payment_intend_id }}</td>
                            <td>{{ $stripePayment->user_id ? $stripePayment->user->name : 'NULL' }}</td>
                            <td>{{ $stripePayment->date }}</td>
                            <td>£{{ $stripePayment->amount }}</td>
                            <td>{{ $stripePayment->refunded_amount ? '£'.$stripePayment->refunded_amount : 'NULL' }}</td>
                            <td>{{ $stripePayment->status }}</td>
                            <td style="width: 200px;">
                                @if($stripePayment->refunded_amount < $stripePayment->amount)
                                    <button type="button" class="btn btn-primary btn-block refund-button btn-sm" data-target="#refund-form{{ $stripePayment->id }}">Refund
                                    </button>
                                    <form action="{{ route('stripe-payments.refund', $stripePayment) }}" id="refund-form{{ $stripePayment->id }}" class="refund-form" method="post">
                                        @csrf
                                        <div class="d-flex">
                                            <button class="btn btn-danger btn-sm cancel-button" title="cancel" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                </svg>
                                            </button>
                                            <input type="number" name="amount" id="amount" step="0.01" min="1" style="width: 105px" class="form-control form-control-sm" max="{{ $stripePayment->amount }}" value="{{ old('amount') ?? $stripePayment->amount }}">
                                            <button type="submit" title="confirm" onclick="return confirm('Are you sure? You want to refund?')" class="btn btn-success btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                    @else
                                    No Actions Available
                                    @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No Records Found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                <div>{{ $stripePayments->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.refund-form').hide();

        $(document).on('click', '.refund-button', function() {
            $('.refund-form').hide();
            $('.refund-button').show();


            let form = $(this).data('target');

            $(this).hide();
            $(form).show();
        });

        $(document).on('click', '.cancel-button', function() {
            $('.refund-form').hide();
            $('.refund-button').show();
        });
    });

</script>
@endsection
