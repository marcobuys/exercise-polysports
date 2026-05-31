<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    lines: { type: Array, required: true },
});

const emit = defineEmits(['remove', 'checkout']);

const expanded = ref(false);

const cartTotal = computed(() => props.lines.reduce((sum, line) => sum + line.quantity, 0));
</script>

<template>
    <div class="sticky top-0 z-10 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-2">
            <button class="flex items-center gap-3 text-sm" @click="expanded = !expanded">
                <span class="font-medium text-slate-700">🛒 {{ lines.length }} line{{ lines.length === 1 ? '' : 's' }}</span>
                <span class="font-semibold text-slate-900">€{{ cartTotal }}</span>
                <span class="text-xs text-slate-400">{{ expanded ? 'hide' : 'view' }}</span>
            </button>
            <button
                class="rounded bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:bg-slate-300"
                :disabled="lines.length === 0"
                @click="emit('checkout')"
            >
                Checkout
            </button>
        </div>

        <div v-if="expanded && lines.length" class="mx-auto max-w-5xl px-4 pb-3">
            <ul class="divide-y divide-slate-100 rounded border border-slate-200">
                <li
                    v-for="line in lines"
                    :key="line.product_variant_id"
                    class="flex items-center justify-between px-3 py-2 text-sm"
                >
                    <span class="text-slate-700">
                        {{ line.product_name }} — EU {{ line.eu_size }} × {{ line.quantity }}
                    </span>
                    <span class="flex items-center gap-3">
                        <span class="text-slate-600">€{{ line.unit_price_cents }}</span>
                        <button class="text-xs text-red-500 hover:text-red-700" @click="emit('remove', line.product_variant_id)">
                            remove
                        </button>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>
