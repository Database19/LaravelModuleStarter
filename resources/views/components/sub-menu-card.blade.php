<div class="card">
    <div class="card-header">
        <p>{{ $menu->description }}</p>
    </div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="container">
            <div class="row g-4">
                @if($menu && $menu->subMenus->isNotEmpty())
                    @foreach($menu->subMenus as $subMenu)
                        @if($subMenu->is_active)
                            <div class="col-md-4">
                                <div class="card-content h-100 shadow-sm" style="border-radius: 0;">
                                    <i class="{{ $subMenu->icons }}"></i>
                                    <a href="{{ $subMenu->route }}" class="d-flex flex-column justify-content-between align-items-center p-3 text-decoration-none" style="height: 100%;">
                                        <span class="fs-3 fw-bold text-center text-dark">{{ $subMenu->name }}</span>
                                        <span class="text-center text-dark">{{ $subMenu->description }}</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>No active submenus found for this menu.</p>
                @endif
            </div>
        </div>
    </div>
</div>
