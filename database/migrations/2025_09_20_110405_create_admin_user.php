<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration {
    public function up(): void
    {
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('twoje_haslo_admina'),
                'is_admin' => true,
            ]);
        }
    }

    public function down(): void
    {
        User::where('email', 'admin@example.com')->delete();
    }
};
