<?php $count = 0; ?>
@foreach ($contacts as $key => $contact)
    <?php $count ++; ?>
    @include('admin.customer_row')
@endforeach