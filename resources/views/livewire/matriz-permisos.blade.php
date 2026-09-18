@use('Illuminate\Support\Str')

<div>
    @if ($mensajeExito)
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <p>{{ $mensajeExito }}</p>
        </flux:callout>
    @endif

    @if ($mensajeError)
        <flux:callout variant="danger" icon="x-circle" class="mb-4">
            <p>{{ $mensajeError }}</p>
        </flux:callout>
    @endif

    <div class="mb-6">
        <flux:heading size="xl">Matriz de Permisos</flux:heading>
        <flux:subheading>
            <span class="inline-flex items-center gap-2">
                <span class="relative flex size-2" aria-hidden="true">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                </span>
                Vista granular de permisos: cada fila es un permiso y cada columna un rol.
            </span>
        </flux:subheading>
    </div>

    @if (! $this->puedeEditar())
        <flux:callout variant="warning" icon="lock-closed" class="mb-4">
            <p>
                Solo el rol <strong>Super Admin</strong> puede modificar los permisos.
                Los controles se muestran deshabilitados para el resto de roles.
            </p>
        </flux:callout>
    @endif

    <flux:card class="overflow-x-auto">
        @php
            $roles = $this->roles;
        @endphp

        <table class="w-full min-w-[860px] border-collapse text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <th class="sticky left-0 z-10 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-700 dark:bg-zinc-800/80 dark:text-zinc-200">
                        Permiso
                    </th>

                    @foreach ($roles as $rol)
                        <th class="px-3 py-3 text-center font-semibold text-slate-700 dark:text-zinc-200">
                            <span class="inline-flex items-center gap-1.5">
                                {{ Str::headline($rol->name) }}
                                @if ($this->esRolInmutable($rol->name))
                                    <flux:icon.lock-closed class="size-3 text-zinc-400" />
                                @endif
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @foreach ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                    <tr class="bg-amber-50/60 dark:bg-amber-950/20">
                        <td colspan="{{ $roles->count() + 1 }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-300">
                            {{ $this->etiquetaModulo($modulo) }}
                        </td>
                    </tr>

                    @foreach ($permisosDelModulo as $permiso)
                        <tr>
                            <td class="sticky left-0 z-10 bg-white px-4 py-2.5 font-medium text-slate-700 dark:bg-zinc-900 dark:text-zinc-200">
                                {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }}
                            </td>

                            @foreach ($roles as $rol)
                                @php
                                    $deshabilitado = ! $this->puedeEditar() || $this->esRolInmutable($rol->name);
                                @endphp

                                <td class="px-3 py-2.5 text-center">
                                    <div class="flex justify-center">
                                        <flux:switch
                                            :checked="$this->rolTienePermiso($rol, $permiso->id)"
                                            :disabled="$deshabilitado"
                                            wire:click="alternarPermiso({{ $rol->id }}, {{ $permiso->id }})"
                                            :title="$this->esRolInmutable($rol->name)
                                                ? 'Rol del sistema: sus permisos no pueden modificarse'
                                                : 'Solo el Super Admin puede alternar este permiso'"
                                        />
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </flux:card>
</div>