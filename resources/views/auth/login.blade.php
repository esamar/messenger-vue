@extends('layouts.app')

@section('content')

<b-conteiner>
    <b-row align-h="center">
        <b-col cols="8">

            <b-card title="Inicio de sesión" class="my-3">
            
            @if ($errors->any())
                <b-alert show variant="danger">
                    <ul>
                    @foreach ($errors ->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    </ul>

                </b-alert>
            @else
                <b-alert show>Por favor ingrese su contraseña</b-alert>
            @endif    

                <b-form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                    <b-form-group
                        label="Correo electrónico:"
                        label-for="email"
                        description="Nunca compartiremos tu email."
                    >
                        <b-form-input id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}" required autofocus
                            placeholder="ejemplo@dominio.com"
                        ></b-form-input>

                    </b-form-group>

                    <b-form-group 
                        label="Contraseña:"
                        label-for="password"
                    >
                        <b-form-input id="password"
                            name="password"
                            type="password"
                            value="{{ old('password') }}" required
                        ></b-form-input>

                    </b-form-group>

                    <b-form-group>
                        <b-form-checkbox name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Recordar sesión
                        </b-form-checkbox>
                    </b-form-group>

                    <b-button type="submit" variant="primary">Ingresar</b-button>
                    <b-button href="{{ route('password.request') }}" variant="link">¿Olvidaste tu contraseña?</b-button>
            
                    </b-form>
            
            </b-card>


                
        </b-col>
    </b-row>
</b-conteiner>

@endsection
