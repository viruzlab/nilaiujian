<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-emerald-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all shadow-lg shadow-emerald-500/30']) }}>
    {{ $slot }}
</button>
