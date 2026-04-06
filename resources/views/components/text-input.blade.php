@props(['disabled' => false])
<style>
    input{
        background-color:gray;
        display: block;
        border-radius:5px;
        border:none;
        height:30px;
        width:100%;
    }
</style>
<input @disabled($disabled) {{ $attributes->merge([]) }}>
