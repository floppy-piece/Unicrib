{{-- resources/views/errors/500.blade.php --}}
@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message_title', 'Internal Server Error')
@section('message_description', 'UniCrib encountered an unexpected error while processing your request. Our technical team has been automatically logged. Please try again in a few moments.')