<div class="flex items-center gap-1 rounded-[1rem] border border-border bg-secondary p-1">
    <button
        type="button"
        @click="setTheme('light')"
        class="rounded-[1rem] px-2 py-2 text-[11px] font-black uppercase tracking-[0.12em] transition sm:px-3 sm:tracking-[0.18em]"
        :class="theme === 'light' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        <span class="sm:hidden">D</span>
        <span class="hidden sm:inline">Day</span>
    </button>
    <button
        type="button"
        @click="setTheme('dark')"
        class="rounded-[1rem] px-2 py-2 text-[11px] font-black uppercase tracking-[0.12em] transition sm:px-3 sm:tracking-[0.18em]"
        :class="theme === 'dark' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        <span class="sm:hidden">N</span>
        <span class="hidden sm:inline">Night</span>
    </button>
    <button
        type="button"
        @click="setTheme('system')"
        class="rounded-[1rem] px-2 py-2 text-[11px] font-black uppercase tracking-[0.12em] transition sm:px-3 sm:tracking-[0.18em]"
        :class="theme === 'system' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        <span class="sm:hidden">A</span>
        <span class="hidden sm:inline">Auto</span>
    </button>
</div>
