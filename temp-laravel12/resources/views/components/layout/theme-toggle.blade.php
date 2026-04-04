<div class="flex items-center gap-1 rounded-2xl border border-border/60 bg-secondary/35 p-1">
    <button
        type="button"
        @click="setTheme('light')"
        class="rounded-xl px-3 py-2 text-xs font-bold transition"
        :class="theme === 'light' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        Light
    </button>
    <button
        type="button"
        @click="setTheme('dark')"
        class="rounded-xl px-3 py-2 text-xs font-bold transition"
        :class="theme === 'dark' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        Dark
    </button>
    <button
        type="button"
        @click="setTheme('system')"
        class="rounded-xl px-3 py-2 text-xs font-bold transition"
        :class="theme === 'system' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
    >
        Auto
    </button>
</div>

