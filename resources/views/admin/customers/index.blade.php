@extends('layouts.admin')
@section('title', 'Data Customer')

@section('content')
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>WhatsApp</th><th>Total Pesanan</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->whatsapp }}</td>
                        <td>{{ $c->orders_count }}</td>
                        <td><a href="{{ route('admin.customers.show', $c) }}" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $customers->links() }}</div>
@endsection