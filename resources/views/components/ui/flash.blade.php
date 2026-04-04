@php
    $toasts = [];

    if (session('status')) {
        $toasts[] = [
            'type' => 'success',
            'title' => 'Success',
            'message' => session('status'),
        ];
    }

    foreach ($errors->all() as $error) {
        $toasts[] = [
            'type' => 'error',
            'title' => 'Something went wrong',
            'message' => $error,
        ];
    }
@endphp

@if(count($toasts))
    <div
        x-data="{
            toasts: @js($toasts),
            remove(index) {
                this.toasts.splice(index, 1);
            },
            init() {
                this.toasts.forEach((toast, index) => {
                    setTimeout(() => {
                        if (this.toasts[index]) {
                            this.remove(index);
                        }
                    }, toast.type === 'error' ? 6500 : 3500);
                });
            }
        }"
        class="pointer-events-none fixed right-4 top-4 z-[100] flex w-full max-w-sm flex-col gap-3"
    >
        <template x-for="(toast, index) in toasts" :key="`${toast.type}-${index}`">
            <div
                x-show="true"
                x-transition:enter="transform ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transform ease-in duration-200"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-2 opacity-0"
                class="pointer-events-auto overflow-hidden rounded-[1.75rem] border shadow-[0_28px_70px_-34px_rgba(15,23,42,0.45)] backdrop-blur-2xl"
                :class="toast.type === 'success'
                    ? 'border-emerald-200/80 bg-white/78 dark:border-emerald-500/20 dark:bg-zinc-900/78'
                    : 'border-rose-200/80 bg-white/78 dark:border-rose-500/20 dark:bg-zinc-900/78'"
            >
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.5),transparent_40%)] dark:bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.06),transparent_25%)]"></div>
                <div class="relative flex items-start gap-3 p-4">
                    <div
                        class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-[1rem] border border-white/20 text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.45)]"
                        :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                    >
                        <svg x-show="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg x-show="toast.type === 'error'" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div
                            class="text-sm font-bold"
                            :class="toast.type === 'success' ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'"
                            x-text="toast.title"
                        ></div>
                        <div class="mt-1 text-sm text-foreground/80 dark:text-zinc-200/90" x-text="toast.message"></div>
                    </div>

                    <button
                        type="button"
                        @click="remove(index)"
                        class="rounded-xl p-2 text-muted-foreground transition hover:bg-secondary/60 hover:text-foreground"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
@endif
