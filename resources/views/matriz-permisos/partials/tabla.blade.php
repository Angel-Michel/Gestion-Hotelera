@php $roles = $this->roles; @endphp

<flux:card class="overflow-x-auto">
    <table class="w-full min-w-[860px] border-collapse text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-zinc-700 dark:bg-zinc-800/50">
                <th class="sticky left-0 z-20 bg-slate-50 px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider text-slate-700 dark:bg-zinc-800 dark:text-zinc-200">
                    Permiso
                </th>

                @foreach ($roles as $rol)
                    <th class="px-3 py-3 text-center font-semibold text-xs uppercase tracking-wider text-slate-700 dark:text-zinc-200">
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
                    <td colspan="{{ $roles->count() + 1 }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-800 dark:text-amber-300">
                        {{ $this->etiquetaModulo($modulo) }}
                    </td>
                </tr>

                @foreach ($permisosDelModulo as $permiso)
                    <tr>
                        <td class="sticky left-0 z-10 bg-white px-4 py-2.5 font-medium text-slate-900 dark:bg-zinc-900 dark:text-zinc-200">
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
