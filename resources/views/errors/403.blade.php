{{-- resources/views/errors/403.blade.php --}}
@extends('errors.layout')

@section('title', 'Access Denied')
@section('code', '403')
@section('message_title', 'Restricted Area')
@section('message_description', 'You do not have authorization to view this section. Ensure you are signed in with the correct account category (Student, Landlord, or Driver).')