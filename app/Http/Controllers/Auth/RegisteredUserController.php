<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender'   => ['nullable', 'in:male,female,non_binary,prefer_not_to_say'],
            'latitud'  => ['nullable', 'numeric'],
            'longitud' => ['nullable', 'numeric'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'gender'   => $request->gender ?? 'prefer_not_to_say',
        ]);

        // Sincronizar en la tabla 'usuarios' de la base 'sukha'
        if (!app()->environment('testing')) {
            try {
                require_once base_path('conexion.php');
                $pdoSukha = obtenerConexion();
                $stmtSukha = $pdoSukha->prepare("
                    INSERT INTO usuarios (nombre, email, password, latitud, longitud, creado_en)
                    VALUES (?, ?, ?, ?, ?, NOW())
                    ON DUPLICATE KEY UPDATE password = VALUES(password), latitud = VALUES(latitud), longitud = VALUES(longitud)
                ");
                $stmtSukha->execute([
                    $user->name,
                    $user->email,
                    $user->password,
                    $request->filled('latitud') ? (float)$request->latitud : null,
                    $request->filled('longitud') ? (float)$request->longitud : null,
                ]);
            } catch (\Throwable $e) {
                \Log::warning("No se pudo sincronizar usuario en tabla sukha.usuarios: " . $e->getMessage());
            }
        }

        // Envío de correo de bienvenida con PHPMailer (omite conexión de red en pruebas unitarias)
        if (!app()->environment('testing')) {
            try {
                require_once base_path('mailer.php');
                enviarCorreoBienvenida($user->email, $user->name);
            } catch (\Throwable $e) {
                \Log::warning("No se pudo enviar correo de bienvenida con PHPMailer: " . $e->getMessage());
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
