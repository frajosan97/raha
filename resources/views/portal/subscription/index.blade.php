@extends('layouts.app')

@section('title', 'Subscriptions')

@section('content')

<div class="container py-5">
    <!-- Salutation -->
    <h2 class="main-color border-bottom-custom text-capitalize pb-3 mb-3">Subscriptions History</h2>

    <div class="table-responsive">
        <table id="subscriptions-table" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Plan</th>
                    <th>Billed Amount</th>
                    <th>Paid Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTable will populate this -->
            </tbody>
        </table>
    </div>

</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#subscriptions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('subscription.index') }}",
            columns: [{
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'plan',
                    name: 'plan'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'amount_paid',
                    name: 'amount_paid'
                },
                {
                    data: 'payment_method',
                    name: 'payment_method'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush