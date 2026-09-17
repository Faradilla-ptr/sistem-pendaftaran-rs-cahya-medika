@if ($paginator->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:4px 0">
        {{-- SUMMARY INFO --}}
        <div style="font-size:12px;color:#64748b;font-weight:500">
            Menampilkan <span style="font-weight:700;color:#0f172a">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai <span style="font-weight:700;color:#0f172a">{{ $paginator->lastItem() ?? 0 }}</span>
            dari <span style="font-weight:700;color:#0f172a">{{ $paginator->total() }}</span> total data
        </div>

        {{-- PAGINATION NAVIGATION --}}
        <div style="display:flex;align-items:center;gap:6px">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;height:34px;padding:0 12px;border-radius:9px;background:#f1f5f9;color:#cbd5e1;font-size:12px;font-weight:600;cursor:not-allowed">
                    <i class="fas fa-chevron-left" style="font-size:10px;margin-right:4px"></i> Seblm
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;height:34px;padding:0 12px;border-radius:9px;background:#ffffff;border:1px solid #cbd5e1;color:#334155;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s ease" onmouseover="this.style.borderColor='#0891b2';this.style.color='#0891b2'" onmouseout="this.style.borderColor='#cbd5e1';this.style.color='#334155'">
                    <i class="fas fa-chevron-left" style="font-size:10px;margin-right:4px"></i> Seblm
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;color:#94a3b8;font-size:12px;font-weight:700">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 8px;border-radius:9px;background:linear-gradient(135deg,#0c4a6e,#0891b2);color:white;font-size:12.5px;font-weight:700;box-shadow:0 3px 10px rgba(8,145,178,0.3)">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 8px;border-radius:9px;background:#ffffff;border:1px solid #e2e8f0;color:#475569;font-size:12.5px;font-weight:600;text-decoration:none;transition:all 0.15s ease" onmouseover="this.style.borderColor='#0891b2';this.style.color='#0891b2';this.style.background='#f0f9ff'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';this.style.background='#ffffff'">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;height:34px;padding:0 12px;border-radius:9px;background:#ffffff;border:1px solid #cbd5e1;color:#334155;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s ease" onmouseover="this.style.borderColor='#0891b2';this.style.color='#0891b2'" onmouseout="this.style.borderColor='#cbd5e1';this.style.color='#334155'">
                    Lanjut <i class="fas fa-chevron-right" style="font-size:10px;margin-left:4px"></i>
                </a>
            @else
                <span style="display:inline-flex;align-items:center;justify-content:center;height:34px;padding:0 12px;border-radius:9px;background:#f1f5f9;color:#cbd5e1;font-size:12px;font-weight:600;cursor:not-allowed">
                    Lanjut <i class="fas fa-chevron-right" style="font-size:10px;margin-left:4px"></i>
                </span>
            @endif
        </div>
    </div>
@endif
