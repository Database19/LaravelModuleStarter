@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Akuntansi</h1>
            <p class="text-lg text-gray-500 mt-1">
                Petakan akun default untuk otomatisasi jurnal di seluruh sistem.
            </p>
        </div>

        {{-- Business Type Selection Card --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8 border border-blue-200">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-2 0h-2m-2 0h-4m-2 0H3m11-10V9a2 2 0 00-2-2H8a2 2 0 00-2 2v2m8 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v2m0 6h4" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-900">Pilih Bidang Usaha</h3>
                    <p class="text-blue-700 text-sm">Pilih bidang usaha untuk menampilkan akun yang relevan dengan bisnis Anda</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="business_type_selector" class="block text-sm font-medium text-blue-900 mb-2">
                        Bidang Usaha <span class="text-red-500">*</span>
                    </label>
                    <select id="business_type_selector"
                            class="w-full px-4 py-3 border border-blue-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Memuat...</option>
                    </select>
                    <small class="text-blue-600 mt-1 block">Akun akan difilter sesuai dengan bidang usaha yang dipilih</small>
                </div>
                <div>
                    <button type="button" id="applyBusinessTypeFilter"
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </div>

            {{-- Current Selection Indicator --}}
            <div id="currentBusinessTypeIndicator" class="mt-4 hidden">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span id="selectedBusinessTypeName">Semua Bidang Usaha</span>
                </div>
            </div>
        </div>

        <form action="{{ route('accounting.settings.store') }}" method="POST">
            @csrf
            <div class="space-y-8" id="settingsContainer">
                {{-- Loading Indicator --}}
                <div id="loadingIndicator" class="hidden">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-600">Memuat akun untuk bidang usaha yang dipilih...</span>
                        </div>
                    </div>
                </div>

                {{-- Settings Groups --}}
                <div id="settingsContent">
                    {{-- Loop melalui setiap grup pengaturan --}}
                    @foreach($settingKeys as $groupName => $groupSettings)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $groupName }}
                                </h3>
                            </div>
                            <div class="p-6 space-y-6">
                                {{-- Loop melalui setiap item di dalam grup --}}
                                @foreach($groupSettings as $key => $label)
                                    <div class="space-y-2">
                                        <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
                                            {{ $label }}
                                            <span class="text-gray-500 text-xs">({{ $key }})</span>
                                        </label>
                                        <select name="settings[{{ $key }}]"
                                                id="account_selector_{{ $key }}"
                                                class="account-selector mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg shadow-sm">
                                            <option value="">-- Tidak Diatur --</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                        data-business-type="{{ $account->business_type ?? 'all' }}"
                                                        data-account-type="{{ $account->type }}"
                                                        @if(($settings[$key] ?? null) == $account->id) selected @endif>
                                                    ({{ $account->account_code }}) {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500">Pilih akun yang akan digunakan untuk {{ strtolower($label) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" id="resetSettings"
                        class="inline-flex justify-center py-3 px-8 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
                <button type="submit"
                        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Accounting Settings Manager
 * Handles business type selection and account auto-selection
 */
class AccountingSettingsManager {
    constructor() {
        this.config = {
            selectors: {
                businessTypeSelector: '#business_type_selector',
                applyFilterBtn: '#applyBusinessTypeFilter',
                resetBtn: '#resetSettings',
                loadingIndicator: '#loadingIndicator',
                settingsContent: '#settingsContent',
                currentIndicator: '#currentBusinessTypeIndicator',
                selectedBusinessTypeName: '#selectedBusinessTypeName',
                accountSelectors: '.account-selector',
                form: 'form'
            },
            routes: {
                getAccounts: '{{ route("accounting.settings.test.accounts-for-business") }}',
                getBusinessTypes: '{{ route("accounting.settings.test.business-types") }}',
                getAccountRecommendations: '{{ route("accounting.settings.test.account-recommendations") }}'
            },
            storage: {
                businessTypeKey: 'selected_business_type'
            },
            ui: {
                highlightColor: '#fef3c7',
                highlightDuration: 2000
            }
        };

        this.elements = {};
        this.tomSelectInstances = {};
        this.businessTypes = [];
        this.businessTypeNames = {};
        this.accountRecommendations = {}; // Cache for recommendations
        this.savedSelections = {}; // Store user selections

        this.init();
    }

    /**
     * Initialize the manager
     */
    async init() {
        this.cacheElements();
        this.saveInitialSelections(); // Save existing selected values
        await this.loadBusinessTypes();
        this.bindEvents();
        this.initializeTomSelect();
        this.loadSavedBusinessType();
    }

    /**
     * Save initial selections from the form
     */
    saveInitialSelections() {
        this.elements.accountSelectors.forEach(selector => {
            if (selector.value && selector.value !== '') {
                const settingKey = selector.name.replace('settings[', '').replace(']', '');
                this.savedSelections[settingKey] = selector.value;
            }
        });
    }

    /**
     * Load business types from database
     */
    async loadBusinessTypes() {
        try {
            const response = await fetch(this.config.routes.getBusinessTypes, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken(),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success && data.business_types) {
                this.businessTypes = data.business_types;
                this.populateBusinessTypeDropdown();
            } else {
                this.loadFallbackBusinessTypes();
            }
        } catch (error) {
            console.error('Error loading business types:', error);
            this.loadFallbackBusinessTypes();
        }
    }

    /**
     * Load fallback business types if API fails
     */
    loadFallbackBusinessTypes() {
        this.businessTypes = [
            { value: 'all', label: 'Semua Bidang Usaha', icon: 'fas fa-globe', color: '#6B7280' },
            { value: 'trading', label: 'Perusahaan Dagang', icon: 'fas fa-shopping-cart', color: '#3B82F6' },
            { value: 'service', label: 'Perusahaan Jasa', icon: 'fas fa-hands-helping', color: '#10B981' }
        ];
        this.populateBusinessTypeDropdown();
    }

    /**
     * Populate business type dropdown
     */
    populateBusinessTypeDropdown() {
        if (!this.elements.businessTypeSelector) return;

        // Clear existing options
        this.elements.businessTypeSelector.innerHTML = '';

        // Add new options
        this.businessTypes.forEach(businessType => {
            const option = document.createElement('option');
            option.value = businessType.value;
            option.textContent = businessType.label;
            this.elements.businessTypeSelector.appendChild(option);

            // Build business type names mapping
            this.businessTypeNames[businessType.value] = businessType.label;
        });

        // Set default value
        this.elements.businessTypeSelector.value = 'all';
    }

    /**
     * Cache DOM elements
     */
    cacheElements() {
        Object.keys(this.config.selectors).forEach(key => {
            const selector = this.config.selectors[key];
            if (key === 'accountSelectors') {
                this.elements[key] = document.querySelectorAll(selector);
            } else {
                this.elements[key] = document.querySelector(selector);
            }
        });
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        if (this.elements.applyFilterBtn) {
            this.elements.applyFilterBtn.addEventListener('click', () => {
                const selectedType = this.elements.businessTypeSelector.value;
                this.applyBusinessTypeFilter(selectedType);
            });
        }

        if (this.elements.businessTypeSelector) {
            this.elements.businessTypeSelector.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.elements.applyFilterBtn.click();
                }
            });

            this.elements.businessTypeSelector.addEventListener('change', () => {
                this.saveBusinessTypeToStorage();
            });
        }

        if (this.elements.resetBtn) {
            this.elements.resetBtn.addEventListener('click', () => {
                this.resetSettings();
            });
        }

        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => {
                this.handleFormSubmission(e);
            });
        }

        // Add change listeners to account selectors to save selections
        this.elements.accountSelectors.forEach(selector => {
            selector.addEventListener('change', () => {
                const settingKey = selector.name.replace('settings[', '').replace(']', '');
                if (selector.value && selector.value !== '') {
                    this.savedSelections[settingKey] = selector.value;
                } else {
                    delete this.savedSelections[settingKey];
                }
            });
        });
    }

    /**
     * Initialize TomSelect for all account selectors
     */
    initializeTomSelect() {
        if (typeof TomSelect === 'undefined') {
            console.warn('TomSelect is not loaded. Using basic select elements.');
            return;
        }

        this.elements.accountSelectors.forEach((element) => {
            if (this.tomSelectInstances[element.id]) {
                this.tomSelectInstances[element.id].destroy();
            }

            try {
                this.tomSelectInstances[element.id] = new TomSelect(element, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "-- Tidak Diatur --",
                    allowEmptyOption: true,
                    render: {
                        option: function(data, escape) {
                            return '<div class="py-2">' +
                                   '<div class="font-medium">' + escape(data.text) + '</div>' +
                                   '</div>';
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing TomSelect for element:', element.id, error);
            }
        });
    }

    /**
     * Apply business type filter and auto-select accounts
     */
    async applyBusinessTypeFilter(selectedType) {
        try {
            this.showLoading();

            // Fetch accounts and recommendations in parallel
            const [accounts, recommendations] = await Promise.all([
                this.fetchAccountsForBusinessType(selectedType),
                this.fetchAllAccountRecommendations(selectedType)
            ]);

            // Cache recommendations for this business type
            this.accountRecommendations[selectedType] = recommendations;

            this.updateAccountSelectors(accounts, selectedType);
            this.updateBusinessTypeIndicator(selectedType);
            this.initializeTomSelect();

            this.hideLoading();
            this.showSuccessMessage(`Menampilkan ${accounts.length} akun untuk: ${this.businessTypeNames[selectedType]}`);

        } catch (error) {
            this.hideLoading();
            this.showErrorMessage('Gagal memuat data akun: ' + error.message);
            console.error('Error applying business type filter:', error);
        }
    }

    /**
     * Fetch all account recommendations for business type
     */
    async fetchAllAccountRecommendations(businessType) {
        try {
            const url = `${this.config.routes.getAccountRecommendations}?business_type=${businessType}`;

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();

            if (data.success && data.recommendations) {
                return data.recommendations;
            }

            return {};
        } catch (error) {
            console.error('Error fetching account recommendations:', error);
            return {};
        }
    }

    /**
     * Fetch accounts for specific business type
     */
    async fetchAccountsForBusinessType(businessType) {
        const url = `${this.config.routes.getAccounts}?business_type=${businessType}`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCSRFToken()
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Gagal memuat data akun');
        }

        return data.accounts;
    }

    /**
     * Update account selectors with new data
     */
    updateAccountSelectors(accounts, businessType) {
        this.elements.accountSelectors.forEach(selector => {
            const settingKey = selector.name.replace('settings[', '').replace(']', '');
            const currentValue = selector.value;

            // Save current selection if user has made a choice
            if (currentValue && currentValue !== '') {
                this.savedSelections[settingKey] = currentValue;
            }

            // Clear existing options and add default option
            selector.innerHTML = '<option value="">-- Tidak Diatur --</option>';

            // Add new options
            accounts.forEach(account => {
                const option = document.createElement('option');
                option.value = account.id;
                option.textContent = account.display_text;
                option.setAttribute('data-business-type', account.business_type);
                option.setAttribute('data-account-type', account.type);

                selector.appendChild(option);
            });

            // Restore saved selection first (highest priority)
            if (this.savedSelections[settingKey]) {
                const savedOption = selector.querySelector(`option[value="${this.savedSelections[settingKey]}"]`);
                if (savedOption) {
                    selector.value = this.savedSelections[settingKey];
                    return; // Don't auto-select if we have a saved selection
                }
            }

            // Auto-select recommended account if no saved selection
            this.autoSelectRecommendedAccount(selector, accounts, businessType);
        });
    }

    /**
     * Auto-select recommended account based on setting key and business type
     */
    autoSelectRecommendedAccount(selector, accounts, businessType) {
        const settingKey = selector.name.replace('settings[', '').replace(']', '');

        // Try to get recommendation from cache first
        const cachedRecommendations = this.accountRecommendations[businessType];
        if (cachedRecommendations && cachedRecommendations[settingKey]) {
            const recommendation = cachedRecommendations[settingKey];
            this.applyRecommendation(selector, accounts, recommendation, businessType);
            return;
        }

        // Fallback to old method if no cached recommendation
        this.autoSelectRecommendedAccountFallback(selector, accounts, businessType);
    }

    /**
     * Apply recommendation to selector
     */
    applyRecommendation(selector, accounts, recommendation, businessType) {
        if (!recommendation || !recommendation.keywords) return;

        const keywords = recommendation.keywords;
        let bestMatch = null;
        let bestScore = 0;

        accounts.forEach(account => {
            let score = 0;
            const accountText = `${account.account_code} ${account.name}`.toLowerCase();

            keywords.forEach(keyword => {
                if (accountText.includes(keyword.toLowerCase())) {
                    score += keyword.length;
                }
            });

            // Prefer accounts with matching business type
            if (account.business_type === businessType || account.business_type === 'all') {
                score += 10;
            }

            if (score > bestScore) {
                bestScore = score;
                bestMatch = account;
            }
        });

        if (bestMatch && bestScore > 0) {
            selector.value = bestMatch.id;
            this.highlightSelectedOption(selector);
        }
    }

    /**
     * Highlight selected option
     */
    highlightSelectedOption(selector) {
        if (selector.parentElement) {
            selector.parentElement.style.backgroundColor = this.config.ui.highlightColor;
            setTimeout(() => {
                if (selector.parentElement) {
                    selector.parentElement.style.backgroundColor = '';
                }
            }, this.config.ui.highlightDuration);
        }
    }

    /**
     * Fallback auto-select method using hardcoded recommendations
     */
    autoSelectRecommendedAccountFallback(selector, accounts, businessType) {
        const settingKey = selector.name.replace('settings[', '').replace(']', '');

        // Fallback recommendations
        const fallbackRecommendations = {
            'sales_account': {
                'trading': ['penjualan', 'sales', '4-'],
                'service': ['pendapatan jasa', 'jasa', '4-'],
                'all': ['penjualan', 'sales', 'pendapatan', '4-']
            },
            'purchase_account': {
                'trading': ['pembelian barang', 'purchase', '5-'],
                'service': ['beban', 'expense', '6-'],
                'all': ['pembelian', 'purchase', 'beban', '5-', '6-']
            },
            'inventory_account': {
                'trading': ['persediaan barang', 'inventory', '1-3'],
                'all': ['persediaan', 'inventory', 'stock', '1-3']
            },
            'accounts_receivable_account': {
                'all': ['piutang', 'receivable', 'tagihan', '1-1']
            },
            'accounts_payable_account': {
                'all': ['hutang', 'payable', 'utang', '2-1']
            },
            'cash_account': {
                'all': ['kas', 'cash', 'tunai', '1-1-001']
            },
            'bank_account': {
                'all': ['bank', 'rekening', '1-1-002']
            }
        };

        const recommendations = fallbackRecommendations[settingKey];

        if (!recommendations) return;

        const keywords = recommendations[businessType] || recommendations['all'] || [];

        let bestMatch = null;
        let bestScore = 0;

        accounts.forEach(account => {
            let score = 0;
            const accountText = `${account.account_code} ${account.name}`.toLowerCase();

            keywords.forEach(keyword => {
                if (accountText.includes(keyword.toLowerCase())) {
                    score += keyword.length;
                }
            });

            // Prefer accounts with matching business type
            if (account.business_type === businessType || account.business_type === 'all') {
                score += 10;
            }

            if (score > bestScore) {
                bestScore = score;
                bestMatch = account;
            }
        });

        // Auto-select the best match
        if (bestMatch && bestScore > 0) {
            selector.value = bestMatch.id;
            this.highlightElement(selector);
        }
    }

    /**
     * Highlight element temporarily
     */
    highlightElement(element) {
        element.style.backgroundColor = this.config.ui.highlightColor;
        setTimeout(() => {
            element.style.backgroundColor = '';
        }, this.config.ui.highlightDuration);
    }

    /**
     * Update business type indicator
     */
    updateBusinessTypeIndicator(selectedType) {
        if (this.elements.selectedBusinessTypeName) {
            this.elements.selectedBusinessTypeName.textContent = this.businessTypeNames[selectedType] || selectedType;
        }
        if (this.elements.currentIndicator) {
            this.elements.currentIndicator.classList.remove('hidden');
        }
    }

    /**
     * Reset all settings
     */
    resetSettings() {
        if (typeof Swal === 'undefined') {
            if (confirm('Reset pengaturan? Semua pilihan akun akan dikosongkan.')) {
                this.performReset();
            }
            return;
        }

        Swal.fire({
            title: 'Reset Pengaturan?',
            text: "Semua pilihan akun akan dikosongkan. Yakin ingin melanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.performReset();
                this.showSuccessMessage('Pengaturan berhasil direset!');
            }
        });
    }

    /**
     * Perform actual reset
     */
    performReset() {
        this.elements.accountSelectors.forEach(select => {
            select.value = '';
            if (this.tomSelectInstances[select.id]) {
                this.tomSelectInstances[select.id].setValue('');
            }
        });
    }

    /**
     * Handle form submission
     */
    handleFormSubmission(event) {
        const submitBtn = event.target.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }
    }

    /**
     * Load saved business type from storage
     */
    loadSavedBusinessType() {
        const savedBusinessType = sessionStorage.getItem(this.config.storage.businessTypeKey);
        if (savedBusinessType && savedBusinessType !== 'all' && this.elements.businessTypeSelector) {
            this.elements.businessTypeSelector.value = savedBusinessType;
            this.applyBusinessTypeFilter(savedBusinessType);
        }
    }

    /**
     * Save business type to storage
     */
    saveBusinessTypeToStorage() {
        if (this.elements.businessTypeSelector) {
            sessionStorage.setItem(this.config.storage.businessTypeKey, this.elements.businessTypeSelector.value);
        }
    }

    /**
     * Show loading indicator
     */
    showLoading() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.remove('hidden');
        }
        if (this.elements.settingsContent) {
            this.elements.settingsContent.classList.add('hidden');
        }
    }

    /**
     * Hide loading indicator
     */
    hideLoading() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.add('hidden');
        }
        if (this.elements.settingsContent) {
            this.elements.settingsContent.classList.remove('hidden');
        }
    }

    /**
     * Show success message
     */
    showSuccessMessage(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            console.log('Success:', message);
        }
    }

    /**
     * Show error message
     */
    showErrorMessage(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        } else {
            console.error('Error:', message);
            alert('Error: ' + message);
        }
    }

    /**
     * Get CSRF token
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.accountingSettingsManager = new AccountingSettingsManager();
});
</script>
@endpush
