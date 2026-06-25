<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function getCurrentUser(Request $request)
    {
        return $this->returnSuccess(200, $request->user());
    }

    public function register(Request $request)
    {
        $errors = $this->validateFieldsFromInput($request->all(), 'register');
        if (count($errors) > 0) {
            return $this->returnFail(400, $errors[0]);
        }

        $input = $request->only(['name', 'email', 'password', 'phone']);

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'phone'    => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
        ]);

        event(new Registered($user));

        return $this->returnSuccess(201, [
            'message' => 'Usuario registrado exitosamente',
            'user'    => $user
        ]);
    }

    public function login(Request $request)
    {
        $errors = $this->validateFieldsFromInput($request->all(), 'login');
        if (count($errors) > 0) {
            return $this->returnFail(400, $errors[0]);
        }
        $input = $request->only(['email', 'password']);

        $user = User::where('email', $input['email'])->first();

        if (!$user || !Hash::check($input['password'], $user->password)) {
            return $this->returnFail(401, 'login.error_msg');
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->returnFail(403, 'login.unverified_email');
        }
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->returnSuccess(200, [
            'token'   => $token,
            'user'    => $user,
            'message' => 'Login exitoso'
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->bearerToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return $this->returnSuccess(200, ['message' => 'Sesión cerrada correctamente']);
    }

    private function validateFieldsFromInput(array $inputs, string $form = 'login'): array
    {
        [$rules, $messages] = $this->setDataToValidateByForm($form);

        $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all();
    }

    private function setDataToValidateByForm(string $form): array
    {
        if ($form === 'login') {
            $rules = [
                'email'    => ['required', 'email:rfc,dns'],
                'password' => ['required', 'min:6', 'regex:/^[^\'\";<>\/\\\\]+$/'],
            ];

            $messages = [
                'email.required'    => 'El correo es requerido.',
                'email.email'       => 'El correo no es válido.',
                'password.required' => 'La contraseña es requerida.',
                'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
                'password.regex'    => 'La contraseña contiene caracteres no permitidos (\' " ; < > / \\).',
            ];
        } else {
            $rules = [
                'name'     => ['required', 'min:2', 'max:100', 'regex:/^[\pL\s\-]+$/u'],

                'email'    => ['required', 'email:rfc,dns', 'unique:users,email'],
                'password' => [
                    'required',
                    'min:8',
                    'max:72',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[!@#%^&*()\-_+=\[\]{}|,.?~]/',
                    'regex:/^[A-Za-z0-9!@#%^&*()\-_+=\[\]{}|,.?~]+$/',
                ],
                'phone'    => ['required', 'min:7', 'max:20', 'regex:/^\+?[\d\s\-().]+$/'],
            ];

            $messages = [
                'name.required'     => 'El nombre completo es requerido.',
                'name.min'          => 'El nombre debe tener al menos 2 caracteres.',
                'name.regex'        => 'El nombre solo puede contener letras, espacios y guiones.',

                'email.required'    => 'El correo es requerido.',
                'email.email'       => 'El correo no es válido.',
                'email.unique'      => 'Este correo ya está registrado.',

                'password.required' => 'La contraseña es requerida.',
                'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
                'password.max'      => 'La contraseña es demasiado larga.',
                'password.regex'    => 'La contraseña debe incluir mayúsculas, minúsculas, un número y un símbolo válido (!@#%^&*…). No se permiten comillas, punto y coma ni barras.',

                'phone.required'    => 'El teléfono es requerido.',
                'phone.min'         => 'El teléfono debe tener al menos 7 dígitos.',
                'phone.regex'       => 'El teléfono solo puede contener dígitos, espacios, +, -, () y puntos.',
            ];
        }

        return [$rules, $messages];
    }
}
