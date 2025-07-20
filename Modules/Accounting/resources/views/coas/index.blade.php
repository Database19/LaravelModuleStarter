@extends('layouts.app')

@section('content')
<div
    class="mx-auto py-6 sm:px-6 lg:px-8"
    x-data="accountManager()"
>
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Chart of Accounts</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua akun yang terdaftar dalam sistem.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button @click="openCreateModal()" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Akun
            </button>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Kode Akun</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Akun</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipe</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($accounts as $account)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $account->account_code }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $account->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ucfirst($account->type) }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <button @click="openEditModal({{ $account->id }})" type="button"
                                    class="inline-flex items-center rounded-md bg-indigo-100 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                    Edit
                                </button>
                                <x-delete-button :action="route('coas.destroy', $account->id)" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>

    {{-- Komponen Modal --}}
    <x-modal name="account-form-modal" title="Form Akun" max-width="lg">
        <form @submit.prevent="submitForm()" id="account-form" :action="formAction" data-ajax-form>
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            {{-- Form Partial --}}
            @include('accounting::coas._form')

            <div class="mt-6 flex justify-end gap-x-3">
                <button @click.prevent="$dispatch('close-modal')" type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
</div>
@endsection

@push('scripts')
<script>
function accountManager() {
  return {
    isLoading: false,
    modalTitle: '',
    formAction: '',
    formMethod: 'POST',
    formData: {
      account_code: '',
      name: '',
      type: '',
      is_active: true,
    },
    openCreateModal() {
      this.modalTitle = 'Tambah Akun Baru';
      this.formAction = '{{ route("coas.store") }}';
      this.formMethod = 'POST';
      this.formData = { account_code: '', name: '', type: '', is_active: true };
      this.$dispatch('open-modal', { name: 'account-form-modal' });
    },
    async openEditModal(accountId) {
      this.modalTitle = 'Edit Akun';
      this.formAction = `/accounting/${accountId}`;
      this.formMethod = 'PUT';
      try {
        const response = await fetch(`/accounting/${accountId}/edit`);
        const data = await response.json();
        this.formData = data;
        this.$dispatch('open-modal', { name: 'account-form-modal' });
      } catch (error) {
        Swal.fire('Error!', 'Gagal memuat data untuk diedit.', 'error');
      }
    },
    async submitForm() {
        window.showLoader();
      try {
        const response = await fetch(this.formAction, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
          },
          body: new FormData(document.getElementById('account-form')),
        });

        const result = await response.json();
        if (!response.ok) {
            // Tampilkan error validasi dari Laravel
            const errors = Object.values(result.errors).map(e => `<li>${e}</li>`).join('');
            Swal.fire('Gagal!', `<ul class="text-left list-disc pl-5">${errors}</ul>`, 'error');
            throw new Error('Validation failed');
        }

        this.$dispatch('close-modal');
        window.hideLoader();
        await Swal.fire('Berhasil!', result.message, 'success');
        window.location.reload(); // Reload halaman untuk melihat perubahan
      } catch (error) {
        if (error.message !== 'Validation failed') {
            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
        }
      } finally {
        window.hideLoader(); // <-- Sembunyikan loader SECARA MANUAL
      }
    },
  };
}
</script>
@endpush
