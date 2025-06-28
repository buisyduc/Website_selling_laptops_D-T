namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;

class OrderReturn extends Model
{
    protected $fillable = ['user_id', 'order_id', 'reason', 'note', 'image', 'status'];

    // Nếu không có created_at và updated_at trong bảng
    // public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
