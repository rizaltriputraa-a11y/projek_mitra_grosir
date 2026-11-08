<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminGoodsReceiptController extends Controller
{
    public function index()
    {
        $receipts = GoodsReceipt::with('product')
            ->latest('received_date')
            ->paginate(15);

        return view('admin.goods_receipts.index', compact('receipts'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.goods_receipts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'received_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($data) {

                $product = Product::whereKey($data['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                GoodsReceipt::create($data);

                $product->increment('stock', $data['quantity']);
            });

            return redirect()
                ->route('admin.goods-receipts.index')
                ->with('success', 'Barang masuk berhasil dicatat dan stok telah ditambahkan.');

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->with('error', 'Barang masuk gagal dicatat.')
                ->withInput();
        }
    }

    public function edit(GoodsReceipt $goods_receipt)
    {
        $products = Product::orderBy('name')->get();

        return view('admin.goods_receipts.edit', compact(
            'goods_receipt',
            'products'
        ));
    }

    public function update(Request $request, GoodsReceipt $goods_receipt)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'received_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($data, $goods_receipt) {

                $receipt = GoodsReceipt::whereKey($goods_receipt->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldProductId = $receipt->product_id;
                $oldQuantity = $receipt->quantity;
                $newProductId = $data['product_id'];
                $newQuantity = $data['quantity'];

                // Jika produk tidak berubah
                if ($oldProductId == $newProductId) {

                    $product = Product::whereKey($oldProductId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $difference = $newQuantity - $oldQuantity;

                    // Pastikan stok tidak menjadi negatif
                    if ($product->stock + $difference < 0) {
                        throw ValidationException::withMessages([
                            'quantity' => 'Jumlah barang tidak dapat dikurangi karena sebagian stok sudah terjual.',
                        ]);
                    }

                    $product->increment('stock', $difference);

                } else {

                    // Kunci kedua produk berdasarkan urutan ID
                    $productIds = [$oldProductId, $newProductId];
                    sort($productIds);

                    $products = Product::whereIn('id', $productIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    $oldProduct = $products->get($oldProductId);
                    $newProduct = $products->get($newProductId);

                    if (!$oldProduct || !$newProduct) {
                        throw ValidationException::withMessages([
                            'product_id' => 'Produk tidak ditemukan.',
                        ]);
                    }

                    // Periksa stok produk lama sebelum dikurangi
                    if ($oldProduct->stock < $oldQuantity) {
                        throw ValidationException::withMessages([
                            'product_id' => 'Barang masuk tidak dapat dipindahkan karena stok produk lama sudah terpakai.',
                        ]);
                    }

                    $oldProduct->decrement('stock', $oldQuantity);
                    $newProduct->increment('stock', $newQuantity);
                }

                $receipt->update($data);
            });

            return redirect()
                ->route('admin.goods-receipts.index')
                ->with('success', 'Catatan barang masuk berhasil diperbarui.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->with('error', 'Catatan barang masuk gagal diperbarui.')
                ->withInput();
        }
    }

    public function destroy(GoodsReceipt $goods_receipt)
    {
        try {
            DB::transaction(function () use ($goods_receipt) {

                $receipt = GoodsReceipt::whereKey($goods_receipt->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $product = Product::whereKey($receipt->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Jangan sampai stok menjadi negatif
                if ($product->stock < $receipt->quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Catatan tidak dapat dihapus karena sebagian barang sudah terjual.',
                    ]);
                }

                $product->decrement('stock', $receipt->quantity);

                $receipt->delete();
            });

            return back()->with(
                'success',
                'Catatan barang masuk berhasil dihapus dan stok telah disesuaikan.'
            );

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Catatan barang masuk gagal dihapus.'
            );
        }
    }
}
