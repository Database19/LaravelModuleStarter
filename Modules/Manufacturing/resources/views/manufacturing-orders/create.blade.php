@extends('layouts.app')
@section('header', 'Buat Manufacturing Order Baru')
@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8" x-data="manufacturingOrderForm()">
    <form action="{{ route('manufacturing.manufacturing-orders.store') }}" method="POST">
        @csrf
        @include('manufacturing::manufacturing-orders._form', ['boms' => $boms])
    </form>
</div>
@endsection
@push('scripts')
<script>
function manufacturingOrderForm(data = {}) {
  return {
    selectedBomId: data.initialBomId || '',
    quantityToProduce: data.initialQuantity || 1,
    bomItems: [],
    isLoading: false,
    init() {
        if (this.selectedBomId) {
            this.fetchBomDetails();
        }
    },
    async fetchBomDetails() {
      if (!this.selectedBomId) {
        this.bomItems = [];
        return;
      }
      this.isLoading = true;
      try {
        const response = await fetch(`/manufacturing/boms/${this.selectedBomId}/details`);
        const data = await response.json();
        this.bomItems = data;
      } catch (error) {
        console.error('Error fetching BOM details:', error);
        Swal.fire('Error!', 'Gagal memuat detail komponen.', 'error');
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>
@endpush
