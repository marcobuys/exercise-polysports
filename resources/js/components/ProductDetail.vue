<script setup>
import { onMounted, ref } from 'vue';
import { getProduct } from '../api.js';
import { formatEuros } from '../money.js';
import OrderGrid from './OrderGrid.vue';

const props = defineProps({
    customer: { type: Object, required: true },
    product: { type: Object, required: true },
});

const emit = defineEmits(['add-to-cart', 'back']);

const detail = ref(null);
const loading = ref(true);

onMounted(async () => {
    const { data } = await getProduct(props.product.id, props.customer.id);
    detail.value = data;
    loading.value = false;
});
</script>

<template>
    <section>
        <button class="mb-4 text-sm text-slate-500 hover:text-slate-800" @click="emit('back')">← Back to catalogue</button>

        <p v-if="loading" class="text-sm text-slate-500">Loading…</p>

        <div v-else class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex gap-4">
                <div class="flex h-24 w-24 items-center justify-center rounded bg-slate-100 text-5xl">👟</div>
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">{{ detail.name }}</h1>
                    <p class="text-sm text-slate-400">{{ detail.sku }} · {{ detail.colorway }} · {{ detail.category }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ formatEuros(detail.price_cents) }}</p>
                    <p class="text-xs text-slate-400">{{ detail.tier }} tier price</p>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-4">
                <h2 class="mb-2 text-sm font-medium text-slate-700">Order across sizes</h2>
                <OrderGrid :product="detail" @add="emit('add-to-cart', $event)" />
            </div>
        </div>
    </section>
</template>
