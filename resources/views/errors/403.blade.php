@extends('errors::minimal')
<h1>bogo</h1>
@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
