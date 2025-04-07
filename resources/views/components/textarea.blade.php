@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-pk focus:ring-pk rounded-md shadow-sm']) !!}>{{ $slot }}</textarea>