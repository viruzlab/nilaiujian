@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-4 py-3 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm outline-none transition-all']) }}>
