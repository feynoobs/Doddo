<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザーが作成でき、属性値とパスワードのハッシュ化を検証する
     */
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

    /**
     * fillable 属性に想定の項目が設定されていることを検証する
     */
    public function test_user_fillable_attributes(): void
    {
        $user = new User();
        
        $expectedFillable = ['name', 'email', 'password'];
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /**
     * hidden 属性に想定の項目が設定されていることを検証する
     */
    public function test_user_hidden_attributes(): void
    {
        $user = new User();
        
        $expectedHidden = ['password', 'remember_token'];
        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /**
     * 作成時にパスワードがハッシュ化され、元の値で保存されないことを検証する
     */
    public function test_password_is_hashed_when_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Password should be hashed, not the original value
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(strlen($user->password) > 20); // Hashed passwords are longer
    }

    /**
     * email_verified_at がデフォルトで null であることを検証する
     */
    public function test_email_verified_at_can_be_null(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // email_verified_at should be null by default
        $this->assertNull($user->email_verified_at);
    }
}
