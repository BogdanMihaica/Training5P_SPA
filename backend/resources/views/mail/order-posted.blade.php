<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9fafb;
    }

    .order-header {
        font-size: 24px;
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .products-title {
        font-size: 20px;
        color: #4b5563;
        margin-bottom: 15px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .order-header-row {
        background-color: #f3f4f6;
    }

    .table-header {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        background-color: #f3f4f6;
    }

    .order-body {
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
    }

    .order-row:hover {
        background-color: #f3f4f6;
    }

    .table-data {
        padding: 12px 16px;
        text-align: left;
        font-size: 14px;
        color: #4b5563;
    }

    .product-image {
        max-height: 80px;
        border-radius: 8px;
    }

    .empty-cell {
        padding: 12px 16px;
    }

    .total-header {
        font-weight: 600;
        color: #4b5563;
        padding: 12px 16px;
        text-align: left;
    }
</style>
@php
    $grandTotal = 0;
    $products = $order->products;
@endphp
<div>
    <h2 class="order-header">{{ __('messages.new_order') . $username }}</h2>
    <h3 class="products-title">{{ __('messages.products_link') }}</h3>
    <table class="order-table">
        <thead class="order-header-row">
            <tr>
                <th scope="col" class="table-header">{{ __('messages.id') }}</th>
                <th scope="col" class="table-header">{{ __('messages.image') }}</th>
                <th scope="col" class="table-header">{{ __('messages.product_title') }}</th>
                <th scope="col" class="table-header">{{ __('messages.description') }}</th>
                <th scope="col" class="table-header">{{ __('messages.price') }}</th>
                <th scope="col" class="table-header">{{ __('messages.quantity') }}</th>
                <th scope="col" class="table-header">{{ __('messages.total') }}</th>
            </tr>
        </thead>
        <tbody class="order-body">
            @foreach ($products as $product)
                        <tr class="order-row">
                            <td class="table-data">{{ $product->getKey() }}</td>
                            <td class="table-data">
                                <img class="product-image" src="{{ getImageUrl($product) }}" alt="Product Image">
                            </td>
                            <td class="table-data">
                                {{ $product->title }}
                            </td>
                            <td class="table-data">
                                {{ $product->description }}
                            </td>
                            <td class="table-data">
                                {{ $product->price }}
                            </td>
                            <td class="table-data">
                                {{ $product->pivot->quantity }}
                            </td>
                            <td class="table-data">{{ $product->price * $product->pivot->quantity }}</td>
                            @php
                                $grandTotal += $product->price * $product->pivot->quantity;
                            @endphp
                        </tr>
            @endforeach
            <tr>
                <td colspan="5" class="empty-cell"></td>
                <td class="total-header">{{ __('messages.grand_total') }}</td>
                <td class="empty-cell">{{ $grandTotal }}</td>
            </tr>
        </tbody>
    </table>
</div>