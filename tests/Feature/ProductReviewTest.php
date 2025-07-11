<?php

// tests/Feature/ProductReviewTest.php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        $this->order->orderItems()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price
        ]);
    }

    public function test_user_can_submit_review_for_completed_order()
    {
        $this->actingAs($this->user);
        
        Storage::fake('public');
        
        $response = $this->post(route('reviews.store', [
            'order' => $this->order,
            'product' => $this->product
        ]), [
            'rating' => 5,
            'comment' => 'Sản phẩm tuyệt vời!',
            'images' => [
                UploadedFile::fake()->image('review1.jpg'),
                UploadedFile::fake()->image('review2.jpg')
            ]
        ]);
        
        $response->assertRedirect(route('orders.show', $this->order))
            ->assertSessionHas('success');
        
        $this->assertDatabaseHas('product_reviews', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'order_id' => $this->order->id,
            'rating' => 5,
            'comment' => 'Sản phẩm tuyệt vời!'
        ]);
        
        $review = ProductReview::first();
        $this->assertCount(2, $review->images);
        Storage::disk('public')->assertExists($review->images[0]);
    }

    public function test_user_cannot_submit_multiple_reviews_for_same_product_in_order()
    {
        $this->actingAs($this->user);
        
        ProductReview::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'order_id' => $this->order->id
        ]);
        
        $response = $this->post(route('reviews.store', [
            'order' => $this->order,
            'product' => $this->product
        ]), [
            'rating' => 4,
            'comment' => 'Sản phẩm tốt'
        ]);
        
        $response->assertForbidden();
    }

    public function test_admin_receives_notification_when_new_review_submitted()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        $this->actingAs($this->user);
        
        $this->post(route('reviews.store', [
            'order' => $this->order,
            'product' => $this->product
        ]), [
            'rating' => 5,
            'comment' => 'Sản phẩm tuyệt vời!'
        ]);
        
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'type' => 'App\Notifications\NewProductReview'
        ]);
    }
}
