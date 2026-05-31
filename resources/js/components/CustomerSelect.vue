<script setup>
import { onMounted, ref } from 'vue';
import { getCustomers } from '../api.js';

const emit = defineEmits(['select']);

const customers = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        const { data } = await getCustomers();
        customers.value = data;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});

const tierBadge = {
    gold: 'bg-amber-100 text-amber-800',
    silver: 'bg-slate-200 text-slate-700',
    bronze: 'bg-orange-100 text-orange-800',
};
</script>

<template>
    <section>
        <h1 class="mb-1 text-lg font-semibold text-slate-900">Which customer are you ordering for?</h1>
        <p class="mb-4 text-sm text-slate-500">Pick the business account before browsing the catalogue.</p>

        <p v-if="loading" class="text-sm text-slate-500">Loading customers…</p>
        <p v-else-if="error" class="text-sm text-red-600">{{ error }}</p>

        <ul v-else class="divide-y divide-slate-100 overflow-hidden rounded-lg border border-slate-200 bg-white">
            <li v-for="c in customers" :key="c.id">
                <button
                    class="flex w-full items-center justify-between px-4 py-3 text-left hover:bg-slate-50"
                    @click="emit('select', c)"
                >
                    <span>
                        <span class="font-medium text-slate-900">{{ c.company_name }}</span>
                        <span class="ml-2 text-xs text-slate-400">{{ c.email }}</span>
                    </span>
                    <span class="rounded px-2 py-0.5 text-xs font-medium capitalize" :class="tierBadge[c.tier]">
                        {{ c.tier }}
                    </span>
                </button>
            </li>
        </ul>
    </section>
</template>
