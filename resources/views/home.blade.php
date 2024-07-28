@extends('layouts.app')

@section('titulo')
    Feed
@endsection

@section('contenido')
    
    <x-listar-post :posts="$posts" />

@endsection