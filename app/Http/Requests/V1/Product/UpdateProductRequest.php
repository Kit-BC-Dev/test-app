<?php

namespace App\Http\Requests\V1\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\V1\Product\ProductRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
class UpdateProductRequest extends ProductRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $productId = $this->route('product');
        $product = Product::find($productId);
        return Gate::allows('update', $product);
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(),[
            'user_id' => '',
        ]);
    }
}
