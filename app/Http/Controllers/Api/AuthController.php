<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Solo llegamos aquí si la validación pasó
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return $this->returnSuccess(201, [
            'message' => 'Usuario registrado exitosamente',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $validated = $this->validateFieldsFromInput($request->all()) ;
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
        
        $validated = $request->validated();
        
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return $this->returnFail(401, 'Las credenciales proporcionadas son incorrectas.');
        }

        $user = User::where('email', $validated['email'])->firstOrFail();

        if ($request->has('device_name')) {
            $token = $user->createToken($validated['device_name'])->plainTextToken;
            return $this->returnSuccess(200, [
                'token' => $token,
                'user' => $user
            ]);
        }

        return $this->returnSuccess(200, [
            'message' => 'Login exitoso vía cookie',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->bearerToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->returnSuccess(200, ['message' => 'Sesión cerrada correctamente']);
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            'email'         => ['required', 'email', 'unique:users'],
            'password'      => ['required'],


        ];
        $messages = [
            'email.required'        => 'Email es requerido.',
            'email.email'           => 'Email no valido.',
            'password.regex'     => 'Contraseña es requerida.',
        ];

         $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

    }
}