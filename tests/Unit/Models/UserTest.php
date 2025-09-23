<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNotEquals('password123', $user->password); // Password should be hashed
    }

    public function test_user_fillable_attributes(): void
    {
        $user = new User();
        
        $expectedFillable = ['name', 'email', 'password'];
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    public function test_user_hidden_attributes(): void
    {
        $user = new User();
        
        $expectedHidden = ['password', 'remember_token'];
        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    public function test_password_is_hashed_when_casted(): void
    {
        $user = new User();
        $casts = $user->casts();
        
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('hashed', $casts['password']);
    }

    public function test_email_verified_at_is_datetime_casted(): void
    {
        $user = new User();
        $casts = $user->casts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
    }
}
