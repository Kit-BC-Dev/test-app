<?php

namespace App\Pipelines\V1\Order;

use Closure;

class MarkOrderConfirmed
{
    public function handle(array $data, Closure $next): array
    {
        $data['order']->update(['status' => 'confirmed']);

        $data['order'] = $data['order']->fresh();

        return $next($data);
    }
}

