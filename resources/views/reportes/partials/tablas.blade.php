<flux:card>
    <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Reservas por estado</flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Estado</flux:table.column>
            <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Total</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($reservasPorEstado as $estado => $total)
                <flux:table.row>
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $estado }}</flux:table.cell>
                    <flux:table.cell align="center">
                        <flux:badge color="amber" size="sm" class="!bg-amber-100 !text-amber-800 !font-semibold">{{ $total }}</flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="2" align="center">
                        <p class="py-6">Sin datos.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>

<flux:card>
    <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Pagos por método</flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Método</flux:table.column>
            <flux:table.column align="center" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Pagos</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($pagosPorMetodo as $pago)
                <flux:table.row>
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $pago->metodo_pago }}</flux:table.cell>
                    <flux:table.cell align="center">{{ $pago->total }}</flux:table.cell>
                    <flux:table.cell align="end">${{ number_format($pago->monto, 2) }}</flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="3" align="center">
                        <p class="py-6">Sin datos.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>

<flux:card>
    <flux:heading size="lg" class="mb-4 !text-slate-800 !font-semibold">Gastos por categoría</flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Categoría</flux:table.column>
            <flux:table.column align="end" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Monto</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($gastosPorCategoria as $gasto)
                <flux:table.row>
                    <flux:table.cell variant="strong" class="!text-slate-900 !font-medium">{{ $gasto->categoria }}</flux:table.cell>
                    <flux:table.cell align="end">${{ number_format($gasto->monto, 2) }}</flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="2" align="center">
                        <p class="py-6">Sin datos.</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</flux:card>
