<flux:card class="overflow-x-auto">
    @php
        $roles = $this->roles;
    @endphp

    <flux:table class="w-full min-w-[860px] border-collapse text-sm">
        <flux:table.columns>
            <flux:table.column class="sticky left-0 z-20 bg-slate-50 dark:bg-zinc-800/80">
                <flux:text size="sm" class="!text-slate-700 !font-semibold uppercase !text-xs tracking-wider">Permiso</flux:text>
            </flux:table.column>

            @foreach ($roles as $rol)
                <flux:table.column align="center" class="px-3 !text-slate-700 !font-semibold uppercase !text-xs tracking-wider">
                    <span class="inline-flex items-center gap-1.5">
                        {{ Str::headline($rol->name) }}
                        @if ($this->esRolInmutable($rol->name))
                            <flux:icon.lock-closed class="size-3 text-zinc-400" />
                        @endif
                    </span>
                </flux:table.column>
            @endforeach
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->permisosPorModulo as $modulo => $permisosDelModulo)
                <flux:table.row>
                    <flux:table.cell colspan="{{ $roles->count() + 1 }}" class="!bg-amber-50/60 dark:!bg-amber-950/20">
                        <flux:text size="sm" class="!text-amber-800 dark:!text-amber-300 !font-semibold uppercase !text-xs tracking-wider">
                            {{ $this->etiquetaModulo($modulo) }}
                        </flux:text>
                    </flux:table.cell>
                </flux:table.row>

                @foreach ($permisosDelModulo as $permiso)
                    <flux:table.row :key="$permiso->id">
                        <flux:table.cell variant="strong" class="sticky left-0 z-10 bg-white dark:bg-zinc-900 !text-slate-900 !font-medium">
                            {{ $this->etiquetaAccion(Str::after($permiso->name, '.')) }}
                        </flux:table.cell>

                        @foreach ($roles as $rol)
                            @php
                                $deshabilitado = ! $this->puedeEditar() || $this->esRolInmutable($rol->name);
                            @endphp

                            <flux:table.cell align="center" class="px-3">
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
                            </flux:table.cell>
                        @endforeach
                    </flux:table.row>
                @endforeach
            @endforeach
        </flux:table.rows>
    </flux:table>
</flux:card>
