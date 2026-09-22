<div
    x-data
    @click="$flux.dark = !$flux.dark"
    role="switch"
    :aria-checked="$flux.dark"
    aria-label="Cambiar entre modo claro y oscuro"
    title="Cambiar tema"
    class="group inline-flex h-7 w-[52px] shrink-0 cursor-pointer items-center rounded-full border border-slate-200 bg-slate-200/80 p-0.5 shadow-inner transition-all duration-300 ease-out hover:scale-105 hover:border-slate-300 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 dark:border-slate-600 dark:bg-slate-700 dark:hover:border-slate-500"
>
    <span
        class="flex size-5 items-center justify-center rounded-full bg-white shadow-md ring-1 ring-black/5 transition-transform duration-300 ease-out group-active:scale-90 dark:translate-x-[22px] dark:bg-slate-950"
    >
        <flux:icon.sun class="size-3 text-amber-500 dark:hidden" />
        <flux:icon.moon class="hidden size-3 text-slate-300 dark:block" />
    </span>
</div>