@if(auth()->check() && auth()->user()->is_super_admin)
<div class="relative inline-block text-left" x-data="{ open: false }">
    <div>
        <button @click="open = !open" type="button"
                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                id="company-switcher" aria-expanded="true" aria-haspopup="true">
            <svg class="mr-2 h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.997 2.5a1.5 1.5 0 0 1 1.5 1.5v2.5h2.5a1.5 1.5 0 0 1 0 3h-2.5v2.5a1.5 1.5 0 0 1-3 0v-2.5h-2.5a1.5 1.5 0 0 1 0-3h2.5v-2.5a1.5 1.5 0 0 1 1.5-1.5z" clip-rule="evenodd"/>
            </svg>
            Super Admin:
            @if(Spatie\Multitenancy\Models\Tenant::checkCurrent())
                {{ Spatie\Multitenancy\Models\Tenant::current()->name ?? 'All Companies' }}
            @else
                All Companies
            @endif
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" @click.away="open = false"
         class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
         role="menu" aria-orientation="vertical" aria-labelledby="company-switcher" tabindex="-1">
        <div class="py-1" role="none">
            <!-- View All Companies Option -->
            <a href="{{ request()->url() }}"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !Spatie\Multitenancy\Models\Tenant::checkCurrent() ? 'bg-gray-50 font-semibold' : '' }}"
               role="menuitem">
                <span class="flex items-center">
                    <svg class="mr-2 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    View All Companies
                </span>
            </a>

            <div class="border-t border-gray-100"></div>

            <!-- Company List -->
            @foreach(\App\Models\Company::all() as $company)
                <a href="{{ request()->fullUrlWithQuery(['switch_company' => $company->id]) }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ Spatie\Multitenancy\Models\Tenant::checkCurrent() && Spatie\Multitenancy\Models\Tenant::current()->id == $company->id ? 'bg-blue-50 font-semibold text-blue-700' : '' }}"
                   role="menuitem">
                    <span class="flex items-center">
                        <svg class="mr-2 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 104 0 2 2 0 00-4 0zm6 0a2 2 0 104 0 2 2 0 00-4 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $company->name }}
                        <span class="ml-2 text-xs text-gray-500">({{ $company->domain }})</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
