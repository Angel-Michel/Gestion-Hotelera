<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Limpieza</flux:heading>
            <flux:subheading>
                Gestiona las tareas de limpieza de las habitaciones.
            </flux:subheading>
        </div>

        <flux:button type="button" variant="primary" wire:click="crear" class="shrink-0">
            <flux:icon.plus class="size-4" />
            Nueva tarea
        </flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Habitación</flux:table.column>
                <flux:table.column>Responsable</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column>Notas</flux:table.column>
                <flux:table.column align="end">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($tareas as $tarea)
                    <flux:table.row :key="$tarea->id">
                        <flux:table.cell variant="strong">Hab. {{ $tarea->habitacion?->numero_habitacion ?? '—' }}</flux:table.cell>
                        <flux:table.cell>{{ $tarea->usuario?->name ?? '—' }}</flux:table.cell>
                        <flux:table.cell>
                            <x-estado-badge :estado="$tarea->estado" />
                        </flux:table.cell>
                        <flux:table.cell>{{ $tarea->notas ?? '—' }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="button" size="sm" variant="outline" wire:click="editar({{ $tarea->id }})">
                                    <flux:icon.pencil-square class="size-4" />
                                    Editar
                                </flux:button>
                                <flux:button type="button" size="sm" variant="danger" wire:click="eliminar({{ $tarea->id }})" wire:confirm="¿Eliminar esta tarea?">
                                    <flux:icon.trash class="size-4" />
                                    Eliminar
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" align="center">
                            <p class="py-8">No hay tareas de limpieza registradas.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="limpieza-form" wire:model="mostrarModal" class="w-full max-w-lg">
        <form wire:submit="guardar">
            <div class="mb-6">
                <flux:heading size="lg">{{ $tareaId ? 'Editar tarea' : 'Nueva tarea' }}</flux:heading>
                <flux:subheading>Completa los datos de la tarea de limpieza.</flux:subheading>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Habitación</flux:label>
                    <select wire:model="habitacion_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona…</option>
                        @foreach ($habitaciones as $habitacion)
                            <option value="{{ $habitacion->id }}">Hab. {{ $habitacion->numero_habitacion }} ({{ $habitacion->estado }})</option>
                        @endforeach
                    </select>
                    <flux:error name="habitacion_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Responsable</flux:label>
                    <select wire:model="user_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        <option value="">Selecciona…</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    <flux:error name="user_id" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Estado</flux:label>
                    <select wire:model="estado" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}">{{ $estado }}</option>
                        @endforeach
                    </select>
                    <flux:error name="estado" />
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Notas</flux:label>
                    <textarea wire:model="notas" rows="3" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"></textarea>
                    <flux:error name="notas" />
                </flux:field>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="cerrarModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">
                    <flux:icon.check class="size-4" />
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
