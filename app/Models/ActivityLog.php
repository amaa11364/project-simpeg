<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    protected $table = 'activity_logs';
    protected $fillable = ['user_id', 'user_name', 'action', 'model', 'model_id', 'description', 'ip_address'];

    public static function record(string $action, string $description, ?string $model = null, ?int $modelId = null): void {
        $user = auth()->user();
        static::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
